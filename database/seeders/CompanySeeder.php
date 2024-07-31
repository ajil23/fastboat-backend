<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('datacompany')->insert([
            'cpn_name' => 'Gilitransfers',
            'cpn_email' => 'reservation@gilitransfers.com',
            'cpn_email_status' => 1,
            'cpn_phone' => 6281122223333,
            'cpn_whatsapp' => 6281122223333,
            'cpn_address' => 'Jl Gong Kebyar 22 Jimbaran',
            'cpn_logo' => "gilitransfers.png",
            'cpn_website' => 'https://gilitransfers.com',
            'cpn_status' => 1,
            'cpn_type' => 'fast_boat',
            'cpn_updated_by' => 1, 
        ]);
        DB::table('datacompany')->insert([
            'cpn_name' => 'Angel Billabong',
            'cpn_email' => 'suppliergilitransfers@gmail.com',
            'cpn_email_status' => 1,
            'cpn_phone' => 6281122223333,
            'cpn_whatsapp' => 6281122223333,
            'cpn_address' => 'Jl. Sanur Beach Street Walk, Sanur Kaja, Denpasaraddress_Angel Billabong',
            'cpn_logo' => "gilitransfers.png",
            'cpn_website' => 'https://angelbillabongfastcruise.com',
            'cpn_status' => 1,
            'cpn_type' => 'fast_boat',
            'cpn_updated_by' => 1, 
        ]);
        DB::table('datacompany')->insert([
            'cpn_name' => 'Arthamas Express',
            'cpn_email' => 'info@arthamasexpress.com',
            'cpn_email_status' => 1,
            'cpn_phone' => 6281122223333,
            'cpn_whatsapp' => 6281122223333,
            'cpn_address' => 'Jl. Matahari Terbit Sanur, Denpasar, Bali',
            'cpn_logo' => "gilitransfers.png",
            'cpn_website' => 'https://www.arthamasexpress.com/',
            'cpn_status' => 1,
            'cpn_type' => 'fast_boat',
            'cpn_updated_by' => 1, 
        ]);
        DB::table('datacompany')->insert([
            'cpn_name' => 'Bluewater Express',
            'cpn_email' => 'booking@bluewater-express.com',
            'cpn_email_status' => 1,
            'cpn_phone' => 6281122223333,
            'cpn_whatsapp' => 6281122223333,
            'cpn_address' => 'Jl Tukad Punggawa Br Pojok Serangan',
            'cpn_logo' => "gilitransfers.png",
            'cpn_website' => 'https://bluewater-express.com/',
            'cpn_status' => 1,
            'cpn_type' => 'fast_boat',
            'cpn_updated_by' => 1, 
        ]);
        DB::table('datacompany')->insert([
            'cpn_name' => 'Caspla Bali Seaview',
            'cpn_email' => 'director@baliseaview.com',
            'cpn_email_status' => 1,
            'cpn_phone' => 6281122223333,
            'cpn_whatsapp' => 6281122223333,
            'cpn_address' => 'Jl. Pulau Moyo Gang Cemara A No 4 Pedungan Denpasar',
            'cpn_logo' => "gilitransfers.png",
            'cpn_website' => 'https://baliseaview.com/',
            'cpn_status' => 1,
            'cpn_type' => 'fast_boat',
            'cpn_updated_by' => 1, 
        ]);
        DB::table('datacompany')->insert([
            'cpn_name' => "D'Camel Fast Ferry",
            'cpn_email' => 'reservation@dcamelfastferry.com',
            'cpn_email_status' => 1,
            'cpn_phone' => 6281122223333,
            'cpn_whatsapp' => 6281122223333,
            'cpn_address' => 'Jl. Hangtuah no. 27 Sanur Denpasar',
            'cpn_logo' => "gilitransfers.png",
            'cpn_website' => 'https://dcamelfastferry.com',
            'cpn_status' => 1,
            'cpn_type' => 'fast_boat',
            'cpn_updated_by' => 1, 
        ]);
        DB::table('datacompany')->insert([
            'cpn_name' => "D'Prabu",
            'cpn_email' => 'd.prabufastboat@gmail.com',
            'cpn_email_status' => 1,
            'cpn_phone' => 6281122223333,
            'cpn_whatsapp' => 6281122223333,
            'cpn_address' => 'Jl. Gurita No. 22, Br. Pegok Sesetan Denpasar',
            'cpn_logo' => "gilitransfers.png",
            'cpn_website' => '-',
            'cpn_status' => 1,
            'cpn_type' => 'fast_boat',
            'cpn_updated_by' => 1, 
        ]);
        DB::table('datacompany')->insert([
            'cpn_name' => 'Eka Jaya',
            'cpn_email' => 'reservation@baliekajaya.com',
            'cpn_email_status' => 1,
            'cpn_phone' => 6281122223333,
            'cpn_whatsapp' => 6281122223333,
            'cpn_address' => 'Jl. Setia Budi no 18 Kuta Badung',
            'cpn_logo' => "gilitransfers.png",
            'cpn_website' => 'https://ekajayafastboat.com/',
            'cpn_status' => 1,
            'cpn_type' => 'fast_boat',
            'cpn_updated_by' => 1, 
        ]);
        DB::table('datacompany')->insert([
            'cpn_name' => 'Gangga Express',
            'cpn_email' => 'suppliergilitransfers@gmail.com',
            'cpn_email_status' => 1,
            'cpn_phone' => 6281122223333,
            'cpn_whatsapp' => 6281122223333,
            'cpn_address' => 'Br. Tribhuwana, Kusamba, Dawan, Klungkung',
            'cpn_logo' => "gilitransfers.png",
            'cpn_website' => '-',
            'cpn_status' => 1,
            'cpn_type' => 'fast_boat',
            'cpn_updated_by' => 1, 
        ]);
        DB::table('datacompany')->insert([
            'cpn_name' => 'Glory Fast Cruise',
            'cpn_email' => 'booking@gloryfastcruise.com',
            'cpn_email_status' => 1,
            'cpn_phone' => 6281122223333,
            'cpn_whatsapp' => 6281122223333,
            'cpn_address' => 'Jl. Hangtuah No 27 Sanur, Denpasar',
            'cpn_logo' => "gilitransfers.png",
            'cpn_website' => 'https://www.gloryfastcruise.com/',
            'cpn_status' => 1,
            'cpn_type' => 'fast_boat',
            'cpn_updated_by' => 1, 
        ]);
        DB::table('datacompany')->insert([
            'cpn_name' => 'Idola Express',
            'cpn_email' => 'info@idolaexpress.com',
            'cpn_email_status' => 1,
            'cpn_phone' => 6281122223333,
            'cpn_whatsapp' => 6281122223333,
            'cpn_address' => '-',
            'cpn_logo' => "gilitransfers.png",
            'cpn_website' => 'https://www.idolaexpress.com/',
            'cpn_status' => 1,
            'cpn_type' => 'fast_boat',
            'cpn_updated_by' => 1, 
        ]);
        DB::table('datacompany')->insert([
            'cpn_name' => 'Maruti Express',
            'cpn_email' => 'rsv@marutigroupfastboat.com',
            'cpn_email_status' => 1,
            'cpn_phone' => 6281122223333,
            'cpn_whatsapp' => 6281122223333,
            'cpn_address' => 'Matahari Terbit Arcade 5, Jln. Matahari Terbit, Sanur Kaja, Denpasar',
            'cpn_logo' => "gilitransfers.png",
            'cpn_website' => 'https://marutigroupfastboat.com/',
            'cpn_status' => 1,
            'cpn_type' => 'fast_boat',
            'cpn_updated_by' => 1, 
        ]);
        DB::table('datacompany')->insert([
            'cpn_name' => 'Ostina',
            'cpn_email' => 'balihappymd@gmail.com',
            'cpn_email_status' => 1,
            'cpn_phone' => 6281122223333,
            'cpn_whatsapp' => 6281122223333,
            'cpn_address' => 'Jl. Pelabuhan Padang Bai, Padangbai, Manggis.',
            'cpn_logo' => "gilitransfers.png",
            'cpn_website' => 'https://www.ostinafastboat.com/',
            'cpn_status' => 1,
            'cpn_type' => 'fast_boat',
            'cpn_updated_by' => 1, 
        ]);
        DB::table('datacompany')->insert([
            'cpn_name' => 'Semabu Hills',
            'cpn_email' => 'suppliergilitransfers@gmail.com',
            'cpn_email_status' => 1,
            'cpn_phone' => 6281122223333,
            'cpn_whatsapp' => 6281122223333,
            'cpn_address' => 'Jl. Hang Tuah No.27, Sanur, Denpasar',
            'cpn_logo' => "gilitransfers.png",
            'cpn_website' => '-',
            'cpn_status' => 1,
            'cpn_type' => 'fast_boat',
            'cpn_updated_by' => 1, 
        ]);
        DB::table('datacompany')->insert([
            'cpn_name' => 'The Tanis Express',
            'cpn_email' => 'tanislembongan@gmail.com',
            'cpn_email_status' => 1,
            'cpn_phone' => 6281122223333,
            'cpn_whatsapp' => 6281122223333,
            'cpn_address' => 'Ruko Arcade, Jl. Matahari Terbit, Sanur Kaja, Denpasar',
            'cpn_logo' => "gilitransfers.png",
            'cpn_website' => 'https://tanisbeachresort.com/',
            'cpn_status' => 1,
            'cpn_type' => 'fast_boat',
            'cpn_updated_by' => 1, 
        ]);
        DB::table('datacompany')->insert([
            'cpn_name' => 'Wijaya Buyuk',
            'cpn_email' => 'suppliergilitransfers@gmail.com',
            'cpn_email_status' => 1,
            'cpn_phone' => 6281122223333,
            'cpn_whatsapp' => 6281122223333,
            'cpn_address' => 'Jl. Hang Tuah, Sanur, Denpasar',
            'cpn_logo' => "gilitransfers.png",
            'cpn_website' => '-',
            'cpn_status' => 1,
            'cpn_type' => 'fast_boat',
            'cpn_updated_by' => 1, 
        ]);
        DB::table('datacompany')->insert([
            'cpn_name' => 'Gili Getaway',
            'cpn_email' => 'giligetaway@ozemail.com.au',
            'cpn_email_status' => 1,
            'cpn_phone' => 6281122223333,
            'cpn_whatsapp' => 6281122223333,
            'cpn_address' => 'Jln Tukad Punggawa no 25 Serangan Denpasar',
            'cpn_logo' => "gilitransfers.png",
            'cpn_website' => 'https://giligetaway.com',
            'cpn_status' => 1,
            'cpn_type' => 'fast_boat',
            'cpn_updated_by' => 1, 
        ]);
        DB::table('datacompany')->insert([
            'cpn_name' => 'Rocky Fast Cruise',
            'cpn_email' => 'reservations@rockyfastcruise.com',
            'cpn_email_status' => 1,
            'cpn_phone' => 6281122223333,
            'cpn_whatsapp' => 6281122223333,
            'cpn_address' => 'Jl. Matahari terbit, Sanur kaja, Denpasar',
            'cpn_logo' => "gilitransfers.png",
            'cpn_website' => 'https://rockyfastcruise.com/',
            'cpn_status' => 1,
            'cpn_type' => 'fast_boat',
            'cpn_updated_by' => 1, 
        ]);
        DB::table('datacompany')->insert([
            'cpn_name' => 'Golden Queen',
            'cpn_email' => 'booking@goldenqueenfastboat.com',
            'cpn_email_status' => 1,
            'cpn_phone' => 6281122223333,
            'cpn_whatsapp' => 6281122223333,
            'cpn_address' => 'Jl. By Pass Ngurah Rai No. 126',
            'cpn_logo' => "gilitransfers.png",
            'cpn_website' => 'https://goldenqueenfastboat.com',
            'cpn_status' => 1,
            'cpn_type' => 'fast_boat',
            'cpn_updated_by' => 1, 
        ]);
        DB::table('datacompany')->insert([
            'cpn_name' => 'Rayfish Fast Cruise',
            'cpn_email' => 'rayfishfastcruises@gmail.com',
            'cpn_email_status' => 1,
            'cpn_phone' => 6281122223333,
            'cpn_whatsapp' => 6281122223333,
            'cpn_address' => 'Jl. Pantai Matahari Terbit, No.4 Sanur Kaja, Denpasar',
            'cpn_logo' => "gilitransfers.png",
            'cpn_website' => 'https://rayfishfastcruises.com/',
            'cpn_status' => 1,
            'cpn_type' => 'fast_boat',
            'cpn_updated_by' => 1, 
        ]);
    }
}
