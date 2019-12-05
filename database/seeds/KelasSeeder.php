<?php

use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kelas = ['A', 'B', 'C'];

        for ($i=7; $i < 10; $i++) { 
            for ($j=0; $j < 3; $j++) {   
                $nama_kelas = strval($i).'-'.$kelas[$j];
                App\Group::create(['nama_kelas' => $nama_kelas]);
            }
        }
        
    }
}
