<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Child;
use App\Models\GrowthRecord;
use App\Models\User;
use App\Models\Vaccination;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        $admin = User::create([
            'name' => 'Admin GoKids',
            'email' => 'admin.kelompok3@gmail.com',
            'password' => Hash::make('AdminK3'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Admin No. 1, Jakarta',
        ]);

        // Create User
        $user = User::create([
            'name' => 'Orang Tua Demo',
            'email' => 'kelompok3@gmail.com',
            'password' => Hash::make('UserK3'),
            'role' => 'user',
            'phone' => '089876543210',
            'address' => 'Jl. User No. 2, Bandung',
        ]);

        // Create Children
        $child1 = Child::create([
            'user_id' => $user->id,
            'name' => 'Budi Pratama',
            'birth_date' => Carbon::now()->subYears(3)->subMonths(2),
            'gender' => 'L',
        ]);

        $child2 = Child::create([
            'user_id' => $user->id,
            'name' => 'Sari Dewi',
            'birth_date' => Carbon::now()->subYear()->subMonths(6),
            'gender' => 'P',
        ]);

        $child3 = Child::create([
            'user_id' => $user->id,
            'name' => 'Andi Setiawan',
            'birth_date' => Carbon::now()->subMonths(8),
            'gender' => 'L',
        ]);

        // Create Growth Records for child1
        $growthDates = [
            Carbon::now()->subMonths(5),
            Carbon::now()->subMonths(4),
            Carbon::now()->subMonths(3),
            Carbon::now()->subMonths(2),
            Carbon::now()->subMonth(),
            Carbon::now(),
        ];

        foreach ($growthDates as $i => $date) {
            GrowthRecord::create([
                'child_id' => $child1->id,
                'weight' => 12 + ($i * 0.3),
                'height' => 85 + ($i * 1.5),
                'head_circumference' => 46 + ($i * 0.2),
                'recorded_at' => $date,
                'notes' => 'Pemeriksaan rutin bulan ' . ($i + 1),
            ]);
        }

        // Growth records for child2
        foreach (array_slice($growthDates, 2) as $i => $date) {
            GrowthRecord::create([
                'child_id' => $child2->id,
                'weight' => 8 + ($i * 0.4),
                'height' => 70 + ($i * 2),
                'head_circumference' => 43 + ($i * 0.3),
                'recorded_at' => $date,
                'notes' => 'Pemeriksaan rutin',
            ]);
        }

        // Vaccinations
        $vaccines = [
            ['child_id' => $child1->id, 'vaccine_name' => 'BCG', 'scheduled_date' => Carbon::now()->subMonths(30), 'status' => 'done'],
            ['child_id' => $child1->id, 'vaccine_name' => 'Polio 1', 'scheduled_date' => Carbon::now()->subMonths(28), 'status' => 'done'],
            ['child_id' => $child1->id, 'vaccine_name' => 'DPT-HB-Hib 3', 'scheduled_date' => Carbon::now()->addWeeks(2), 'status' => 'upcoming'],
            ['child_id' => $child1->id, 'vaccine_name' => 'Campak Booster', 'scheduled_date' => Carbon::now()->addMonths(2), 'status' => 'upcoming'],
            ['child_id' => $child2->id, 'vaccine_name' => 'Hepatitis B 1', 'scheduled_date' => Carbon::now()->subMonths(10), 'status' => 'done'],
            ['child_id' => $child2->id, 'vaccine_name' => 'Polio 2', 'scheduled_date' => Carbon::now()->addDays(5), 'status' => 'upcoming'],
            ['child_id' => $child3->id, 'vaccine_name' => 'BCG', 'scheduled_date' => Carbon::now()->subMonths(6), 'status' => 'done'],
            ['child_id' => $child3->id, 'vaccine_name' => 'DPT-HB-Hib 1', 'scheduled_date' => Carbon::now()->addWeeks(1), 'status' => 'upcoming'],
        ];

        foreach ($vaccines as $vaccine) {
            Vaccination::create($vaccine);
        }

        // Articles
        $articles = [
            [
                'admin_id' => $admin->id,
                'title' => 'Panduan Nutrisi Seimbang untuk Balita Usia 1-3 Tahun',
                'category' => 'Nutrisi',
                'content' => '<h2>Pentingnya Nutrisi Seimbang</h2><p>Nutrisi yang seimbang sangat penting untuk mendukung pertumbuhan dan perkembangan anak di usia emas. Pada usia 1-3 tahun, anak membutuhkan asupan makanan yang kaya akan protein, karbohidrat, lemak sehat, vitamin, dan mineral.</p><h3>Kebutuhan Kalori Harian</h3><p>Anak usia 1-3 tahun membutuhkan sekitar 1.000-1.400 kalori per hari, tergantung pada tingkat aktivitasnya. Pastikan untuk memberikan makanan yang bervariasi dan seimbang setiap hari.</p><h3>Makanan yang Direkomendasikan</h3><ul><li>Sayuran hijau: bayam, brokoli, kale</li><li>Buah-buahan: pisang, apel, jeruk</li><li>Protein: telur, ikan, ayam, tahu</li><li>Karbohidrat: nasi, roti gandum, kentang</li></ul><p>Hindari memberikan makanan yang terlalu asin, manis, atau mengandung pengawet berlebih kepada anak-anak.</p>',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(10),
            ],
            [
                'admin_id' => $admin->id,
                'title' => 'Jadwal Imunisasi Lengkap untuk Anak 0-18 Bulan',
                'category' => 'Vaksinasi',
                'content' => '<h2>Jadwal Imunisasi Dasar</h2><p>Imunisasi merupakan cara yang paling efektif untuk melindungi anak dari berbagai penyakit berbahaya. Berikut adalah jadwal imunisasi yang direkomendasikan oleh IDAI (Ikatan Dokter Anak Indonesia).</p><h3>0 Bulan</h3><p>Hepatitis B pertama, diberikan dalam 24 jam setelah lahir.</p><h3>1 Bulan</h3><p>BCG dan Polio 1.</p><h3>2 Bulan</h3><p>DPT-HB-Hib 1 dan Polio 2.</p><h3>3 Bulan</h3><p>DPT-HB-Hib 2 dan Polio 3.</p><h3>4 Bulan</h3><p>DPT-HB-Hib 3, Polio 4, dan IPV.</p><h3>9 Bulan</h3><p>Campak/MR pertama.</p><p>Pastikan untuk selalu berkonsultasi dengan dokter anak mengenai jadwal imunisasi anak Anda.</p>',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(7),
            ],
            [
                'admin_id' => $admin->id,
                'title' => 'Tahapan Tumbuh Kembang Anak Usia 0-5 Tahun',
                'category' => 'Tumbuh Kembang',
                'content' => '<h2>Milestone Perkembangan Anak</h2><p>Setiap anak berkembang dengan kecepatannya masing-masing, namun ada milestone umum yang dapat dijadikan panduan oleh orang tua.</p><h3>0-3 Bulan</h3><p>Bayi mulai bisa mengangkat kepala, tersenyum, dan mengikuti objek bergerak dengan mata.</p><h3>4-6 Bulan</h3><p>Mulai bisa tengkurap, meraih benda, dan babbling (mengoceh).</p><h3>7-12 Bulan</h3><p>Duduk tanpa bantuan, merangkak, berdiri berpegangan, dan mulai mengucapkan kata pertama.</p><h3>1-2 Tahun</h3><p>Berjalan, berlari sederhana, menyusun balok, dan kosakata bertambah pesat.</p><h3>3-5 Tahun</h3><p>Bisa melompat, menggambar bentuk sederhana, berbicara dalam kalimat lengkap, dan mulai bermain sosial.</p>',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(3),
            ],
            [
                'admin_id' => $admin->id,
                'title' => 'Tips Menjaga Kesehatan Anak di Musim Hujan',
                'category' => 'Kesehatan',
                'content' => '<h2>Waspada Penyakit Musim Hujan</h2><p>Musim hujan seringkali membawa berbagai penyakit yang rentan menyerang anak-anak. Berikut beberapa tips untuk menjaga kesehatan anak Anda.</p><h3>1. Jaga Kebersihan</h3><p>Ajarkan anak untuk rajin mencuci tangan dengan sabun, terutama sebelum makan dan setelah bermain.</p><h3>2. Berikan Makanan Bergizi</h3><p>Pastikan asupan vitamin C dan zinc tercukupi untuk meningkatkan daya tahan tubuh.</p><h3>3. Istirahat Cukup</h3><p>Anak membutuhkan tidur 10-13 jam per hari untuk menjaga sistem imunnya.</p>',
                'status' => 'published',
                'published_at' => Carbon::now()->subDay(),
            ],
            [
                'admin_id' => $admin->id,
                'title' => 'Draft: Pentingnya ASI Eksklusif 6 Bulan Pertama',
                'category' => 'Nutrisi',
                'content' => '<h2>ASI Eksklusif</h2><p>Draft artikel tentang pentingnya ASI eksklusif selama 6 bulan pertama kehidupan bayi. Artikel ini masih dalam tahap penulisan.</p>',
                'status' => 'draft',
                'published_at' => null,
            ],
        ];

        foreach ($articles as $article) {
            Article::create($article);
        }
    }
}
