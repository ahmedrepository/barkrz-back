<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => '1',
                'name' => 'Admin',
                'email' => 'barkrz@admin.com',
                'email_verified_at' => null,
                'password' => bcrypt('barkrzadmin'),
                'expiry' => null,
                'membership_plan' => '0',
                'membership_created' => null,
                'membership_updated' => null,
                'admin' => '1',
                'remember_token' => 's4GGLBsoXKXw1fUnHeazKDws8tNILF7tjzGAA7bHqlQBvfl04XokPFpqUXWF',
                'created_at' => '2020-10-07 14:17:25',
                'updated_at' => '2020-10-07 14:17:25'
            ],
            [
                'id' => '2',
                'name' => 'sample',
                'email' => 'sample@barkrz.com',
                'email_verified_at' => null,
                'password' => bcrypt('sample'),
                'expiry' => null,
                'membership_plan' => '2',
                'membership_created' => '2021-11-26',
                'membership_updated' => '2021-11-26',
                'admin' => '0',
                'remember_token' => null,
                'created_at' => '2020-10-07 14:18:28',
                'updated_at' => '2020-10-07 14:19:22'
            ],
        ]);


        DB::table('coupons')->insert([
            [
                'id' => '1',
                'beta' => '224f3fs35',
                'fam' => 'f32dsf23f32',
                'created_at' => '2020-10-07 22:18:20',
                'updated_at' => '2020-10-07 22:18:23',
            ],
        ]);
    }
}