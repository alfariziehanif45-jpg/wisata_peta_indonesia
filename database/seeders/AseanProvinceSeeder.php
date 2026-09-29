<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Province;
use Illuminate\Support\Str;

class AseanProvinceSeeder extends Seeder
{
    public function run(): void
    {
        $data = [

            // BRUNEI
            [
                'country' => 'BN',
                'type' => 'District',
                'items' => [
                    'Belait',
                    'Brunei-Muara',
                    'Temburong',
                    'Tutong',
                ],
            ],

            // CAMBODIA
            [
                'country' => 'KH',
                'type' => 'Province',
                'items' => [
                    'Banteay Meanchey',
                    'Battambang',
                    'Kampong Cham',
                    'Kampong Chhnang',
                    'Kampong Speu',
                    'Kampong Thom',
                    'Kampot',
                    'Kandal',
                    'Kep',
                    'Koh Kong',
                    'Kratie',
                    'Mondulkiri',
                    'Oddar Meanchey',
                    'Pailin',
                    'Phnom Penh',
                    'Preah Sihanouk',
                    'Preah Vihear',
                    'Pursat',
                    'Ratanakiri',
                    'Siem Reap',
                    'Stung Treng',
                    'Svay Rieng',
                    'Takeo',
                    'Tbong Khmum',
                    'Pursat',
                ],
            ],

            // LAO PDR
            [
                'country' => 'LA',
                'type' => 'Province',
                'items' => [
                    'Attapeu',
                    'Bokeo',
                    'Bolikhamsai',
                    'Champasak',
                    'Houaphanh',
                    'Khammouane',
                    'Luang Namtha',
                    'Luang Prabang',
                    'Oudomxay',
                    'Phongsaly',
                    'Salavan',
                    'Savannakhet',
                    'Sekong',
                    'Vientiane Province',
                    'Vientiane Capital',
                    'Xaignabouli',
                    'Xaisomboun',
                    'Xiangkhouang',
                ],
            ],

            // MALAYSIA
            [
                'country' => 'MY',
                'type' => 'State / Federal Territory',
                'items' => [
                    'Johor',
                    'Kedah',
                    'Kelantan',
                    'Melaka',
                    'Negeri Sembilan',
                    'Pahang',
                    'Perak',
                    'Perlis',
                    'Penang',
                    'Sabah',
                    'Sarawak',
                    'Selangor',
                    'Terengganu',
                    'Kuala Lumpur',
                    'Labuan',
                    'Putrajaya',
                ],
            ],

            // MYANMAR
            [
                'country' => 'MM',
                'type' => 'State / Region',
                'items' => [
                    'Ayeyarwady Region',
                    'Bago Region',
                    'Chin State',
                    'Kachin State',
                    'Kayah State',
                    'Kayin State',
                    'Magway Region',
                    'Mandalay Region',
                    'Mon State',
                    'Rakhine State',
                    'Sagaing Region',
                    'Shan State',
                    'Tanintharyi Region',
                    'Yangon Region',
                    'Naypyidaw Union Territory',
                ],
            ],

            // PHILIPPINES
            [
                'country' => 'PH',
                'type' => 'Region',
                'items' => [
                    'National Capital Region',
                    'Cordillera Administrative Region',
                    'Ilocos Region',
                    'Cagayan Valley',
                    'Central Luzon',
                    'CALABARZON',
                    'MIMAROPA',
                    'Bicol Region',
                    'Western Visayas',
                    'Central Visayas',
                    'Eastern Visayas',
                    'Zamboanga Peninsula',
                    'Northern Mindanao',
                    'Davao Region',
                    'SOCCSKSARGEN',
                    'Caraga',
                    'Bangsamoro Autonomous Region in Muslim Mindanao',
                ],
            ],

            // SINGAPORE
            [
                'country' => 'SG',
                'type' => 'Region',
                'items' => [
                    'Central Region',
                    'East Region',
                    'North Region',
                    'North-East Region',
                    'West Region',
                ],
            ],

            // THAILAND
            [
                'country' => 'TH',
                'type' => 'Province',
                'items' => [
                    'Bangkok',
                    'Amnat Charoen',
                    'Ang Thong',
                    'Bueng Kan',
                    'Buriram',
                    'Chachoengsao',
                    'Chai Nat',
                    'Chaiyaphum',
                    'Chanthaburi',
                    'Chiang Mai',
                    'Chiang Rai',
                    'Chon Buri',
                    'Chumphon',
                    'Kalasin',
                    'Kamphaeng Phet',
                    'Kanchanaburi',
                    'Khon Kaen',
                    'Krabi',
                    'Lampang',
                    'Lamphun',
                    'Loei',
                    'Lopburi',
                    'Mae Hong Son',
                    'Maha Sarakham',
                    'Mukdahan',
                    'Nakhon Nayok',
                    'Nakhon Pathom',
                    'Nakhon Phanom',
                    'Nakhon Ratchasima',
                    'Nakhon Sawan',
                    'Nakhon Si Thammarat',
                    'Nan',
                    'Narathiwat',
                    'Nong Bua Lamphu',
                    'Nong Khai',
                    'Nonthaburi',
                    'Pathum Thani',
                    'Pattani',
                    'Phang Nga',
                    'Phatthalung',
                    'Phayao',
                    'Phetchabun',
                    'Phetchaburi',
                    'Phichit',
                    'Phitsanulok',
                    'Phra Nakhon Si Ayutthaya',
                    'Phrae',
                    'Phuket',
                    'Prachin Buri',
                    'Prachuap Khiri Khan',
                    'Ranong',
                    'Ratchaburi',
                    'Rayong',
                    'Roi Et',
                    'Sa Kaeo',
                    'Sakon Nakhon',
                    'Samut Prakan',
                    'Samut Sakhon',
                    'Samut Songkhram',
                    'Saraburi',
                    'Satun',
                    'Sing Buri',
                    'Sisaket',
                    'Songkhla',
                    'Sukhothai',
                    'Suphan Buri',
                    'Surat Thani',
                    'Surin',
                    'Tak',
                    'Trang',
                    'Trat',
                    'Ubon Ratchathani',
                    'Uthai Thani',
                    'Uttaradit',
                    'Yala',
                    'Yasothon',
                ],
            ],

            // TIMOR-LESTE
            [
                'country' => 'TL',
                'type' => 'Municipality',
                'items' => [
                    'Aileu',
                    'Ainaro',
                    'Baucau',
                    'Bobonaro',
                    'Cova Lima',
                    'Dili',
                    'Ermera',
                    'Lautem',
                    'Liquica',
                    'Manatuto',
                    'Manufahi',
                    'Oecusse',
                    'Viqueque',
                ],
            ],

            // VIET NAM
            [
                'country' => 'VN',
                'type' => 'Province / Municipality',
                'items' => [
                    'An Giang',
                    'Bac Ninh',
                    'Ca Mau',
                    'Can Tho',
                    'Cao Bang',
                    'Da Nang',
                    'Dak Lak',
                    'Dien Bien',
                    'Dong Nai',
                    'Dong Thap',
                    'Gia Lai',
                    'Ha Tinh',
                    'Hai Phong',
                    'Hanoi',
                    'Ho Chi Minh City',
                    'Hue',
                    'Hung Yen',
                    'Khanh Hoa',
                    'Lai Chau',
                    'Lam Dong',
                    'Lang Son',
                    'Lao Cai',
                    'Nghe An',
                    'Ninh Binh',
                    'Phu Tho',
                    'Quang Ngai',
                    'Quang Ninh',
                    'Quang Tri',
                    'Son La',
                    'Tay Ninh',
                    'Thai Nguyen',
                    'Thanh Hoa',
                    'Tuyen Quang',
                    'Vinh Long',
                    'Yen Bai',
                ],
            ],
        ];

        foreach ($data as $countryData) {

            $country = Country::where(
                'code',
                $countryData['country']
            )->first();

            if (!$country) {
                continue;
            }

            foreach ($countryData['items'] as $name) {

                Province::updateOrCreate(
                    [
                        'country_id' => $country->id,
                        'name' => $name,
                    ],
                    [
                        'type' => $countryData['type'],
                        'slug' => Str::slug($name),
                    ]
                );
            }
        }
    }
}