<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\Article;
use App\Models\BreakingNews;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Page;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@halosulbar.com'],
            [
                'name' => 'Redaksi Kata Sulbar',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'bio' => 'Pemimpin Redaksi dan Administrator Utama Portal Berita Kata Sulbar.',
                'phone' => '081142008899',
                'status' => 'active',
            ]
        );

        $pemred = User::updateOrCreate(
            ['email' => 'pemred@halosulbar.com'],
            [
                'name' => 'Andi Syarifuddin',
                'password' => Hash::make('password'),
                'role' => 'pemred',
                'bio' => 'Pemimpin Redaksi Kata Sulbar dengan pengalaman jurnalisme lebih dari 15 tahun.',
                'phone' => '081234567890',
                'status' => 'active',
            ]
        );

        $editor = User::updateOrCreate(
            ['email' => 'editor@halosulbar.com'],
            [
                'name' => 'Nur Azizah, S.I.Kom',
                'password' => Hash::make('password'),
                'role' => 'editor',
                'bio' => 'Editor Desk Politik dan Pemerintahan Kata Sulbar.',
                'phone' => '081298765432',
                'status' => 'active',
            ]
        );

        $reporter1 = User::updateOrCreate(
            ['email' => 'reporter1@halosulbar.com'],
            [
                'name' => 'Faisal Rasyid',
                'password' => Hash::make('password'),
                'role' => 'reporter',
                'bio' => 'Jurnalis Investigasi dan Liputan Khusus Mamuju & Majene.',
                'phone' => '085211223344',
                'status' => 'active',
            ]
        );

        $reporter2 = User::updateOrCreate(
            ['email' => 'reporter2@halosulbar.com'],
            [
                'name' => 'Wahyu Pratama',
                'password' => Hash::make('password'),
                'role' => 'reporter',
                'bio' => 'Koresponden Wilayah Polewali Mandar dan Mamasa.',
                'phone' => '085233445566',
                'status' => 'active',
            ]
        );

        // 2. Settings
        $settings = [
            'site_name' => 'KATA SULBAR',
            'site_tagline' => 'Suara Sulbar, Untuk Semua',
            'site_description' => 'Portal Berita Terkini dan Terpercaya Sulawesi Barat. Menyajikan berita politik, ekonomi, hukum, wisata, dan suara rakyat dari 6 kabupaten Sulawesi Barat.',
            'site_keywords' => 'kata sulbar, berita sulbar, sulawesi barat, mamuju, polewali mandar, majene, mamasa, pasangkayu, mamuju tengah, portal berita sulbar',
            'contact_email' => 'redaksi@katasulbar.id',
            'contact_phone' => '+62 811-4200-8899',
            'contact_address' => 'Jl. Jenderal Sudirman No. 45, Simboro, Kec. Simboro dan Kepulauan, Kabupaten Mamuju, Sulawesi Barat 91511',
            'location_label' => 'Mamuju, Sulawesi Barat',
            'weather_label' => '28°C',
            'facebook_url' => 'https://facebook.com/katasulbar',
            'instagram_url' => 'https://instagram.com/katasulbar',
            'youtube_url' => 'https://youtube.com/@katasulbar',
            'tiktok_url' => 'https://tiktok.com/@katasulbar',
            'twitter_url' => 'https://x.com/katasulbar',
            'telegram_url' => 'https://t.me/katasulbar',
            'whatsapp_channel' => 'https://whatsapp.com/channel/katasulbar',
            'footer_about' => 'KATA SULBAR adalah media siber independen yang menyuarakan aspirasi masyarakat Sulawesi Barat secara objektif, berimbang, dan berintegritas tinggi untuk kemajuan Banua Malaqbiq.',
            'enable_dark_mode' => '1',
            'enable_comments' => '1',
            'auto_approve_comments' => '0',
        ];

        foreach ($settings as $key => $val) {
            Setting::updateOrCreate(['key' => $key], ['value' => $val, 'group' => 'general']);
        }

        // 3. Categories
        $categoriesData = [
            ['name' => 'Sulbar', 'slug' => 'sulbar', 'color' => '#EF1C25', 'icon' => 'fa-map-marked-alt', 'desc' => 'Kabar berita seputar 6 kabupaten di Sulawesi Barat', 'order' => 1, 'image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80'],
            ['name' => 'Nasional', 'slug' => 'nasional', 'color' => '#074C8C', 'icon' => 'fa-flag', 'desc' => 'Berita aktual tingkat nasional Indonesia', 'order' => 2, 'image' => 'https://images.unsplash.com/photo-1541872703-74c5e44368f9?auto=format&fit=crop&w=600&q=80'],
            ['name' => 'Ekonomi', 'slug' => 'ekonomi', 'color' => '#EF1C25', 'icon' => 'fa-chart-line', 'desc' => 'Informasi bisnis, perbankan, UMKM dan pasar lokal', 'order' => 3, 'image' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?auto=format&fit=crop&w=600&q=80'],
            ['name' => 'Politik', 'slug' => 'politik', 'color' => '#074C8C', 'icon' => 'fa-landmark', 'desc' => 'Dinamika politik daerah, pilkada, dan legislatif', 'order' => 4, 'image' => 'https://images.unsplash.com/photo-1541872703-74c5e44368f9?auto=format&fit=crop&w=600&q=80'],
            ['name' => 'Hukum', 'slug' => 'hukum', 'color' => '#3B2865', 'icon' => 'fa-gavel', 'desc' => 'Peristiwa kriminal, pengadilan, kejaksaan dan kepolisian', 'order' => 5, 'image' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&w=600&q=80'],
            ['name' => 'Pendidikan', 'slug' => 'pendidikan', 'color' => '#074C8C', 'icon' => 'fa-graduation-cap', 'desc' => 'Dunia kampus, sekolah, beasiswa, dan inovasi edukasi', 'order' => 6, 'image' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=600&q=80'],
            ['name' => 'Kesehatan', 'slug' => 'kesehatan', 'color' => '#0F8055', 'icon' => 'fa-stethoscope', 'desc' => 'Layanan RS, tips sehat, stunting, dan program medis', 'order' => 7, 'image' => 'https://images.unsplash.com/photo-1505751172876-fa1923c5c528?auto=format&fit=crop&w=600&q=80'],
            ['name' => 'Pariwisata', 'slug' => 'pariwisata', 'color' => '#E05A1B', 'icon' => 'fa-umbrella-beach', 'desc' => 'Destinasi eksotis, festival budaya, kuliner khas Sulbar', 'order' => 8, 'image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80'],
            ['name' => 'Olahraga', 'slug' => 'olahraga', 'color' => '#0E7A63', 'icon' => 'fa-running', 'desc' => 'Kabar sepak bola, atlet Sulbar, PON dan turnamen', 'order' => 9, 'image' => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?auto=format&fit=crop&w=600&q=80'],
            ['name' => 'Teknologi', 'slug' => 'teknologi', 'color' => '#1D4ED8', 'icon' => 'fa-microchip', 'desc' => 'Gadget, transformasi digital pemda, startup dan internet', 'order' => 10, 'image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=600&q=80'],
            ['name' => 'Opini', 'slug' => 'opini', 'color' => '#475569', 'icon' => 'fa-pen-nib', 'desc' => 'Gagasan kritis akademisi, tokoh masyarakat dan jurnalis', 'order' => 11, 'image' => 'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=600&q=80'],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $cat = Category::updateOrCreate(
                ['slug' => $c['slug']],
                [
                    'name' => $c['name'],
                    'description' => $c['desc'],
                    'color' => $c['color'],
                    'icon' => $c['icon'],
                    'image' => $c['image'],
                    'order' => $c['order'],
                    'is_active' => true,
                ]
            );
            $categories[$c['slug']] = $cat;
        }

        // 4. Tags
        $tagNames = [
            'Mamuju', 'Polewali Mandar', 'Majene', 'Mamasa', 'Pasangkayu', 'Mamuju Tengah',
            'Pemprov Sulbar', 'Gubernur Sulbar', 'DPRD Sulbar', 'Infrastruktur', 'Ekonomi Daerah',
            'Pariwisata Sulbar', 'Festival Budaya', 'Pendidikan Sulbar', 'Digitalisasi',
            'BMKG', 'Pertanian', 'Perikanan Mandar', 'UNSULBAR', 'Trans Sulawesi',
        ];

        $tags = [];
        foreach ($tagNames as $tn) {
            $tag = Tag::updateOrCreate(
                ['slug' => Str::slug($tn)],
                ['name' => $tn]
            );
            $tags[$tn] = $tag;
        }

        // 5. Video Categories
        $videoCatNames = ['Berita Sulbar', 'Politik', 'Pemerintahan', 'Peristiwa', 'Pendidikan', 'Ekonomi', 'Pariwisata', 'Olahraga', 'Teknologi', 'Video Singkat'];
        $videoCats = [];
        foreach ($videoCatNames as $vc) {
            $cat = VideoCategory::updateOrCreate(['slug' => Str::slug($vc)], ['name' => $vc]);
            $videoCats[$vc] = $cat;
        }

        // 6. Breaking News
        $breakingNewsItems = [
            'Pemprov Sulbar Perkuat Infrastruktur Daerah untuk Percepatan Konektivitas Antarwilayah',
            'Festival Literasi Polewali Mandar Resmi Dibuka Ribuan Pegiat dan Siswa',
            'Harga Pangan di Wilayah Polewali Mandar Mulai Bergerak Stabil Pekan Ini',
            'BMKG: Waspada Potensi Hujan Lebat Disertai Angin Kencang di Pesisir Sulbar',
            'DPRD Sulbar Sahkan Perda Perlindungan Wilayah Konservasi Maritim',
        ];

        foreach ($breakingNewsItems as $idx => $bn) {
            BreakingNews::updateOrCreate(
                ['title' => $bn],
                [
                    'url' => '#',
                    'is_active' => true,
                    'order' => $idx + 1,
                ]
            );
        }

        // 7. Articles Data
        $articlesData = [
            [
                'title' => 'Pemprov Sulbar Perkuat Infrastruktur untuk Dukung Pertumbuhan Ekonomi',
                'category_slug' => 'sulbar',
                'is_headline' => true,
                'is_breaking' => true,
                'is_editor_pick' => true,
                'is_featured' => true,
                'thumbnail' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1200&q=80',
                'caption' => 'Pemandangan landmark kota Mamuju dan jalan lingkar arteri pesisir Sulawesi Barat.',
                'views_count' => 1240,
                'excerpt' => 'Pemerintah Provinsi Sulawesi Barat terus memperkuat pembangunan infrastruktur sebagai upaya mendorong pertumbuhan ekonomi daerah dan meningkatkan konektivitas antarwilayah di enam kabupaten.',
                'content' => '<p><strong>MAMUJU, KATA SULBAR</strong> — Pemerintah Provinsi Sulawesi Barat (Pemprov Sulbar) terus mempercepat pembangunan infrastruktur jalan, jembatan, dan sarana transportasi darat guna menopang percepatan ekonomi daerah. Kebijakan ini dinilai sangat strategis mengingat posisi Sulbar yang berhadapan langsung dengan Ibu Kota Nusantara (IKN) di Selat Makassar.</p>
<p>Kepala Dinas Pekerjaan Umum dan Penataan Ruang (PUPR) Sulbar menyampaikan bahwa fokus utama tahun ini meliputi pemeliharaan ruas jalan arteri lingkar luar Mamuju, penanganan titik longsor jalur Trans Sulawesi di perbatasan Majene-Mamuju, serta pembukaan akses jalan antar desa di dataran tinggi Mamasa.</p>
<p>“Konektivitas adalah urat nadi perekonomian masyarakat petani dan nelayan kita di Banua Malaqbiq. Jika akses jalan mulus, maka distribusi hasil panen kakao, kelapa sawit, dan ikan tangkap akan jauh lebih cepat sampai ke pasar dan pelabuhan,” terangnya saat meninjau proyek di Simboro.</p>
<p>Selain jalan raya, pembenahan pelabuhan laut Palipi di Polewali Mandar dan Pelabuhan Feri Simboro Mamuju juga terus dioptimalkan sebagai gerbang logistik penyuplai komoditas pangan menuju IKN Kalimantan Timur.</p>
<p>Masyarakat setempat mengapresiasi langkah nyata percepatan pembangunan ini, yang mulai memangkas waktu tempuh antar sentra produksi dan pasar regional.</p>',
                'tags' => ['Pemprov Sulbar', 'Infrastruktur', 'Mamuju', 'Ekonomi Daerah'],
            ],
            [
                'title' => 'DPRD Sulbar Bahas Rancangan APBD 2027, Fokus pada Pendidikan dan Kesehatan',
                'category_slug' => 'politik',
                'is_headline' => false,
                'is_breaking' => false,
                'is_editor_pick' => true,
                'is_featured' => true,
                'thumbnail' => 'https://images.unsplash.com/photo-1541872703-74c5e44368f9?auto=format&fit=crop&w=800&q=80',
                'caption' => 'Rapat Paripurna DPRD Sulawesi Barat di Gedung DPRD Kompleks Kantor Gubernur Sulbar.',
                'views_count' => 856,
                'excerpt' => 'DPRD Provinsi Sulawesi Barat menggelar rapat paripurna guna membahas arah kebijakan APBD, menitikberatkan alokasi anggaran pada mutu sekolah dan pencegahan stunting balita.',
                'content' => '<p><strong>MAMUJU</strong> — Dewan Perwakilan Rakyat Daerah (DPRD) Sulawesi Barat menggelar rapat paripurna pembahasan rancangan arah umum anggaran APBD. Forum legislatif tersebut menegaskan komitmen agar pembiayaan publik benar-benar diarahkan pada kebutuhan esensial rakyat.</p>
<p>Pimpinan Badan Anggaran DPRD Sulbar menekankan bahwa peningkatan kualitas gedung sekolah di pelosok pegunungan serta perluasan cakupan posyandu terpadu harus menjadi skala prioritas tertinggi.</p>
<p>“Kita tidak boleh membiarkan anak-anak kita belajar di ruang kelas rusak. Begitu pula intervensi gizi anak harus masif sampai ke tingkat dusun demi menuntaskan persoalan stunting di Sulawesi Barat,” ungkap juru bicara dewan.</p>',
                'tags' => ['DPRD Sulbar', 'Pemprov Sulbar', 'Pendidikan Sulbar'],
            ],
            [
                'title' => 'Pesona Pantai Dato Majene Kembali Jadi Favorit Wisatawan Pesisir',
                'category_slug' => 'pariwisata',
                'is_headline' => false,
                'is_breaking' => false,
                'is_editor_pick' => true,
                'is_featured' => true,
                'thumbnail' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80',
                'caption' => 'Tebing karang eksotis dan air laut jernih Pantai Dato di Kabupaten Majene.',
                'views_count' => 724,
                'excerpt' => 'Pantai Dato yang terkenal dengan tebing karang bertingkat dan pasir putihnya kembali dipadati pengunjung dari berbagai daerah selama akhir pekan.',
                'content' => '<p><strong>MAJENE</strong> — Keindahan alam Pantai Dato di Kelurahan Baurung, Kecamatan Banggae Timur, Kabupaten Majene terus memikat wisatawan lokal maupun dari luar provinsi. Tebing karang yang menjorok ke laut biru toska berpadu dengan tangga menurun yang ikonik menjadikannya destinasi favorit swafoto.</p>
<p>Dinas Pariwisata Kabupaten Majene terus menambah fasilitas pendukung seperti gazebo, penerangan bertenaga surya, serta penataan sentra kuliner ikan bakar khas Mandar agar wisatawan merasa semakin nyaman dan betah berlama-lama menikmati matahari terbenam.</p>',
                'tags' => ['Majene', 'Pariwisata Sulbar'],
            ],
            [
                'title' => 'Transformasi Digital di Sulbar, Layanan Publik Pemda Semakin Mudah Diakses',
                'category_slug' => 'teknologi',
                'is_headline' => false,
                'is_breaking' => false,
                'is_editor_pick' => false,
                'is_featured' => true,
                'thumbnail' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80',
                'caption' => 'Pusat komando data dan jaringan digital layanan terpadu Diskominfopers Sulbar.',
                'views_count' => 612,
                'excerpt' => 'Implementasi Sistem Pemerintahan Berbasis Elektronik (SPBE) di Pemprov Sulbar mempermudah warga dalam mengurus dokumen kependudukan dan perizinan usaha secara online.',
                'content' => '<p><strong>MAMUJU</strong> — Era keterbukaan informasi dan digitalisasi pelayanan publik kini terasa nyata di Sulawesi Barat. Melalui integrasi portal layanan satu pintu, masyarakat tidak perlu lagi mengantre panjang di kantor dinas untuk mengurus administrasi pokok.</p>
<p>Layanan terpadu ini juga terhubung dengan command center guna memantau aduan warga seputar jalan rusak, layanan kesehatan, hingga informasi kebencanaan secara waktu nyata (realtime).</p>',
                'tags' => ['Digitalisasi', 'Pemprov Sulbar', 'Mamuju'],
            ],
            [
                'title' => 'Harga Pangan di Polewali Mandar Mulai Stabil Jelang Akhir Tahun',
                'category_slug' => 'ekonomi',
                'is_headline' => false,
                'is_breaking' => false,
                'is_editor_pick' => false,
                'is_featured' => false,
                'thumbnail' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=800&q=80',
                'caption' => 'Aktivitas jual beli komoditas sembako di Pasar Sentral Pekkabata, Polewali Mandar.',
                'views_count' => 524,
                'excerpt' => 'Pasokan beras lokal melimpah dan operasi pasar murah terpadu berhasil menjaga stabilitas harga cabai, bawang, dan minyak goreng di pasar tradisional Polman.',
                'content' => '<p><strong>POLEWALI MANDAR</strong> — Memasuki kuartal akhir tahun, pergerakan harga kebutuhan pokok di sejumlah pasar tradisional Kabupaten Polewali Mandar terpantau terkendali dengan stok yang sangat mencukupi.</p>
<p>Pasokan beras dari lumbung pangan Wonomulyo dan Campalagian yang memasuki masa panen raya memberikan dampak signifikan dalam menahan laju inflasi pangan daerah.</p>',
                'tags' => ['Polewali Mandar', 'Ekonomi Daerah', 'Pertanian'],
            ],
            [
                'title' => 'Disdik Sulbar Dorong Digitalisasi Sekolah di Daerah Terpencil',
                'category_slug' => 'pendidikan',
                'is_headline' => false,
                'is_breaking' => false,
                'is_editor_pick' => false,
                'is_featured' => false,
                'thumbnail' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=800&q=80',
                'caption' => 'Siswa dan guru di pelosok Sulbar memanfaatkan perangkat pembelajaran digital interaktif.',
                'views_count' => 412,
                'excerpt' => 'Bantuan perangkat Chromebook dan router internet satelit mulai didistribusikan ke sekolah-sekolah di wilayah pegunungan Mamasa dan Kalumpang.',
                'content' => '<p><strong>MAMASA</strong> — Dinas Pendidikan dan Kebudayaan Sulbar terus memperkecil kesenjangan mutu pendidikan antara sekolah perkotaan dan sekolah di pelosok pegunungan. Puluhan sekolah dasar dan menengah di daerah 3T (tertinggal, terdepan, terluar) kini mulai dilengkapi perangkat teknologi edukasi modern.</p>',
                'tags' => ['Pendidikan Sulbar', 'Mamasa', 'Digitalisasi'],
            ],
            [
                'title' => 'Dinkes Sulbar Gelar Imunisasi Massal untuk Lindungi Anak Generasi Penerus',
                'category_slug' => 'kesehatan',
                'is_headline' => false,
                'is_breaking' => false,
                'is_editor_pick' => false,
                'is_featured' => false,
                'thumbnail' => 'https://images.unsplash.com/photo-1505751172876-fa1923c5c528?auto=format&fit=crop&w=800&q=80',
                'caption' => 'Tenaga kesehatan memberikan vaksinasi rutin kepada balita di posyandu desa.',
                'views_count' => 398,
                'excerpt' => 'Program Pekan Imunisasi Nasional di Sulbar ditargetkan menjangkau lebih dari 80.000 anak balita di seluruh pelosok desa dan pesisir.',
                'content' => '<p><strong>MAMUJU</strong> — Dinas Kesehatan Sulbar bersama TP-PKK mengintensifkan gerakan jemput bola imunisasi lengkap untuk anak-anak balita. Bidan desa dan relawan kesehatan menyisir pemukiman nelayan dan pegunungan untuk memastikan tidak ada anak yang terlewat.</p>',
                'tags' => ['Pemprov Sulbar', 'Mamuju'],
            ],
            [
                'title' => 'Potensi Perikanan Sulbar Jadi Motor Penggerak Ekonomi Maritim Baru',
                'category_slug' => 'sulbar',
                'is_headline' => false,
                'is_breaking' => false,
                'is_editor_pick' => true,
                'is_featured' => false,
                'thumbnail' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=800&q=80',
                'caption' => 'Perahu nelayan tradisional Sandeq bersandar di bibir teluk Mandar dengan hasil tangkapan melimpah.',
                'views_count' => 621,
                'excerpt' => 'Garis pantai Sulawesi Barat sepanjang lebih dari 677 kilometer menyimpan potensi tangkapan tuna, cakalang, dan budidaya udang vaname bernilai ekspor tinggi.',
                'content' => '<p><strong>POLEWALI MANDAR</strong> — Sektor kelautan dan perikanan terus membuktikan diri sebagai pilar ketahanan ekonomi masyarakat Sulawesi Barat. Dengan garis pantai yang membentang dari Pasangkayu hingga Polman, komoditas hasil laut seperti ikan cakalang, layang, dan kepiting bakau menjadi primadona pasar ekspor.</p>',
                'tags' => ['Perikanan Mandar', 'Ekonomi Daerah', 'Polewali Mandar'],
            ],
            [
                'title' => 'Penguatan Infrastruktur TIK di Wilayah Blankspot Sulawesi Barat',
                'category_slug' => 'teknologi',
                'is_headline' => false,
                'is_breaking' => false,
                'is_editor_pick' => true,
                'is_featured' => false,
                'thumbnail' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?auto=format&fit=crop&w=800&q=80',
                'caption' => 'Pemasangan menara telekomunikasi BTS di perbukitan guna menjangkau desa terpencil.',
                'views_count' => 446,
                'excerpt' => 'Pemerintah menambah puluhan titik pemancar sinyal seluler dan wifi publik di desa-desa terisolir guna pemerataan akses internet merata.',
                'content' => '<p><strong>MAMUJU</strong> — Upaya pengentasan kawasan tanpa sinyal (blankspot) terus digulirkan melalui kerja sama antara Pemprov Sulbar, Bakti Kominfo, dan penyedia layanan seluler terkemuka.</p>',
                'tags' => ['Digitalisasi', 'Teknologi'],
            ],
            [
                'title' => 'Membangun SDM Unggul untuk Menyongsong Sulawesi Barat Berkelanjutan',
                'category_slug' => 'opini',
                'is_headline' => false,
                'is_breaking' => false,
                'is_editor_pick' => true,
                'is_featured' => false,
                'thumbnail' => 'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=800&q=80',
                'caption' => 'Catatan kritis pemerhati kebijakan publik mengenai arah pendidikan vokasi di Sulbar.',
                'views_count' => 315,
                'excerpt' => 'Kekayaan alam Sulbar membutuhkan generasi muda terampil yang menguasai teknologi agromaritim agar nilai tambah hasil bumi dapat dinikmati masyarakat lokal.',
                'content' => '<p><em>Oleh: Dr. Irfan Sanusi, Pengamat Kebijakan Publik Universitas Sulawesi Barat</em></p><p>Sulawesi Barat dikaruniai tanah yang subur, laut yang kaya, dan warisan budaya Mandar yang luhur. Namun, tanpa lompatan mutu sumber daya manusia, segala potensi alamiah itu tidak akan mampu mengangkat kesejahteraan rakyat secara mandiri dan berkelanjutan.</p>',
                'tags' => ['UNSULBAR', 'Opini'],
            ],
            [
                'title' => 'Proyek Strategis Nasional di Sulawesi Barat Terus Menunjukkan Progres Positif',
                'category_slug' => 'nasional',
                'is_headline' => false,
                'is_breaking' => false,
                'is_editor_pick' => true,
                'is_featured' => false,
                'thumbnail' => 'https://images.unsplash.com/photo-1541872703-74c5e44368f9?auto=format&fit=crop&w=800&q=80',
                'caption' => 'Pembangunan fasilitas pelabuhan dan bendungan pengairan di wilayah Sulbar.',
                'views_count' => 402,
                'excerpt' => 'Kementerian PUPR bersama pemda mengawasi langsung percepatan pembangunan jaringan irigasi dan pengaman pantai penahan abrasi di sepanjang Selat Makassar.',
                'content' => '<p><strong>MAMUJU</strong> — Dukungan pemerintah pusat terhadap percepatan pembangunan di Sulawesi Barat kian nyata lewat realisasi sejumlah Proyek Strategis Nasional (PSN). Proyek bendungan irigasi skala besar di Mamuju Tengah kini siap mengairi ribuan hektare sawah petani.</p>',
                'tags' => ['Pemprov Sulbar', 'Infrastruktur'],
            ],
            // Additional popular items matching mockup sidebar
            [
                'title' => 'Jembatan Pulau Karampuang Jadi Ikon Baru Mamuju yang Memukau',
                'category_slug' => 'pariwisata',
                'is_headline' => false,
                'is_breaking' => false,
                'is_editor_pick' => false,
                'is_featured' => false,
                'thumbnail' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80',
                'caption' => 'Panorama dermaga pelangi dan terumbu karang bawah laut Pulau Karampuang.',
                'views_count' => 12400,
                'excerpt' => 'Pulau Karampuang yang terletak tepat di hadapan teluk Mamuju kian memikat hati para pelancong bahari dengan keindahan alam bawah airnya.',
                'content' => '<p><strong>MAMUJU</strong> — Pulau Karampuang yang berjarak hanya 15 menit penyeberangan perahu motor dari dermaga Kasiwa Mamuju kini tampil lebih menawan dengan dermaga kayu panjang bercat warna-warni dan fasilitas selam snorkel yang dikelola kelompok sadar wisata setempat.</p>',
                'tags' => ['Mamuju', 'Pariwisata Sulbar'],
            ],
            [
                'title' => 'Festival Literasi Polewali Mandar Resmi Dibuka Ribuan Pengunjung',
                'category_slug' => 'pendidikan',
                'is_headline' => false,
                'is_breaking' => true,
                'is_editor_pick' => false,
                'is_featured' => false,
                'thumbnail' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=800&q=80',
                'caption' => 'Antusiasme anak muda dan komunitas penggiat pustaka di Festival Literasi Polman.',
                'views_count' => 8300,
                'excerpt' => 'Pentas seni budaya Mandar, pameran buku, dan bincang sastra maritim memeriahkan pagelaran literasi tahunan terbesar di Sulawesi Barat.',
                'content' => '<p><strong>POLEWALI MANDAR</strong> — Komunitas penggiat literasi perahu pustaka bersama pegiat seni budaya di Polewali Mandar sukses menyelenggarakan Festival Literasi Banua Malaqbiq yang dibuka meriah oleh ribuan pelajar dan masyarakat umum.</p>',
                'tags' => ['Polewali Mandar', 'Pendidikan Sulbar', 'Festival Budaya'],
            ],
            [
                'title' => 'BMKG: Waspada Potensi Hujan Lebat di Sejumlah Wilayah Pesisir Sulbar',
                'category_slug' => 'sulbar',
                'is_headline' => false,
                'is_breaking' => true,
                'is_editor_pick' => false,
                'is_featured' => false,
                'thumbnail' => 'https://images.unsplash.com/photo-1534088568595-a066f410bcda?auto=format&fit=crop&w=800&q=80',
                'caption' => 'Awan konvektif tebal berpotensi menimbulkan hujan lebat disertai angin di perairan Selat Makassar.',
                'views_count' => 7900,
                'excerpt' => 'Badan Meteorologi Klimatologi dan Geofisika Stasiun Meteorologi Tampa Padang Mamuju mengeluarkan peringatan dini cuaca ekstrem untuk 3 hari ke depan.',
                'content' => '<p><strong>MAMUJU</strong> — BMKG mengingatkan para nelayan dan operator kapal penyeberangan di pesisir barat Sulawesi untuk mewaspadai potensi gelombang tinggi dan angin kencang di perairan Selat Makassar.</p>',
                'tags' => ['BMKG', 'Mamuju', 'Majene'],
            ],
            [
                'title' => 'UNSULBAR Dorong Inovasi Digital Mahasiswa Berbasis Potensi Lokal',
                'category_slug' => 'pendidikan',
                'is_headline' => false,
                'is_breaking' => false,
                'is_editor_pick' => false,
                'is_featured' => false,
                'thumbnail' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=800&q=80',
                'caption' => 'Mahasiswa Universitas Sulawesi Barat mempresentasikan karya teknologi digital tepat guna.',
                'views_count' => 6600,
                'excerpt' => 'Fakultas Teknik Universitas Sulawesi Barat menggelar kompetisi inovasi teknologi ramah lingkungan guna menjawab tantangan ketahanan pangan lokal.',
                'content' => '<p><strong>MAJENE</strong> — Rektorat Universitas Sulawesi Barat (UNSULBAR) di Majene terus memberikan dukungan penuh terhadap riset terapan mahasiswa, khususnya inovasi Internet of Things (IoT) pada sektor tambak ikan bandeng dan perkebunan kakao rakyat.</p>',
                'tags' => ['UNSULBAR', 'Majene', 'Digitalisasi'],
            ],
        ];

        foreach ($articlesData as $artData) {
            $cat = $categories[$artData['category_slug']] ?? $categories['sulbar'];
            $article = Article::updateOrCreate(
                ['slug' => Str::slug($artData['title'])],
                [
                    'title' => $artData['title'],
                    'excerpt' => $artData['excerpt'],
                    'content' => $artData['content'],
                    'category_id' => $cat->id,
                    'author_id' => $reporter1->id,
                    'editor_id' => $editor->id,
                    'thumbnail' => $artData['thumbnail'],
                    'thumbnail_caption' => $artData['caption'],
                    'status' => 'published',
                    'is_headline' => $artData['is_headline'],
                    'is_breaking' => $artData['is_breaking'],
                    'is_editor_pick' => $artData['is_editor_pick'],
                    'is_featured' => $artData['is_featured'],
                    'allow_comments' => true,
                    'published_at' => now()->subHours(rand(1, 48)),
                    'views_count' => $artData['views_count'],
                    'unique_views_count' => intval($artData['views_count'] * 0.8),
                    'meta_title' => $artData['title'].' | KATA SULBAR',
                    'meta_description' => $artData['excerpt'],
                    'meta_keywords' => implode(', ', $artData['tags']),
                ]
            );

            // Sync tags
            $tagIds = [];
            foreach ($artData['tags'] as $tagName) {
                if (isset($tags[$tagName])) {
                    $tagIds[] = $tags[$tagName]->id;
                }
            }
            $article->tags()->sync($tagIds);

            // Seed sample comments for popular articles
            if ($artData['views_count'] > 500) {
                Comment::updateOrCreate(
                    ['article_id' => $article->id, 'name' => 'Baharuddin Mandar'],
                    [
                        'email' => 'bahar@gmail.com',
                        'content' => 'Langkah yang sangat baik untuk kemajuan Sulawesi Barat. Semoga pemerataan pembangunan infrastruktur sampai ke pelosok desa.',
                        'status' => 'approved',
                        'ip_address' => '127.0.0.1',
                    ]
                );
                Comment::updateOrCreate(
                    ['article_id' => $article->id, 'name' => 'Siti Marwah Mamuju'],
                    [
                        'email' => 'siti.marwah@yahoo.com',
                        'content' => 'Terima kasih atas liputannya yang komprehensif dari redaksi Kata Sulbar. Informasi ini sangat bermanfaat bagi warga.',
                        'status' => 'approved',
                        'ip_address' => '127.0.0.1',
                    ]
                );
            }
        }

        // 8. Videos & Shorts (Sections 10, 31, 32, 34, 38)
        $videosData = [
            // Featured video
            [
                'title' => 'Keindahan Jembatan Pulau Karampuang dari Udara',
                'description' => 'Eksplorasi panorama memukau laut jernih dan terumbu karang Pulau Karampuang Mamuju menggunakan drone 4K.',
                'youtube_url' => 'https://www.youtube.com/watch?v=ScMzIvxBSi4',
                'youtube_id' => 'ScMzIvxBSi4',
                'thumbnail' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80',
                'video_type' => 'video',
                'duration' => '02:35',
                'views' => 3420,
                'is_featured' => true,
                'is_short' => false,
                'show_homepage' => true,
                'category' => 'Pariwisata',
            ],
            [
                'title' => 'Festival Literasi Polewali Mandar Resmi Dibuka',
                'description' => 'Kemeriahan pembukaan pawai budaya dan parade perahu pustaka menyemarakkan pembukaan Festival Literasi Polman.',
                'youtube_url' => 'https://www.youtube.com/watch?v=kJQP7kiw5Fk',
                'youtube_id' => 'kJQP7kiw5Fk',
                'thumbnail' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=800&q=80',
                'video_type' => 'video',
                'duration' => '01:48',
                'views' => 2100,
                'is_featured' => false,
                'is_short' => false,
                'show_homepage' => true,
                'category' => 'Pendidikan',
            ],
            [
                'title' => 'Pesona Alam Sulawesi Barat dari Ketinggian Dataran Mamasa',
                'description' => 'Lanskap perbukitan hijau, persawahan terasering, dan rumah adat Banua Sibatang di Mamasa.',
                'youtube_url' => 'https://www.youtube.com/watch?v=3JZ_D3ELwOQ',
                'youtube_id' => '3JZ_D3ELwOQ',
                'thumbnail' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=800&q=80',
                'video_type' => 'video',
                'duration' => '03:12',
                'views' => 1850,
                'is_featured' => false,
                'is_short' => false,
                'show_homepage' => true,
                'category' => 'Pariwisata',
            ],
            // Shorts (9:16) for Section 32
            [
                'title' => 'Suasana Festival Literasi Polewali Mandar',
                'description' => 'Momen kebersamaan ratusan anak-anak membaca buku di tepi pantai Polman.',
                'youtube_url' => 'https://youtube.com/shorts/5qap5aO4i9A',
                'youtube_id' => '5qap5aO4i9A',
                'thumbnail' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=600&h=1067&q=80',
                'video_type' => 'shorts',
                'duration' => '00:45',
                'views' => 5400,
                'is_featured' => false,
                'is_short' => true,
                'show_homepage' => true,
                'category' => 'Video Singkat',
            ],
            [
                'title' => 'Pesona Pantai Dato Majene dari Tebing Karang',
                'description' => 'Spot tangga ikonik dan laut biru toska Majene yang super estetik.',
                'youtube_url' => 'https://youtube.com/shorts/kJQP7kiw5Fk',
                'youtube_id' => 'kJQP7kiw5Fk',
                'thumbnail' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&h=1067&q=80',
                'video_type' => 'shorts',
                'duration' => '00:30',
                'views' => 4200,
                'is_featured' => false,
                'is_short' => true,
                'show_homepage' => true,
                'category' => 'Video Singkat',
            ],
            [
                'title' => 'Kondisi Jalan Trans Sulawesi Hari Ini di Pesisir Majene',
                'description' => 'Laporan visual kondisi arus lalu lintas lancar di jalur pesisir Selat Makassar.',
                'youtube_url' => 'https://youtube.com/shorts/ScMzIvxBSi4',
                'youtube_id' => 'ScMzIvxBSi4',
                'thumbnail' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=600&h=1067&q=80',
                'video_type' => 'shorts',
                'duration' => '00:55',
                'views' => 3800,
                'is_featured' => false,
                'is_short' => true,
                'show_homepage' => true,
                'category' => 'Video Singkat',
            ],
            [
                'title' => 'Kuliner Khas Mandar: Bau Peapi dan Jepa Hangat',
                'description' => 'Menikmati sajian ikan cakalang asam pedas gurih dengan lezatnya jepa sagu.',
                'youtube_url' => 'https://youtube.com/shorts/3JZ_D3ELwOQ',
                'youtube_id' => '3JZ_D3ELwOQ',
                'thumbnail' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&h=1067&q=80',
                'video_type' => 'shorts',
                'duration' => '00:40',
                'views' => 6100,
                'is_featured' => false,
                'is_short' => true,
                'show_homepage' => true,
                'category' => 'Video Singkat',
            ],
            [
                'title' => 'Senja Indah di Anjungan Pantai Manakarra Mamuju',
                'description' => 'Kilau keemasan matahari tenggelam di depan pulau Karampuang.',
                'youtube_url' => 'https://youtube.com/shorts/5qap5aO4i9A',
                'youtube_id' => '5qap5aO4i9A',
                'thumbnail' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&h=1067&q=80',
                'video_type' => 'shorts',
                'duration' => '00:35',
                'views' => 7300,
                'is_featured' => false,
                'is_short' => true,
                'show_homepage' => true,
                'category' => 'Video Singkat',
            ],
        ];

        foreach ($videosData as $v) {
            $vcat = $videoCats[$v['category']] ?? $videoCats['Berita Sulbar'];
            Video::updateOrCreate(
                ['slug' => Str::slug($v['title'])],
                [
                    'title' => $v['title'],
                    'description' => $v['description'],
                    'youtube_url' => $v['youtube_url'],
                    'youtube_id' => $v['youtube_id'],
                    'thumbnail' => $v['thumbnail'],
                    'video_type' => $v['video_type'],
                    'category_id' => $vcat->id,
                    'author_id' => $superAdmin->id,
                    'duration' => $v['duration'],
                    'views' => $v['views'],
                    'is_featured' => $v['is_featured'],
                    'is_short' => $v['is_short'],
                    'show_homepage' => $v['show_homepage'],
                    'status' => 'published',
                    'published_at' => now()->subHours(rand(2, 72)),
                    'meta_title' => $v['title'].' | Video Kata Sulbar',
                    'meta_description' => $v['description'],
                ]
            );
        }

        // 9. Advertisements
        Advertisement::updateOrCreate(
            ['title' => 'Space Banner Iklan 728 x 90'],
            [
                'position' => 'horizontal_top',
                'ad_type' => 'image',
                'image_url' => null,
                'target_url' => '#kontak-iklan',
                'is_active' => true,
                'impressions_count' => 1420,
                'clicks_count' => 58,
            ]
        );

        Advertisement::updateOrCreate(
            ['title' => 'Space Iklan Sidebar 300 x 600'],
            [
                'position' => 'sidebar_right',
                'ad_type' => 'image',
                'image_url' => null,
                'target_url' => '#kontak-iklan',
                'is_active' => true,
                'impressions_count' => 2840,
                'clicks_count' => 112,
            ]
        );

        Advertisement::updateOrCreate(
            ['title' => 'Banner Iklan Bawah 970 x 100'],
            [
                'position' => 'home_bottom',
                'ad_type' => 'image',
                'image_url' => null,
                'target_url' => '#kontak-iklan',
                'is_active' => true,
                'impressions_count' => 980,
                'clicks_count' => 45,
            ]
        );

        Advertisement::updateOrCreate(
            ['title' => 'Iklan Dalam Artikel 728 x 90'],
            [
                'position' => 'in_article',
                'ad_type' => 'image',
                'image_url' => null,
                'target_url' => '#kontak-iklan',
                'is_active' => true,
                'paragraph_position' => 3,
                'impressions_count' => 3120,
                'clicks_count' => 84,
            ]
        );

        // 10. Static Pages
        $pages = [
            [
                'title' => 'Tentang Kami',
                'slug' => 'tentang-kami',
                'content' => '<h2>Tentang KATA SULBAR</h2><p><strong>KATA SULBAR</strong> dengan tagline <em>“Suara Sulbar, Untuk Semua”</em> adalah media siber profesional yang didirikan dengan semangat keterbukaan, independensi, dan keberpihakan pada kepentingan publik di Sulawesi Barat.</p><p>Kami berdedikasi mengabarkan setiap denyut dinamika masyarakat di 6 kabupaten: Mamuju, Majene, Polewali Mandar, Mamasa, Pasangkayu, dan Mamuju Tengah, menjembatani suara warga dan pemangku kebijakan dengan standar jurnalisme bermutu tinggi.</p>',
            ],
            [
                'title' => 'Struktur Redaksi',
                'slug' => 'redaksi',
                'content' => '<h2>Susunan Redaksi KATA SULBAR</h2><ul><li><strong>Pemimpin Umum:</strong> Andi Syarifuddin</li><li><strong>Pemimpin Redaksi / Penanggung Jawab:</strong> Andi Syarifuddin</li><li><strong>Sekretaris Redaksi:</strong> Nur Azizah, S.I.Kom</li><li><strong>Redaktur Pelaksana:</strong> Faisal Rasyid</li><li><strong>Tim Jurnalis & Koresponden:</strong> Wahyu Pratama (Polman), Hendra (Majene), Rian (Mamasa), Dewi Lestari (Pasangkayu), Ilham (Mateng)</li><li><strong>Tim Multimedia & TI:</strong> Kata Media Digital</li></ul>',
            ],
            [
                'title' => 'Pedoman Media Siber',
                'slug' => 'pedoman-media-siber',
                'content' => '<h2>Pedoman Pemberitaan Media Siber</h2><p>KATA SULBAR berpegang teguh pada Kode Etik Jurnalistik dan Pedoman Pemberitaan Media Siber yang ditetapkan oleh Dewan Pers demi menjamin akurasi, keberimbangan, dan perlindungan hak privasi narasumber.</p>',
            ],
            [
                'title' => 'Kontak & Kerja Sama Iklan',
                'slug' => 'kontak',
                'content' => '<h2>Hubungi Kami</h2><p>Alamat Kantor Redaksi: Jl. Jenderal Sudirman No. 45, Mamuju, Sulawesi Barat 91511<br>Telepon / WhatsApp: +62 811-4200-8899<br>Email Redaksi: redaksi@halosulbar.com<br>Email Kerja Sama / Iklan: iklan@halosulbar.com</p>',
            ],
            [
                'title' => 'Kebijakan Privasi',
                'slug' => 'kebijakan-privasi',
                'content' => '<h2>Kebijakan Privasi</h2><p>Kami menghormati kerahasiaan data pembaca dan pengguna situs KATA SULBAR. Setiap data yang diperoleh melalui newsletter atau komentar dijaga dengan standar keamanan terbaik dan tidak akan diperjualbelikan kepada pihak ketiga.</p>',
            ],
            [
                'title' => 'Syarat dan Ketentuan',
                'slug' => 'syarat-ketentuan',
                'content' => '<h2>Syarat dan Ketentuan Layanan</h2><p>Seluruh konten berita, foto, grafis, dan video pada situs KATA SULBAR dilindungi undang-undang hak cipta. Pengutipan materi berita diperkenankan dengan mencantumkan tautan aktif ke sumber berita asli.</p>',
            ],
        ];

        foreach ($pages as $p) {
            Page::updateOrCreate(
                ['slug' => $p['slug']],
                [
                    'title' => $p['title'],
                    'content' => $p['content'],
                    'is_active' => true,
                    'meta_title' => $p['title'].' | KATA SULBAR',
                    'meta_description' => strip_tags(substr($p['content'], 0, 150)),
                ]
            );
        }
    }
}
