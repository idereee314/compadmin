<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use user\CompadUser as User;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User;

		$user->firstname = 'aagii';
		$user->lastname = ' ';
		$user->email = 'aagii@1.1';
		$user->username = 'aagii';
		$user->phone_number =  '11111';
		$user->password = md5('password');

		$user->save();
        
    }
}
