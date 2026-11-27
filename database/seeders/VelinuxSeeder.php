<?php

namespace Database\Seeders;

use App\Enums\AssessmentStatus;
use App\Enums\DifficultyLevel;
use App\Enums\LabSessionStatus;
use App\Enums\PointSourceType;
use App\Enums\QuestionType;
use App\Enums\QuizType;
use App\Enums\UserRole;
use App\Models\Achievement;
use App\Models\ActivityLog;
use App\Models\AiFeedback;
use App\Models\Answer;
use App\Models\Assessment;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LabChallenge;
use App\Models\LabSession;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Role;
use App\Models\Submission;
use App\Models\User;
use App\Models\UserPoint;
use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class VelinuxSeeder extends Seeder
{
    /**
     * Seed the VELINUX Cybersecurity Learning Platform dataset.
     */
    public function run(): void
    {
        $studentRole = Role::firstOrCreate(
            ['slug' => UserRole::Student->value],
            ['name' => 'Mahasiswa', 'description' => 'Mahasiswa dan anggota pembelajar komunitas VELINUX.']
        );
        $mentorRole = Role::firstOrCreate(
            ['slug' => UserRole::Mentor->value],
            ['name' => 'Mentor', 'description' => 'Mentor teknis dan evaluator Human-in-the-Loop.']
        );
        $adminRole = Role::firstOrCreate(
            ['slug' => UserRole::Admin->value],
            ['name' => 'Admin', 'description' => 'Administrator platform VELINUX UPN Veteran Jawa Timur.']
        );

        $password = Hash::make('password');

        $student = User::firstOrCreate(
            ['email' => 'raka@velinux.test'],
            [
                'role_id' => $studentRole->id,
                'name' => 'Raka Pratama',
                'username' => 'raka.sec',
                'cohort' => 'UPN Veteran Jatim · Informatika',
                'xp_points' => 2450,
                'current_streak' => 7,
                'last_activity_date' => now()->toDateString(),
                'bio' => 'Mahasiswa Informatika & anggota komunitas VELINUX fokus Web Security dan Linux Hardening.',
                'email_verified_at' => now(),
                'password' => $password,
            ]
        );

        $mentor = User::firstOrCreate(
            ['email' => 'mentor@velinux.test'],
            [
                'role_id' => $mentorRole->id,
                'name' => 'Aldi Setiawan',
                'username' => 'aldi.mentor',
                'cohort' => 'VELINUX Core Mentor',
                'xp_points' => 4200,
                'current_streak' => 21,
                'last_activity_date' => now()->toDateString(),
                'bio' => 'Mentor Penetration Testing & Blue Team Defense.',
                'email_verified_at' => now(),
                'password' => $password,
            ]
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@velinux.test'],
            [
                'role_id' => $adminRole->id,
                'name' => 'Admin VELINUX',
                'username' => 'velinux.admin',
                'cohort' => 'VELINUX UPN Veteran Jatim',
                'xp_points' => 5000,
                'current_streak' => 30,
                'last_activity_date' => now()->toDateString(),
                'email_verified_at' => now(),
                'password' => $password,
            ]
        );

        $peers = collect([
            ['name' => 'Nadia Kusuma', 'username' => 'nadia.ctf', 'email' => 'nadia@velinux.test', 'xp_points' => 3120, 'current_streak' => 12],
            ['name' => 'Bima Nugraha', 'username' => 'bima.root', 'email' => 'bima@velinux.test', 'xp_points' => 2790, 'current_streak' => 9],
            ['name' => 'Salsabila Putri', 'username' => 'salsa.dfir', 'email' => 'salsa@velinux.test', 'xp_points' => 2180, 'current_streak' => 5],
            ['name' => 'Farhan Wijaya', 'username' => 'farhan.net', 'email' => 'farhan@velinux.test', 'xp_points' => 1740, 'current_streak' => 4],
        ])->map(fn (array $peer) => User::firstOrCreate(
            ['email' => $peer['email']],
            [
                'role_id' => $studentRole->id,
                'name' => $peer['name'],
                'username' => $peer['username'],
                'cohort' => 'UPN Veteran Jatim · Sistem Informasi',
                'xp_points' => $peer['xp_points'],
                'current_streak' => $peer['current_streak'],
                'last_activity_date' => now()->toDateString(),
                'email_verified_at' => now(),
                'password' => $password,
            ]
        ));

        $webSec = Course::firstOrCreate(
            ['slug' => 'web-application-security'],
            [
                'created_by' => $mentor->id,
                'title' => 'Web Application Security & OWASP Top 10',
                'category' => 'Web Application Security',
                'difficulty' => DifficultyLevel::Intermediate,
                'description' => 'Memahami eksploitasi dan mitigasi kerentanan web modern mulai dari SQL Injection, XSS, CSRF, hingga Broken Access Control.',
                'estimated_hours' => 8,
                'xp_reward' => 350,
                'is_published' => true,
            ]
        );

        $linuxAdmin = Course::firstOrCreate(
            ['slug' => 'linux-administration-hardening'],
            [
                'created_by' => $mentor->id,
                'title' => 'Linux Administration & Server Hardening',
                'category' => 'Linux Administration',
                'difficulty' => DifficultyLevel::Beginner,
                'description' => 'Penguasaan CLI Linux, manajemen permission file, konfigurasi sudoers, SSH key hardening, dan firewall UFW untuk server aman.',
                'estimated_hours' => 6,
                'xp_reward' => 250,
                'is_published' => true,
            ]
        );

        $networking = Course::firstOrCreate(
            ['slug' => 'networking-fundamentals-packet-analysis'],
            [
                'created_by' => $mentor->id,
                'title' => 'Networking Fundamentals & Packet Analysis',
                'category' => 'Networking Fundamentals',
                'difficulty' => DifficultyLevel::Beginner,
                'description' => 'Analisis lalu lintas TCP/IP, port scanning menggunakan Nmap, dan deteksi anomali paket dengan Wireshark.',
                'estimated_hours' => 5,
                'xp_reward' => 220,
                'is_published' => true,
            ]
        );

        $forensics = Course::firstOrCreate(
            ['slug' => 'digital-forensics-incident-response'],
            [
                'created_by' => $admin->id,
                'title' => 'Digital Forensics & Incident Response',
                'category' => 'Digital Forensics',
                'difficulty' => DifficultyLevel::Advanced,
                'description' => 'Investigasi insiden keamanan siber, analisis log autentikasi Linux, ekstraksi artefak memori, dan timeline forensik.',
                'estimated_hours' => 10,
                'xp_reward' => 450,
                'is_published' => true,
            ]
        );

        $crypto = Course::firstOrCreate(
            ['slug' => 'cryptography-and-jwt-security'],
            [
                'created_by' => $mentor->id,
                'title' => 'Applied Cryptography & Token Security',
                'category' => 'Cryptography',
                'difficulty' => DifficultyLevel::Intermediate,
                'description' => 'Konsep hashing, enkripsi simetris/asimetris, TLS handshake, serta mitigasi kelemahan implementasi JWT.',
                'estimated_hours' => 6,
                'xp_reward' => 300,
                'is_published' => true,
            ]
        );

        $sqliModule = Module::firstOrCreate(
            ['course_id' => $webSec->id, 'slug' => 'sql-injection-prevention'],
            [
                'title' => 'SQL Injection & Prepared Statements',
                'description' => 'Analisis query rentan dan penerapan parameterized queries pada aplikasi web.',
                'sort_order' => 1,
                'estimated_minutes' => 45,
            ]
        );

        $xssModule = Module::firstOrCreate(
            ['course_id' => $webSec->id, 'slug' => 'xss-and-csp-defense'],
            [
                'title' => 'Cross-Site Scripting & CSP Defense',
                'description' => 'Context-aware output encoding dan Content Security Policy.',
                'sort_order' => 2,
                'estimated_minutes' => 40,
            ]
        );

        $linuxPermModule = Module::firstOrCreate(
            ['course_id' => $linuxAdmin->id, 'slug' => 'linux-permissions-and-sudo'],
            [
                'title' => 'Filesystem Permissions & Least Privilege',
                'description' => 'Audit permission chmod/chown, SUID binary, dan kebijakan sudoers.',
                'sort_order' => 1,
                'estimated_minutes' => 35,
            ]
        );

        $netEnumModule = Module::firstOrCreate(
            ['course_id' => $networking->id, 'slug' => 'nmap-enumeration-basics'],
            [
                'title' => 'Port Enumeration & Service Fingerprinting',
                'description' => 'Memetakan surface serangan jaringan lokal secara terukur.',
                'sort_order' => 1,
                'estimated_minutes' => 30,
            ]
        );

        $lesson1 = Lesson::firstOrCreate(
            ['module_id' => $sqliModule->id, 'slug' => 'anatomi-sql-injection'],
            [
                'title' => 'Anatomi SQL Injection & String Concatenation',
                'content_type' => 'article',
                'content' => "SQL Injection terjadi ketika input pengguna digabungkan langsung ke dalam string query SQL tanpa sanitasi atau parameter binding. Penyerang dapat memanipulasi logika klausa WHERE, melakukan bypass autentikasi, atau membaca tabel sensitif.\n\nSolusi utama adalah menggunakan Prepared Statements (Parameterized Queries) sehingga engine database memperlakukan input murni sebagai data, bukan instruksi SQL.",
                'code_snippet' => "// RENTAN (Concatenation)\n\$stmt = \$pdo->query(\"SELECT * FROM users WHERE email = '\$email'\");\n\n// AMAN (Prepared Statement)\n\$stmt = \$pdo->prepare('SELECT id, name, email FROM users WHERE email = ?');\n\$stmt->execute([\$email]);",
                'code_language' => 'php',
                'sort_order' => 1,
                'xp_reward' => 30,
            ]
        );

        $lesson2 = Lesson::firstOrCreate(
            ['module_id' => $sqliModule->id, 'slug' => 'prepared-statements-laravel'],
            [
                'title' => 'Parameterized Queries pada Laravel Eloquent',
                'content_type' => 'code',
                'content' => 'Eloquent dan Query Builder Laravel secara default menggunakan PDO parameter binding pada metode where(), find(), dan create(). Risiko muncul kembali saat developer menggunakan whereRaw() atau DB::select() tanpa array binding.',
                'code_snippet' => "// AMAN dengan parameter binding eksplisit\n\$users = User::whereRaw('LOWER(email) = ?', [strtolower(\$email)])->get();",
                'code_language' => 'php',
                'sort_order' => 2,
                'xp_reward' => 35,
            ]
        );

        $lesson3 = Lesson::firstOrCreate(
            ['module_id' => $xssModule->id, 'slug' => 'output-encoding-blade'],
            [
                'title' => 'Auto-Escaping Blade & Mitigasi XSS',
                'content_type' => 'article',
                'content' => 'Gunakan sintaks {{ $value }} untuk melakukan HTML entity encoding otomatis dan hindari {!! $value !!} pada konten yang berasal dari input pengguna.',
                'code_snippet' => "{{-- Aman: otomatis di-escape --}}\n<p>{{ \$comment->body }}</p>",
                'code_language' => 'blade',
                'sort_order' => 1,
                'xp_reward' => 30,
            ]
        );

        $lesson4 = Lesson::firstOrCreate(
            ['module_id' => $linuxPermModule->id, 'slug' => 'audit-suid-dan-permission'],
            [
                'title' => 'Audit SUID Binary & File Permission Linux',
                'content_type' => 'terminal',
                'content' => 'Privilege escalation di Linux sering memanfaatkan file world-writable atau binary SUID yang salah dikonfigurasi. Gunakan command find untuk melakukan audit sistem secara berkala.',
                'code_snippet' => "find / -perm -4000 -type f 2>/dev/null\nls -la /etc/sudoers.d/",
                'code_language' => 'bash',
                'sort_order' => 1,
                'xp_reward' => 25,
            ]
        );

        Lesson::firstOrCreate(
            ['module_id' => $netEnumModule->id, 'slug' => 'tcp-syn-scan-dan-banner'],
            [
                'title' => 'TCP SYN Scan & Service Banner Grabbing',
                'content_type' => 'terminal',
                'content' => 'Enumerasi layanan aktif membantu tim Blue Team mengidentifikasi port yang tidak semestinya terbuka ke publik.',
                'code_snippet' => 'nmap -sS -sV -p 22,80,443,3306 10.10.24.15',
                'code_language' => 'bash',
                'sort_order' => 1,
                'xp_reward' => 25,
            ]
        );

        Video::firstOrCreate(
            ['module_id' => $sqliModule->id, 'title' => 'Demo Eksploitasi & Mitigasi SQL Injection'],
            [
                'lesson_id' => $lesson1->id,
                'description' => 'Walkthrough langsung membandingkan query rentan dan prepared statement pada aplikasi web.',
                'video_url' => 'https://www.youtube.com/embed/ciNHn38EyRc',
                'duration_seconds' => 540,
                'sort_order' => 1,
            ]
        );

        Video::firstOrCreate(
            ['module_id' => $linuxPermModule->id, 'title' => 'Linux Hardening: Audit Permission & SSH'],
            [
                'lesson_id' => $lesson4->id,
                'description' => 'Panduan langkah demi langkah mengamankan VPS Ubuntu untuk mahasiswa VELINUX.',
                'video_url' => 'https://www.youtube.com/embed/ZhMw53or2tY',
                'duration_seconds' => 720,
                'sort_order' => 1,
            ]
        );

        Enrollment::firstOrCreate(
            ['user_id' => $student->id, 'course_id' => $webSec->id],
            ['progress_percentage' => 78, 'status' => 'active', 'enrolled_at' => now()->subDays(12)]
        );
        Enrollment::firstOrCreate(
            ['user_id' => $student->id, 'course_id' => $linuxAdmin->id],
            ['progress_percentage' => 100, 'status' => 'completed', 'enrolled_at' => now()->subDays(20), 'completed_at' => now()->subDays(6)]
        );
        Enrollment::firstOrCreate(
            ['user_id' => $student->id, 'course_id' => $networking->id],
            ['progress_percentage' => 45, 'status' => 'active', 'enrolled_at' => now()->subDays(4)]
        );

        $student->completedLessons()->syncWithoutDetaching([
            $lesson1->id => ['completed_at' => now()->subDays(4)],
            $lesson2->id => ['completed_at' => now()->subDays(3)],
            $lesson4->id => ['completed_at' => now()->subDays(6)],
        ]);

        $dailyQuiz = Quiz::firstOrCreate(
            ['slug' => 'daily-challenge-sqli-audit'],
            [
                'course_id' => $webSec->id,
                'module_id' => $sqliModule->id,
                'title' => 'Daily Challenge: Find the SQLi Vulnerability',
                'description' => 'Audit potongan kode PHP/Laravel berikut dan tentukan mekanisme pencegahan yang paling tepat.',
                'quiz_type' => QuizType::DailyChallenge,
                'time_limit_seconds' => 600,
                'passing_score' => 75,
                'xp_reward' => 100,
                'is_daily' => true,
                'is_published' => true,
            ]
        );

        $sprintQuiz = Quiz::firstOrCreate(
            ['slug' => 'cyber-quiz-linux-networking'],
            [
                'course_id' => $linuxAdmin->id,
                'module_id' => $linuxPermModule->id,
                'title' => 'Cyber Quiz: Linux Hardening & Network Defense',
                'description' => 'Kuis cepat seputar hak akses file Linux, port standar, dan analisis log SSH.',
                'quiz_type' => QuizType::CyberQuiz,
                'time_limit_seconds' => 480,
                'passing_score' => 70,
                'xp_reward' => 50,
                'is_daily' => false,
                'is_published' => true,
            ]
        );

        $q1 = Question::firstOrCreate(
            ['quiz_id' => $dailyQuiz->id, 'sort_order' => 1],
            [
                'question_type' => QuestionType::CodeVulnerability,
                'prompt' => 'Mengapa query berikut rentan terhadap SQL Injection dan bagaimana perbaikannya?',
                'code_snippet' => "\$rows = DB::select(\"SELECT * FROM invoices WHERE code = '\" . \$request->input('code') . \"'\");",
                'options' => [
                    'A' => 'Gunakan parameter binding: DB::select("SELECT * FROM invoices WHERE code = ?", [$request->input("code")])',
                    'B' => 'Tambahkan trim() sebelum concatenation string',
                    'C' => 'Pindahkan query ke method POST agar tidak terlihat di URL',
                    'D' => 'Ganti PDO error mode menjadi silent',
                ],
                'correct_answer' => 'A',
                'rubric' => 'Harus menjelaskan pemisahan instruksi SQL dan data melalui prepared statements / parameterized queries.',
                'points' => 50,
            ]
        );

        Question::firstOrCreate(
            ['quiz_id' => $dailyQuiz->id, 'sort_order' => 2],
            [
                'question_type' => QuestionType::Essay,
                'prompt' => 'Jelaskan cara kerja Prepared Statements dalam mencegah SQL Injection pada aplikasi web.',
                'options' => null,
                'correct_answer' => 'Prepared statements mengompilasi struktur query SQL terlebih dahulu lalu mengirimkan input user sebagai parameter terikat sehingga input tidak pernah dieksekusi sebagai sintaks SQL.',
                'rubric' => 'Poin penuh bila menyebut pre-compilation query plan, parameter binding, dan pemisahan data vs kode SQL.',
                'points' => 50,
            ]
        );

        Question::firstOrCreate(
            ['quiz_id' => $sprintQuiz->id, 'sort_order' => 1],
            [
                'question_type' => QuestionType::MultipleChoice,
                'prompt' => 'Permission angka oktal berapakah yang tepat untuk file private key SSH (~/.ssh/id_ed25519)?',
                'options' => [
                    'A' => '600',
                    'B' => '777',
                    'C' => '644',
                    'D' => '755',
                ],
                'correct_answer' => 'A',
                'rubric' => 'Private key hanya boleh dibaca dan ditulis oleh pemilik file (chmod 600).',
                'points' => 50,
            ]
        );

        $evaluatedAssessment = Assessment::firstOrCreate(
            ['user_id' => $student->id, 'quiz_id' => $dailyQuiz->id],
            [
                'score' => 85,
                'max_score' => 100,
                'status' => AssessmentStatus::Evaluated,
                'ai_evaluated_at' => now()->subHours(2),
                'started_at' => now()->subHours(2)->subMinutes(10),
                'completed_at' => now()->subHours(2),
            ]
        );

        $answer1 = Answer::firstOrCreate(
            ['assessment_id' => $evaluatedAssessment->id, 'question_id' => $q1->id, 'user_id' => $student->id],
            [
                'answer_text' => 'Query rentan karena input digabungkan langsung. Gunakan prepared statements agar input tidak dianggap perintah SQL.',
                'is_correct' => true,
                'score_awarded' => 85,
            ]
        );

        AiFeedback::firstOrCreate(
            ['assessment_id' => $evaluatedAssessment->id],
            [
                'answer_id' => $answer1->id,
                'recommended_course_id' => $webSec->id,
                'summary' => 'Jawaban Anda sudah menjelaskan konsep SQL Injection dengan cukup baik. Namun, penjelasan mengenai prepared statements dan parameterized queries masih perlu ditingkatkan.',
                'strengths' => 'Berhasil mengidentifikasi celah string concatenation pada query SQL.',
                'improvements' => 'Sertakan mekanisme pre-compilation SQL template dan placeholder binding (?, :param).',
                'recommended_topic' => 'SQL Injection Prevention pada modul Web Application Security',
                'confidence_score' => 0.92,
                'model_name' => 'velinux-ai-evaluator-v1',
            ]
        );

        $peerStudent = $peers->first();
        $reviewAssessment = Assessment::firstOrCreate(
            ['user_id' => $peerStudent->id, 'quiz_id' => $dailyQuiz->id],
            [
                'score' => 72,
                'max_score' => 100,
                'status' => AssessmentStatus::NeedsReview,
                'ai_evaluated_at' => now()->subMinutes(40),
                'started_at' => now()->subMinutes(55),
                'completed_at' => now()->subMinutes(40),
            ]
        );

        AiFeedback::firstOrCreate(
            ['assessment_id' => $reviewAssessment->id],
            [
                'recommended_course_id' => $webSec->id,
                'summary' => 'Jawaban esai menyebutkan penggunaan escape string manual. Perlu peninjauan mentor karena penggunaan prepared statement lebih direkomendasikan daripada escaping manual.',
                'strengths' => 'Memahami dampak kebocoran data dari input tak terfilter.',
                'improvements' => 'Ganti pendekatan addslashes/escaping manual dengan PDO parameterized query.',
                'recommended_topic' => 'Parameterized Queries pada Laravel Eloquent',
                'confidence_score' => 0.68,
                'model_name' => 'velinux-ai-evaluator-v1',
            ]
        );

        $linuxLab = LabChallenge::firstOrCreate(
            ['slug' => 'linux-privilege-audit-lab'],
            [
                'course_id' => $linuxAdmin->id,
                'title' => 'Linux Command Line Lab: SUID & Sudoers Audit',
                'category' => 'Linux Command Line Lab',
                'difficulty' => DifficultyLevel::Beginner,
                'scenario' => 'Container sandbox Ubuntu mengalami misconfiguration pada direktori /opt/velinux dan binary permission.',
                'instructions' => "1. Masuk ke sesi lab container terisolasi.\n2. Jalankan perintah enumerasi permission di /opt/velinux.\n3. Temukan file konfigurasi yang menyimpan flag rahasia.",
                'target_host' => '10.10.24.11',
                'terminal_prompt' => "velinux@lab-linux01:~$ find /opt/velinux -perm -4000 2>/dev/null\n/opt/velinux/bin/audit-check\nvelinux@lab-linux01:~$ cat /opt/velinux/.flag.txt",
                'flag' => 'VELINUX{l1nux_perm_h4rdened}',
                'xp_reward' => 100,
                'time_limit_minutes' => 45,
                'is_active' => true,
            ]
        );

        $webLab = LabChallenge::firstOrCreate(
            ['slug' => 'web-security-sqli-sandbox'],
            [
                'course_id' => $webSec->id,
                'title' => 'Web Security Lab: Auth Bypass Investigation',
                'category' => 'Web Security Lab',
                'difficulty' => DifficultyLevel::Intermediate,
                'scenario' => 'Portal simulasi internal memiliki endpoint pencarian log yang belum menerapkan parameterized query.',
                'instructions' => "1. Inspeksi payload query pada target simulasi http://10.10.24.22:8080.\n2. Ekstrak token flag dari tabel audit_tokens.\n3. Submit flag di bawah untuk verifikasi dan peroleh +150 XP.",
                'target_host' => '10.10.24.22',
                'terminal_prompt' => "velinux@lab-web02:~$ curl -s 'http://10.10.24.22:8080/api/logs?q=admin' | jq .\n{\"status\":\"sandbox_ready\",\"hint\":\"inspect_audit_tokens\"}",
                'flag' => 'VELINUX{pr3p4r3d_st4t3m3nt_ftw}',
                'xp_reward' => 150,
                'time_limit_minutes' => 60,
                'is_active' => true,
            ]
        );

        $netLab = LabChallenge::firstOrCreate(
            ['slug' => 'network-scanning-hidden-service'],
            [
                'course_id' => $networking->id,
                'title' => 'Network Scanning Lab: Hidden Service Discovery',
                'category' => 'Network Scanning Lab',
                'difficulty' => DifficultyLevel::Beginner,
                'scenario' => 'Sebuah host simulasi menjalankan service diagnostik tersembunyi di port non-standar.',
                'instructions' => "1. Lakukan scan range port 8000-9000 pada target 10.10.24.33.\n2. Ambil banner service menggunakan netcat (nc) untuk mendapatkan flag.",
                'target_host' => '10.10.24.33',
                'terminal_prompt' => "velinux@lab-net03:~$ nmap -p 8000-9000 10.10.24.33\nPORT     STATE SERVICE\n8443/tcp open  velinux-diag",
                'flag' => 'VELINUX{nm4p_p0rt_d3t3ct1v3}',
                'xp_reward' => 100,
                'time_limit_minutes' => 45,
                'is_active' => true,
            ]
        );

        $cryptoLab = LabChallenge::firstOrCreate(
            ['slug' => 'cryptography-jwt-weak-secret'],
            [
                'course_id' => $crypto->id,
                'title' => 'Cryptography Lab: Weak JWT HMAC Secret',
                'category' => 'Cryptography Lab',
                'difficulty' => DifficultyLevel::Advanced,
                'scenario' => 'Layanan API simulasi menandatangani token HS256 dengan secret key pendek yang mudah ditebak.',
                'instructions' => "1. Dekode header dan payload JWT pada token sampel.\n2. Uji kekuatan secret key secara offline di sandbox.\n3. Dapatkan flag pada endpoint /admin-flag.",
                'target_host' => '10.10.24.44',
                'terminal_prompt' => "velinux@lab-cry04:~$ echo 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9' | base64 -d\n{\"alg\":\"HS256\",\"typ\":\"JWT\"}",
                'flag' => 'VELINUX{jwt_hm4c_str0ng_k3y}',
                'xp_reward' => 200,
                'time_limit_minutes' => 60,
                'is_active' => true,
            ]
        );

        $session = LabSession::firstOrCreate(
            ['user_id' => $student->id, 'lab_challenge_id' => $webLab->id],
            [
                'container_id' => 'vlx-sandbox-8821a',
                'status' => LabSessionStatus::Running,
                'started_at' => now()->subMinutes(12),
                'expires_at' => now()->addMinutes(48),
            ]
        );

        Submission::firstOrCreate(
            ['user_id' => $student->id, 'lab_challenge_id' => $linuxLab->id, 'is_correct' => true],
            [
                'submitted_flag' => 'VELINUX{l1nux_perm_h4rdened}',
                'notes' => 'Berhasil mengaudit SUID binary dan menemukan flag.',
                'score' => 100,
                'status' => 'verified',
                'evaluated_by' => $mentor->id,
                'evaluated_at' => now()->subDays(2),
            ]
        );

        $badge1 = Achievement::firstOrCreate(
            ['slug' => 'first-blood-lab'],
            [
                'name' => 'First Blood Lab',
                'description' => 'Menyelesaikan tantangan Cyber Lab terisolasi pertama.',
                'icon' => 'terminal',
                'badge_color' => 'emerald',
                'xp_bonus' => 100,
                'criteria_type' => 'labs_completed',
                'criteria_value' => 1,
            ]
        );
        $badge2 = Achievement::firstOrCreate(
            ['slug' => 'seven-day-streak'],
            [
                'name' => '7-Day Cyber Streak',
                'description' => 'Belajar dan menyelesaikan tantangan 7 hari berturut-turut.',
                'icon' => 'flame',
                'badge_color' => 'amber',
                'xp_bonus' => 75,
                'criteria_type' => 'streak_days',
                'criteria_value' => 7,
            ]
        );
        $badge3 = Achievement::firstOrCreate(
            ['slug' => 'owasp-defender'],
            [
                'name' => 'OWASP Defender',
                'description' => 'Lulus evaluasi AI Web Application Security dengan skor >= 80.',
                'icon' => 'shield',
                'badge_color' => 'cyan',
                'xp_bonus' => 150,
                'criteria_type' => 'assessment_score',
                'criteria_value' => 80,
            ]
        );

        $student->achievements()->syncWithoutDetaching([
            $badge1->id => ['earned_at' => now()->subDays(2)],
            $badge2->id => ['earned_at' => now()->subDay()],
            $badge3->id => ['earned_at' => now()->subHours(2)],
        ]);

        if (! UserPoint::where('user_id', $student->id)->exists()) {
            UserPoint::create([
                'user_id' => $student->id,
                'source_type' => PointSourceType::Quiz,
                'source_id' => $evaluatedAssessment->id,
                'points' => 50,
                'description' => 'Complete Quiz: Daily Challenge SQLi Audit',
                'awarded_at' => now()->subHours(2),
            ]);
            UserPoint::create([
                'user_id' => $student->id,
                'source_type' => PointSourceType::Challenge,
                'source_id' => $linuxLab->id,
                'points' => 100,
                'description' => 'Finish Challenge: Linux Privilege Audit Lab',
                'awarded_at' => now()->subDays(2),
            ]);
            UserPoint::create([
                'user_id' => $student->id,
                'source_type' => PointSourceType::Streak,
                'points' => 25,
                'description' => 'Daily Streak Bonus (Day 7)',
                'awarded_at' => now()->subHours(1),
            ]);
        }

        if (! ActivityLog::where('user_id', $student->id)->exists()) {
            ActivityLog::create([
                'user_id' => $student->id,
                'action' => 'assessment.ai_evaluated',
                'subject_type' => Assessment::class,
                'subject_id' => $evaluatedAssessment->id,
                'metadata' => ['score' => 85, 'quiz' => $dailyQuiz->title],
                'ip_address' => '127.0.0.1',
                'created_at' => now()->subHours(2),
            ]);
            ActivityLog::create([
                'user_id' => $student->id,
                'action' => 'lab.session_started',
                'subject_type' => LabSession::class,
                'subject_id' => $session->id,
                'metadata' => ['container_id' => $session->container_id],
                'ip_address' => '127.0.0.1',
                'created_at' => now()->subMinutes(12),
            ]);
        }

        // Keep additional lab challenge referenced so seeded catalog has 4 active challenges
        unset($netLab, $cryptoLab);
    }
}
