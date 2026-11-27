<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('id')->constrained('roles')->nullOnDelete();
            $table->string('username')->nullable()->unique()->after('name');
            $table->string('cohort')->nullable()->after('email');
            $table->unsignedInteger('xp_points')->default(0)->index()->after('cohort');
            $table->unsignedSmallInteger('current_streak')->default(0)->after('xp_points');
            $table->date('last_activity_date')->nullable()->after('current_streak');
            $table->string('avatar_url')->nullable()->after('last_activity_date');
            $table->text('bio')->nullable()->after('avatar_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
            $table->dropColumn([
                'username',
                'cohort',
                'xp_points',
                'current_streak',
                'last_activity_date',
                'avatar_url',
                'bio',
            ]);
        });

        Schema::dropIfExists('roles');
    }
};
