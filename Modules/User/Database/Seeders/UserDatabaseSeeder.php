<?php

namespace Modules\User\Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserDatabaseSeeder extends Seeder
{
    public function run()
    {
        $user = User::create([
            "name" => "Super Admin",
            "email" => "admin@example.com",
            "birthday" => "2007-12-28",
            "address" => "Tajikistan. Istaravshan",
            "tel" => 880886643,
            "gender" => true,
            "password" => "1234",
            "person_type" => Teacher::class,
            "person_id" => 1
        ]);
        $user->assignRole("Super-Admin");
    }
}
