<?php

use App\Student;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class StudentTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$faker = Faker::create();
		$nis = 1111;
		for ($j = 1; $j < 10; $j++) {

			for ($i = 0; $i < 11; $i++) {
				Student::create([
					'nis'           => $nis++,
					'group_id'      => $j,
					'name'          => $faker->name(),
					'jenis_kelamin' => ($nis % 2 == 0 ? 'L' : 'P'),
					'tahun_masuk'   => 2018,
					'tempat_lahir'  => 'denpasar',
					'tgl_lahir'     => $faker->dateTimeThisCentury()->format('Y-m-d'),
					'nama_ayah'     => $faker->name('male'),
					'nama_ibu'      => $faker->name('female'),
					'no_hp_ortu'    => $faker->phoneNumber,
					'alamat'        => $faker->address,
					'no_hp'         => $faker->e164PhoneNumber,
					'photo'         => 'photo.jpg',
				]);
			}

		}
	}
}
