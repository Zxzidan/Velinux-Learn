<?php

namespace App\Providers;

use App\Models\Assessment;
use App\Support\ActiveLearnerResolver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as BladeView;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(! app()->isProduction());

        View::composer('components.layouts.app', function (BladeView $view): void {
            $data = $view->getData();
            $resolver = app(ActiveLearnerResolver::class);
            $user = $data['user'] ?? $resolver->resolve();

            $notifications = $data['notifications'] ?? [];

            if ($notifications === [] && $user) {
                $recentAssessments = Assessment::query()
                    ->with('quiz:id,title')
                    ->where('user_id', $user->id)
                    ->latest('ai_evaluated_at')
                    ->limit(3)
                    ->get();

                $notifications = $recentAssessments->map(fn (Assessment $assessment): array => [
                    'badge' => $assessment->score.'%',
                    'title' => $assessment->quiz?->title ?? 'AI Assessment',
                    'body' => 'dievaluasi dengan status '.$assessment->status->label().'.',
                    'time' => $assessment->ai_evaluated_at?->diffForHumans() ?? $assessment->created_at?->diffForHumans() ?? 'Baru saja',
                    'url' => route('quizzes.index').'#ai-feedback',
                ])->all();
            }

            $pendingReviewCount = $data['pendingReviewCount'] ?? Assessment::query()->needsReview()->count();

            $view->with([
                'user' => $user,
                'notifications' => $notifications,
                'pendingReviewCount' => $pendingReviewCount,
            ]);
        });
    }
}
