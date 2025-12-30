<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $partners = [
            [
                'name' => 'Indo Bismar',
                'logo' => '/partners/indo-bismar.png',
                'website' => 'https://indobismar.com',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => 'JD Global',
                'logo' => '/partners/jd-global.png',
                'website' => 'https://jdglobal.co.id',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'Jindal Stainless',
                'logo' => '/partners/jindal-stainless.png',
                'website' => 'https://jindalstainless.com',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'name' => 'Indopipe',
                'logo' => '/partners/indopipe.png',
                'website' => 'https://indopipe.co.id',
                'is_active' => true,
                'order' => 4,
            ],
            [
                'name' => 'Swadaya Graha',
                'logo' => '/partners/swadaya-graha.png',
                'website' => 'https://swadayagraha.co.id',
                'is_active' => true,
                'order' => 5,
            ],
            [
                'name' => 'Mitra Partner 1',
                'logo' => '/partners/1631333940710 1.png',
                'website' => null,
                'is_active' => true,
                'order' => 6,
            ],
            [
                'name' => 'Mitra Partner 2',
                'logo' => '/partners/1659427442484 1.png',
                'website' => null,
                'is_active' => true,
                'order' => 7,
            ],
            [
                'name' => 'Mitra Partner 3',
                'logo' => '/partners/Group 278.png',
                'website' => null,
                'is_active' => true,
                'order' => 8,
            ],
            [
                'name' => 'Mitra Partner 4',
                'logo' => '/partners/Group 279.png',
                'website' => null,
                'is_active' => true,
                'order' => 9,
            ],
            [
                'name' => 'Mitra Partner 5',
                'logo' => '/partners/Group 281.png',
                'website' => null,
                'is_active' => true,
                'order' => 10,
            ],
            [
                'name' => 'Mitra Partner 6',
                'logo' => '/partners/Group 282.png',
                'website' => null,
                'is_active' => true,
                'order' => 11,
            ],
            [
                'name' => 'Mitra Partner 7',
                'logo' => '/partners/Group 283.png',
                'website' => null,
                'is_active' => true,
                'order' => 12,
            ],
            [
                'name' => 'Mitra Partner 8',
                'logo' => '/partners/Group 284.png',
                'website' => null,
                'is_active' => true,
                'order' => 13,
            ],
            [
                'name' => 'Mitra Partner 9',
                'logo' => '/partners/Group 285.png',
                'website' => null,
                'is_active' => true,
                'order' => 14,
            ],
            [
                'name' => 'Mitra Partner 10',
                'logo' => '/partners/Group 286.png',
                'website' => null,
                'is_active' => true,
                'order' => 15,
            ],
            [
                'name' => 'Mitra Partner 11',
                'logo' => '/partners/Group 287.png',
                'website' => null,
                'is_active' => true,
                'order' => 16,
            ],
            [
                'name' => 'Mitra Partner 12',
                'logo' => '/partners/Group 289.png',
                'website' => null,
                'is_active' => true,
                'order' => 17,
            ],
            [
                'name' => 'Mitra Partner 13',
                'logo' => '/partners/Group 290.png',
                'website' => null,
                'is_active' => true,
                'order' => 18,
            ],
            [
                'name' => 'Mitra Partner 14',
                'logo' => '/partners/Group 291.png',
                'website' => null,
                'is_active' => true,
                'order' => 19,
            ],
        ];

        foreach ($partners as $partner) {
            Partner::updateOrCreate(
                ['name' => $partner['name']],
                $partner
            );
        }
    }
}
