<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Province;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $data = [

            // =====================================================
            // ACEH
            // =====================================================

            'Aceh' => [

                'Simeulue',
                'Aceh Singkil',
                'Aceh Selatan',
                'Aceh Tenggara',
                'Aceh Timur',
                'Aceh Tengah',
                'Aceh Barat',
                'Aceh Besar',
                'Pidie',
                'Bireuen',
                'Aceh Utara',
                'Aceh Barat Daya',
                'Gayo Lues',
                'Aceh Tamiang',
                'Nagan Raya',
                'Aceh Jaya',
                'Bener Meriah',
                'Pidie Jaya',

                'Banda Aceh',
                'Sabang',
                'Langsa',
                'Lhokseumawe',
                'Subulussalam',
            ],


            // =====================================================
            // SUMATERA UTARA
            // =====================================================

            'Sumatera Utara' => [

                'Nias',
                'Mandailing Natal',
                'Tapanuli Selatan',
                'Tapanuli Tengah',
                'Tapanuli Utara',
                'Toba',
                'Labuhanbatu',
                'Asahan',
                'Simalungun',
                'Dairi',
                'Karo',
                'Deli Serdang',
                'Langkat',
                'Nias Selatan',
                'Humbang Hasundutan',
                'Pakpak Bharat',
                'Samosir',
                'Serdang Bedagai',
                'Batu Bara',
                'Padang Lawas Utara',
                'Padang Lawas',
                'Labuhanbatu Selatan',
                'Labuhanbatu Utara',
                'Nias Utara',
                'Nias Barat',

                'Sibolga',
                'Tanjungbalai',
                'Pematangsiantar',
                'Tebing Tinggi',
                'Medan',
                'Binjai',
                'Padangsidimpuan',
                'Gunungsitoli',
            ],


            // =====================================================
            // SUMATERA BARAT
            // =====================================================

            'Sumatera Barat' => [

                'Kepulauan Mentawai',
                'Pesisir Selatan',
                'Solok',
                'Sijunjung',
                'Tanah Datar',
                'Padang Pariaman',
                'Agam',
                'Lima Puluh Kota',
                'Pasaman',
                'Solok Selatan',
                'Dharmasraya',
                'Pasaman Barat',

                'Padang',
                'Solok',
                'Sawahlunto',
                'Padang Panjang',
                'Bukittinggi',
                'Payakumbuh',
                'Pariaman',
            ],


            // =====================================================
            // RIAU
            // =====================================================

            'Riau' => [

                'Kuantan Singingi',
                'Indragiri Hulu',
                'Indragiri Hilir',
                'Pelalawan',
                'Siak',
                'Kampar',
                'Rokan Hulu',
                'Bengkalis',
                'Rokan Hilir',
                'Kepulauan Meranti',

                'Pekanbaru',
                'Dumai',
            ],


            // =====================================================
            // JAMBI
            // =====================================================

            'Jambi' => [

                'Kerinci',
                'Merangin',
                'Sarolangun',
                'Batang Hari',
                'Muaro Jambi',
                'Tanjung Jabung Timur',
                'Tanjung Jabung Barat',
                'Tebo',
                'Bungo',

                'Jambi',
                'Sungai Penuh',
            ],


            // =====================================================
            // SUMATERA SELATAN
            // =====================================================

            'Sumatera Selatan' => [

                'Ogan Komering Ulu',
                'Ogan Komering Ilir',
                'Muara Enim',
                'Lahat',
                'Musi Rawas',
                'Musi Banyuasin',
                'Banyuasin',
                'Ogan Komering Ulu Selatan',
                'Ogan Komering Ulu Timur',
                'Ogan Ilir',
                'Empat Lawang',
                'Penukal Abab Lematang Ilir',
                'Musi Rawas Utara',

                'Palembang',
                'Prabumulih',
                'Pagar Alam',
                'Lubuklinggau',
            ],


            // =====================================================
            // BENGKULU
            // =====================================================

            'Bengkulu' => [

                'Bengkulu Selatan',
                'Rejang Lebong',
                'Bengkulu Utara',
                'Kaur',
                'Seluma',
                'Mukomuko',
                'Lebong',
                'Kepahiang',
                'Bengkulu Tengah',

                'Bengkulu',
            ],


            // =====================================================
            // LAMPUNG
            // =====================================================

            'Lampung' => [

                'Lampung Barat',
                'Tanggamus',
                'Lampung Selatan',
                'Lampung Timur',
                'Lampung Tengah',
                'Lampung Utara',
                'Way Kanan',
                'Tulang Bawang',
                'Pesawaran',
                'Pringsewu',
                'Mesuji',
                'Tulang Bawang Barat',
                'Pesisir Barat',

                'Bandar Lampung',
                'Metro',
            ],


            // =====================================================
            // KEPULAUAN BANGKA BELITUNG
            // =====================================================

            'Kepulauan Bangka Belitung' => [

                'Bangka',
                'Belitung',
                'Bangka Barat',
                'Bangka Tengah',
                'Bangka Selatan',
                'Belitung Timur',

                'Pangkalpinang',
            ],


            // =====================================================
            // KEPULAUAN RIAU
            // =====================================================

            'Kepulauan Riau' => [

                'Karimun',
                'Bintan',
                'Natuna',
                'Lingga',
                'Kepulauan Anambas',

                'Batam',
                'Tanjungpinang',
            ],


            // =====================================================
            // DKI JAKARTA
            // =====================================================

            'DKI Jakarta' => [

                'Kepulauan Seribu',
                'Jakarta Selatan',
                'Jakarta Timur',
                'Jakarta Pusat',
                'Jakarta Barat',
                'Jakarta Utara',
            ],


            // =====================================================
            // JAWA BARAT
            // =====================================================

            'Jawa Barat' => [

                'Bogor',
                'Sukabumi',
                'Cianjur',
                'Bandung',
                'Garut',
                'Tasikmalaya',
                'Ciamis',
                'Kuningan',
                'Cirebon',
                'Majalengka',
                'Sumedang',
                'Indramayu',
                'Subang',
                'Purwakarta',
                'Karawang',
                'Bekasi',
                'Bandung Barat',
                'Pangandaran',

                'Bogor',
                'Sukabumi',
                'Bandung',
                'Cirebon',
                'Bekasi',
                'Depok',
                'Cimahi',
                'Tasikmalaya',
                'Banjar',
            ],


            // =====================================================
            // JAWA TENGAH
            // =====================================================

            'Jawa Tengah' => [

                'Cilacap',
                'Banyumas',
                'Purbalingga',
                'Banjarnegara',
                'Kebumen',
                'Purworejo',
                'Wonosobo',
                'Magelang',
                'Boyolali',
                'Klaten',
                'Sukoharjo',
                'Wonogiri',
                'Karanganyar',
                'Sragen',
                'Grobogan',
                'Blora',
                'Rembang',
                'Pati',
                'Kudus',
                'Jepara',
                'Demak',
                'Semarang',
                'Temanggung',
                'Kendal',
                'Batang',
                'Pekalongan',
                'Pemalang',
                'Tegal',
                'Brebes',

                'Magelang',
                'Surakarta',
                'Salatiga',
                'Semarang',
                'Pekalongan',
                'Tegal',
            ],


            // =====================================================
            // DI YOGYAKARTA
            // =====================================================

            'DI Yogyakarta' => [

                'Kulon Progo',
                'Bantul',
                'Gunungkidul',
                'Sleman',

                'Yogyakarta',
            ],


            // =====================================================
            // JAWA TIMUR
            // =====================================================

            'Jawa Timur' => [

                'Pacitan',
                'Ponorogo',
                'Trenggalek',
                'Tulungagung',
                'Blitar',
                'Kediri',
                'Malang',
                'Lumajang',
                'Jember',
                'Banyuwangi',
                'Bondowoso',
                'Situbondo',
                'Probolinggo',
                'Pasuruan',
                'Sidoarjo',
                'Mojokerto',
                'Jombang',
                'Nganjuk',
                'Madiun',
                'Magetan',
                'Ngawi',
                'Bojonegoro',
                'Tuban',
                'Lamongan',
                'Gresik',
                'Bangkalan',
                'Sampang',
                'Pamekasan',
                'Sumenep',

                'Kediri',
                'Blitar',
                'Malang',
                'Probolinggo',
                'Pasuruan',
                'Mojokerto',
                'Madiun',
                'Surabaya',
                'Batu',
            ],


            // =====================================================
            // BANTEN
            // =====================================================

            'Banten' => [

                'Pandeglang',
                'Lebak',
                'Tangerang',
                'Serang',

                'Tangerang',
                'Cilegon',
                'Serang',
                'Tangerang Selatan',
            ],


            // =====================================================
            // BALI
            // =====================================================

            'Bali' => [

                'Jembrana',
                'Tabanan',
                'Badung',
                'Gianyar',
                'Klungkung',
                'Bangli',
                'Karangasem',
                'Buleleng',

                'Denpasar',
            ],


            // =====================================================
            // NTB
            // =====================================================

            'Nusa Tenggara Barat' => [

                'Lombok Barat',
                'Lombok Tengah',
                'Lombok Timur',
                'Sumbawa',
                'Dompu',
                'Bima',
                'Sumbawa Barat',
                'Lombok Utara',

                'Mataram',
                'Bima',
            ],


            // =====================================================
            // NTT
            // =====================================================

            'Nusa Tenggara Timur' => [

                'Sumba Barat',
                'Sumba Timur',
                'Kupang',
                'Timor Tengah Selatan',
                'Timor Tengah Utara',
                'Belu',
                'Alor',
                'Lembata',
                'Flores Timur',
                'Sikka',
                'Ende',
                'Ngada',
                'Manggarai',
                'Rote Ndao',
                'Manggarai Barat',
                'Sumba Tengah',
                'Sumba Barat Daya',
                'Nagekeo',
                'Manggarai Timur',
                'Sabu Raijua',
                'Malaka',

                'Kupang',
            ],


            // =====================================================
            // KALIMANTAN BARAT
            // =====================================================

            'Kalimantan Barat' => [

                'Sambas',
                'Bengkayang',
                'Landak',
                'Mempawah',
                'Sanggau',
                'Ketapang',
                'Sintang',
                'Kapuas Hulu',
                'Sekadau',
                'Melawi',
                'Kayong Utara',
                'Kubu Raya',

                'Pontianak',
                'Singkawang',
            ],


            // =====================================================
            // KALIMANTAN TENGAH
            // =====================================================

            'Kalimantan Tengah' => [

                'Kotawaringin Barat',
                'Kotawaringin Timur',
                'Kapuas',
                'Barito Selatan',
                'Barito Utara',
                'Sukamara',
                'Lamandau',
                'Seruyan',
                'Katingan',
                'Pulang Pisau',
                'Gunung Mas',
                'Barito Timur',
                'Murung Raya',

                'Palangka Raya',
            ],


            // =====================================================
            // KALIMANTAN SELATAN
            // =====================================================

            'Kalimantan Selatan' => [

                'Tanah Laut',
                'Kotabaru',
                'Banjar',
                'Barito Kuala',
                'Tapin',
                'Hulu Sungai Selatan',
                'Hulu Sungai Tengah',
                'Hulu Sungai Utara',
                'Tabalong',
                'Tanah Bumbu',
                'Balangan',

                'Banjarmasin',
                'Banjarbaru',
            ],


            // =====================================================
            // KALIMANTAN TIMUR
            // =====================================================

            'Kalimantan Timur' => [

                'Paser',
                'Kutai Barat',
                'Kutai Kartanegara',
                'Kutai Timur',
                'Berau',
                'Penajam Paser Utara',
                'Mahakam Ulu',

                'Balikpapan',
                'Samarinda',
                'Bontang',
            ],


            // =====================================================
            // KALIMANTAN UTARA
            // =====================================================

            'Kalimantan Utara' => [

                'Malinau',
                'Bulungan',
                'Tana Tidung',
                'Nunukan',

                'Tarakan',
            ],


            // =====================================================
            // SULAWESI UTARA
            // =====================================================

            'Sulawesi Utara' => [

                'Bolaang Mongondow',
                'Minahasa',
                'Kepulauan Sangihe',
                'Kepulauan Talaud',
                'Minahasa Selatan',
                'Minahasa Utara',
                'Bolaang Mongondow Utara',
                'Kepulauan Siau Tagulandang Biaro',
                'Minahasa Tenggara',
                'Bolaang Mongondow Selatan',
                'Bolaang Mongondow Timur',

                'Manado',
                'Bitung',
                'Tomohon',
                'Kotamobagu',
            ],


            // =====================================================
            // SULAWESI TENGAH
            // =====================================================

            'Sulawesi Tengah' => [

                'Banggai Kepulauan',
                'Banggai',
                'Morowali',
                'Poso',
                'Donggala',
                'Tolitoli',
                'Buol',
                'Parigi Moutong',
                'Tojo Una-Una',
                'Sigi',
                'Banggai Laut',
                'Morowali Utara',

                'Palu',
            ],


            // =====================================================
            // SULAWESI SELATAN
            // =====================================================

            'Sulawesi Selatan' => [

                'Kepulauan Selayar',
                'Bulukumba',
                'Bantaeng',
                'Jeneponto',
                'Takalar',
                'Gowa',
                'Sinjai',
                'Maros',
                'Pangkajene dan Kepulauan',
                'Barru',
                'Bone',
                'Soppeng',
                'Wajo',
                'Sidenreng Rappang',
                'Pinrang',
                'Enrekang',
                'Luwu',
                'Tana Toraja',
                'Luwu Utara',
                'Luwu Timur',
                'Toraja Utara',

                'Makassar',
                'Parepare',
                'Palopo',
            ],


            // =====================================================
            // SULAWESI TENGGARA
            // =====================================================

            'Sulawesi Tenggara' => [

                'Buton',
                'Muna',
                'Konawe',
                'Kolaka',
                'Konawe Selatan',
                'Bombana',
                'Wakatobi',
                'Kolaka Utara',
                'Buton Utara',
                'Konawe Utara',
                'Kolaka Timur',
                'Konawe Kepulauan',
                'Muna Barat',
                'Buton Selatan',
                'Buton Tengah',

                'Kendari',
                'Baubau',
            ],


            // =====================================================
            // GORONTALO
            // =====================================================

            'Gorontalo' => [

                'Boalemo',
                'Gorontalo',
                'Pohuwato',
                'Bone Bolango',
                'Gorontalo Utara',

                'Gorontalo',
            ],


            // =====================================================
            // SULAWESI BARAT
            // =====================================================

            'Sulawesi Barat' => [

                'Majene',
                'Polewali Mandar',
                'Mamasa',
                'Mamuju',
                'Pasangkayu',
                'Mamuju Tengah',
            ],


            // =====================================================
            // MALUKU
            // =====================================================

            'Maluku' => [

                'Maluku Tengah',
                'Maluku Tenggara',
                'Kepulauan Tanimbar',
                'Buru',
                'Seram Bagian Timur',
                'Seram Bagian Barat',
                'Kepulauan Aru',
                'Maluku Barat Daya',
                'Buru Selatan',

                'Ambon',
                'Tual',
            ],


            // =====================================================
            // MALUKU UTARA
            // =====================================================

            'Maluku Utara' => [

                'Halmahera Barat',
                'Halmahera Tengah',
                'Halmahera Utara',
                'Halmahera Selatan',
                'Kepulauan Sula',
                'Halmahera Timur',
                'Pulau Morotai',
                'Pulau Taliabu',

                'Ternate',
                'Tidore Kepulauan',
            ],


            // =====================================================
            // PAPUA BARAT
            // =====================================================

            'Papua Barat' => [

                'Manokwari',
                'Fakfak',
                'Teluk Bintuni',
                'Teluk Wondama',
                'Kaimana',
                'Manokwari Selatan',
                'Pegunungan Arfak',
            ],


            // =====================================================
            // PAPUA
            // =====================================================

            'Papua' => [

                'Jayapura',
                'Kepulauan Yapen',
                'Biak Numfor',
                'Sarmi',
                'Keerom',
                'Waropen',
                'Supiori',
                'Mamberamo Raya',

                'Jayapura',
            ],


            // =====================================================
            // PAPUA SELATAN
            // =====================================================

            'Papua Selatan' => [

                'Merauke',
                'Boven Digoel',
                'Mappi',
                'Asmat',
            ],


            // =====================================================
            // PAPUA TENGAH
            // =====================================================

            'Papua Tengah' => [

                'Nabire',
                'Puncak Jaya',
                'Paniai',
                'Mimika',
                'Puncak',
                'Dogiyai',
                'Intan Jaya',
                'Deiyai',
            ],


            // =====================================================
            // PAPUA PEGUNUNGAN
            // =====================================================

            'Papua Pegunungan' => [

                'Jayawijaya',
                'Pegunungan Bintang',
                'Yahukimo',
                'Tolikara',
                'Mamberamo Tengah',
                'Yalimo',
                'Lanny Jaya',
                'Nduga',
            ],


            // =====================================================
            // PAPUA BARAT DAYA
            // =====================================================

            'Papua Barat Daya' => [

                'Sorong',
                'Sorong Selatan',
                'Raja Ampat',
                'Tambrauw',
                'Maybrat',

                'Sorong',
            ],

        ];


        foreach ($data as $provinceName => $cities) {

            $province = Province::where(
                'name',
                $provinceName
            )->first();

            if (!$province) {
                continue;
            }


            foreach ($cities as $cityName) {

                /*
                 * Karena data yang dimasukkan adalah daftar
                 * kabupaten/kota lengkap, koordinat sementara
                 * menggunakan koordinat pusat provinsi.
                 *
                 * Nanti koordinat masing-masing kota bisa
                 * diperbarui secara terpisah.
                 */

                City::updateOrCreate(
                    [
                        'province_id' => $province->id,
                        'slug' => Str::slug($cityName),
                    ],
                    [
                        'name' => $cityName,

                        'latitude' =>
                            $province->latitude,

                        'longitude' =>
                            $province->longitude,

                        'description' =>
                            $cityName .
                            ' merupakan wilayah administratif di Provinsi ' .
                            $provinceName . '.',
                    ]
                );
            }
        }
    }
}