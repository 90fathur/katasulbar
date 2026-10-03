<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class NavigationMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MenuItem::query()->delete();

        $categories = Category::all()->keyBy('slug');

        // 1. Beranda
        MenuItem::create([
            'title' => 'Beranda',
            'url' => '/',
            'type' => 'custom',
            'order' => 1,
            'is_active' => true,
        ]);

        // 2. Sulbar (with dropdown for 6 districts/regions)
        $sulbarCat = $categories->get('sulbar');
        $sulbarMenu = MenuItem::create([
            'title' => 'Sulbar',
            'url' => $sulbarCat ? route('category.show', $sulbarCat->slug, false) : '/kategori/sulbar',
            'type' => $sulbarCat ? 'category' : 'custom',
            'category_id' => $sulbarCat?->id,
            'order' => 2,
            'is_active' => true,
        ]);

        // Dropdown children for Sulbar
        $districts = [
            'Mamuju' => '/kategori/sulbar?daerah=mamuju',
            'Majene' => '/kategori/sulbar?daerah=majene',
            'Polewali Mandar' => '/kategori/sulbar?daerah=polman',
            'Mamasa' => '/kategori/sulbar?daerah=mamasa',
            'Pasangkayu' => '/kategori/sulbar?daerah=pasangkayu',
            'Mamuju Tengah' => '/kategori/sulbar?daerah=mateng',
        ];

        $dOrder = 1;
        foreach ($districts as $dName => $dUrl) {
            MenuItem::create([
                'title' => $dName,
                'url' => $dUrl,
                'type' => 'custom',
                'parent_id' => $sulbarMenu->id,
                'order' => $dOrder++,
                'is_active' => true,
            ]);
        }

        // 3. Nasional
        $nasionalCat = $categories->get('nasional');
        MenuItem::create([
            'title' => 'Nasional',
            'url' => $nasionalCat ? route('category.show', $nasionalCat->slug, false) : '/kategori/nasional',
            'type' => $nasionalCat ? 'category' : 'custom',
            'category_id' => $nasionalCat?->id,
            'order' => 3,
            'is_active' => true,
        ]);

        // 4. Politik
        $politikCat = $categories->get('politik');
        MenuItem::create([
            'title' => 'Politik',
            'url' => $politikCat ? route('category.show', $politikCat->slug, false) : '/kategori/politik',
            'type' => $politikCat ? 'category' : 'custom',
            'category_id' => $politikCat?->id,
            'order' => 4,
            'is_active' => true,
        ]);

        // 5. Ekonomi
        $ekonomiCat = $categories->get('ekonomi');
        MenuItem::create([
            'title' => 'Ekonomi',
            'url' => $ekonomiCat ? route('category.show', $ekonomiCat->slug, false) : '/kategori/ekonomi',
            'type' => $ekonomiCat ? 'category' : 'custom',
            'category_id' => $ekonomiCat?->id,
            'order' => 5,
            'is_active' => true,
        ]);

        // 6. Hukum
        $hukumCat = $categories->get('hukum');
        MenuItem::create([
            'title' => 'Hukum',
            'url' => $hukumCat ? route('category.show', $hukumCat->slug, false) : '/kategori/hukum',
            'type' => $hukumCat ? 'category' : 'custom',
            'category_id' => $hukumCat?->id,
            'order' => 6,
            'is_active' => true,
        ]);

        // 7. Pendidikan
        $pendidikanCat = $categories->get('pendidikan');
        MenuItem::create([
            'title' => 'Pendidikan',
            'url' => $pendidikanCat ? route('category.show', $pendidikanCat->slug, false) : '/kategori/pendidikan',
            'type' => $pendidikanCat ? 'category' : 'custom',
            'category_id' => $pendidikanCat?->id,
            'order' => 7,
            'is_active' => true,
        ]);

        // 8. Pariwisata
        $pariwisataCat = $categories->get('pariwisata');
        MenuItem::create([
            'title' => 'Pariwisata',
            'url' => $pariwisataCat ? route('category.show', $pariwisataCat->slug, false) : '/kategori/pariwisata',
            'type' => $pariwisataCat ? 'category' : 'custom',
            'category_id' => $pariwisataCat?->id,
            'order' => 8,
            'is_active' => true,
        ]);

        // 9. Video & Shorts
        MenuItem::create([
            'title' => 'Video',
            'url' => '/video',
            'type' => 'custom',
            'badge' => 'NEW',
            'badge_color' => '#EF1C25',
            'order' => 9,
            'is_active' => true,
        ]);

        // 10. Rubrik Lainnya (Dropdown)
        $moreMenu = MenuItem::create([
            'title' => 'Rubrik Lain',
            'url' => '#',
            'type' => 'custom',
            'order' => 10,
            'is_active' => true,
        ]);

        $subRubrik = [
            'Kesehatan' => ['slug' => 'kesehatan', 'url' => '/kategori/kesehatan'],
            'Teknologi' => ['slug' => 'teknologi', 'url' => '/kategori/teknologi'],
            'Olahraga' => ['slug' => 'olahraga', 'url' => '/kategori/olahraga'],
            'Opini' => ['slug' => 'opini', 'url' => '/kategori/opini'],
            'Galeri Foto' => ['slug' => null, 'url' => '/media'],
            'Tentang Kami' => ['slug' => null, 'url' => '/tentang-kami'],
            'Redaksi' => ['slug' => null, 'url' => '/redaksi'],
            'Pedoman Media Siber' => ['slug' => null, 'url' => '/pedoman-media-siber'],
            'Kontak & Iklan' => ['slug' => null, 'url' => '/kontak'],
        ];

        $mOrder = 1;
        foreach ($subRubrik as $sTitle => $sData) {
            $cat = ! empty($sData['slug']) ? $categories->get($sData['slug']) : null;
            MenuItem::create([
                'title' => $sTitle,
                'url' => $cat ? route('category.show', $cat->slug, false) : $sData['url'],
                'type' => $cat ? 'category' : 'custom',
                'category_id' => $cat?->id,
                'parent_id' => $moreMenu->id,
                'order' => $mOrder++,
                'is_active' => true,
            ]);
        }
    }
}
