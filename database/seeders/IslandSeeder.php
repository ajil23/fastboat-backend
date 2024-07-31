<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IslandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('masterisland')->insert([
            'isd_name' => 'Bali',
            'isd_code' => 'BLI',
            'isd_slug_en' => 'bali',
            'isd_slug_idn' => 'bali',
            'isd_keyword' => 'bali, destinations in bali, bali what to do, bali islands',
            'isd_image1' => 'gilitransfers.png',
            'isd_image2' => 'gilitransfers.png',
            'isd_image3' => 'gilitransfers.png',
            'isd_map' => '-8.273633525320992, 115.05759851360975',
            'isd_description_en' => '-',
            'isd_description_idn' => '-',
            'isd_content_en' => '-',
            'isd_content_idn' => '-',
            'isd_updated_by' => 1,
        ]);
        DB::table('masterisland')->insert([
            'isd_name' => 'Gili Air',
            'isd_code' => 'GAI',
            'isd_slug_en' => 'gili-air',
            'isd_slug_idn' => 'gili-air',
            'isd_keyword' => 'gili air, gili islands, what to do in gili air, destination in gili air, how to get to gili air',
            'isd_image1' => 'gilitransfers.png',
            'isd_image2' => 'gilitransfers.png',
            'isd_image3' => 'gilitransfers.png',
            'isd_map' => '-8.357023777477952, 116.08165469049703',
            'isd_description_en' => '-',
            'isd_description_idn' => '-',
            'isd_content_en' => '-',
            'isd_content_idn' => '-',
            'isd_updated_by' => 1,
        ]);
        DB::table('masterisland')->insert([
            'isd_name' => 'Gili Meno',
            'isd_code' => 'GMI',
            'isd_slug_en' => 'gili-meno',
            'isd_slug_idn' => 'gili-meno',
            'isd_keyword' => 'gili meno, gili islands, gili meno things to do, how to get to gili meno',
            'isd_image1' => 'gilitransfers.png',
            'isd_image2' => 'gilitransfers.png',
            'isd_image3' => 'gilitransfers.png',
            'isd_map' => '-8.35413651043415, 116.0578795910508',
            'isd_description_en' => '-',
            'isd_description_idn' => '-',
            'isd_content_en' => '-',
            'isd_content_idn' => '-',
            'isd_updated_by' => 1,
        ]);
        DB::table('masterisland')->insert([
            'isd_name' => 'Gili Trawangan',
            'isd_code' => 'GTI',
            'isd_slug_en' => 'gili-trawangan',
            'isd_slug_idn' => 'gili-trawangan',
            'isd_keyword' => 'gili trawangan, gili t, gili islands, trawangan island',
            'isd_image1' => 'gilitransfers.png',
            'isd_image2' => 'gilitransfers.png',
            'isd_image3' => 'gilitransfers.png',
            'isd_map' => '-8.354955940203034, 116.04278047686812',
            'isd_description_en' => '-',
            'isd_description_idn' => '-',
            'isd_content_en' => '-',
            'isd_content_idn' => '-',
            'isd_updated_by' => 1,
        ]);
        DB::table('masterisland')->insert([
            'isd_name' => 'Lombok',
            'isd_code' => 'LMI',
            'isd_slug_en' => 'lombok-island',
            'isd_slug_idn' => 'lombok-island',
            'isd_keyword' => 'lombok, lombok islands, lombok destination',
            'isd_image1' => 'gilitransfers.png',
            'isd_image2' => 'gilitransfers.png',
            'isd_image3' => 'gilitransfers.png',
            'isd_map' => '-8.487948683556246, 116.27294555104835',
            'isd_description_en' => '-',
            'isd_description_idn' => '-',
            'isd_content_en' => '-',
            'isd_content_idn' => '-',
            'isd_updated_by' => 1,
        ]);
        DB::table('masterisland')->insert([
            'isd_name' => 'Nusa Lembongan',
            'isd_code' => 'NLI',
            'isd_slug_en' => 'nusa-lembongan',
            'isd_slug_idn' => 'nusa-lembongan',
            'isd_keyword' => 'nusa lembongan, lembongan, lembongan island, lembongan things to do, lembongan activities',
            'isd_image1' => 'gilitransfers.png',
            'isd_image2' => 'gilitransfers.png',
            'isd_image3' => 'gilitransfers.png',
            'isd_map' => '-8.678087768307856, 115.45572479219602',
            'isd_description_en' => '-',
            'isd_description_idn' => '-',
            'isd_content_en' => '-',
            'isd_content_idn' => '-',
            'isd_updated_by' => 1,
        ]);
        DB::table('masterisland')->insert([
            'isd_name' => 'Nusa Penida',
            'isd_code' => 'NPI',
            'isd_slug_en' => 'nusa-penida',
            'isd_slug_idn' => 'nusa-penida',
            'isd_keyword' => 'nusa penida, penida island, penida, penida activities, penida things to do',
            'isd_image1' => 'gilitransfers.png',
            'isd_image2' => 'gilitransfers.png',
            'isd_image3' => 'gilitransfers.png',
            'isd_map' => '-8.72521097283184, 115.5445064159822',
            'isd_description_en' => '-',
            'isd_description_idn' => '-',
            'isd_content_en' => '-',
            'isd_content_idn' => '-',
            'isd_updated_by' => 1,
        ]);
        DB::table('masterisland')->insert([
            'isd_name' => 'Java Island',
            'isd_code' => 'JVI',
            'isd_slug_en' => 'java-island',
            'isd_slug_idn' => 'java-island',
            'isd_keyword' => 'bromo, jogjakarta, yogyakarta, ijen, banyuwangi, jawa, java',
            'isd_image1' => 'gilitransfers.png',
            'isd_image2' => 'gilitransfers.png',
            'isd_image3' => 'gilitransfers.png',
            'isd_map' => '-7.558628309983764, 110.86764465235473',
            'isd_description_en' => '-',
            'isd_description_idn' => '-',
            'isd_content_en' => '-',
            'isd_content_idn' => '-',
            'isd_updated_by' => 1,
        ]);
    }
}
