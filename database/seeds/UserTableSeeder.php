<?php

use App\Group;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$faker = Faker::create();
		for ($i=0; $i < 11; $i++) { 
			$firstName = $faker->firstName();
 			User::create([
				'name'     => $firstName.' '.$faker->lastName,
				'email'    => $firstName. '@school.com',
				'photo'		 => 'photo.jpg',
				'password' => '$2y$10$RZX7GK8woTjo0d2vnTMF/ukn68JQm9iimzcUNhH6kQu5I1xl3vnQC',
			]);
		}
		User::create([
			'name'     => 'pengurus',
			'email'    => 'pengurus@school.com',
			'role'		 => 'pengurus',
			'photo'		 => 'photo.jpg',
			'password' => '$2y$10$RZX7GK8woTjo0d2vnTMF/ukn68JQm9iimzcUNhH6kQu5I1xl3vnQC',
		]);
	}
}
