<?php

use App\Group;
use App\User;
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
      $kelas = Group::all();
			foreach ($kelas as $key => $value) {
				User::create([
					'group_id' => $value->id,
					'name'     => $value->nama_kelas,
					'email'    => $value->nama_kelas.'@school.com',
					'password' => '$2y$10$RZX7GK8woTjo0d2vnTMF/ukn68JQm9iimzcUNhH6kQu5I1xl3vnQC',
				]);
			}
    }
}
