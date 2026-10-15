<?php

namespace Database\Seeders;

use App\Models\TesProduct;
use Illuminate\Database\Seeder;

class TesProductSeeder extends Seeder
{
    public function run(): void
    {
        TesProduct::factory(10000)->create();
    }
}
