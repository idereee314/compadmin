<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountryAbbrevationConst extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::table('uq_country_abbrevation')->insert([
                ['abbrevation' => 'mn', 'token' => 'MGL'],
                ['abbrevation' => 'cn', 'token' => 'CHN'],
                ['abbrevation' => 'jp', 'token' => 'JPN'],
                ['abbrevation' => 'kr', 'token' => 'KOR'],
                ['abbrevation' => 'us', 'token' => 'USA'],
                ['abbrevation' => 'ru', 'token' => 'RUS'],
                ['abbrevation' => 'de', 'token' => 'GER'],
                ['abbrevation' => 'fr', 'token' => 'FRA'],
                ['abbrevation' => 'gb', 'token' => 'GBR'],
                ['abbrevation' => 'it', 'token' => 'ITA'],
                ['abbrevation' => 'es', 'token' => 'ESP'],
                ['abbrevation' => 'ca', 'token' => 'CAN'],
                ['abbrevation' => 'au', 'token' => 'AUS'],
                ['abbrevation' => 'br', 'token' => 'BRA'],
            ]);
            echo "Inserted\n";
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
