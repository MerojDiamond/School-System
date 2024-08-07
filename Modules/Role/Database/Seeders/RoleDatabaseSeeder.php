<?php

namespace Modules\Role\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleDatabaseSeeder extends Seeder
{
    public function run()
    {
        Role::insert([
            ["name" => "Super-Admin", "guard_name" => "web"],
            ["name" => "Pupil", "guard_name" => "web"],
            ["name" => "Teacher", "guard_name" => "web"]
        ]);
        $permissions = [
            "show-users" => '{"en":"show users","ru":"показать пользователей","tj":"нишон додани истифодабарандагон"}',
            "show-trashed-users" => '{"en":"show-trashed-users","ru":"показать удалённых пользователей","tj":"нишон додани истифодабарандагони хоричшуда"}',
            "edit-users" => '{"en":"edit-users","ru":"изменить пользователей","tj":"тагйир додани истифодабарандагон"}',
            "delete-users" => '{"en":"delete-users","ru":"удалить пользователей","tj":"хорич кардани истифодабарандагон"}',
            "restore-users" => '{"en":"restore-users","ru":"восстановить пользователей","tj":"баркарор кардани истифодабарандагон"}',
            "delete-forever-users" => '{"en":"delete-forever-users","ru":"удалить пользователей навсегда","tj":"тамоман хорич кардани истифодабарандагон"}',
            "show-roles" => '{"en":"show-roles","ru":"показать роли","tj":"нишон додани макомхо"}',
            "create-roles" => '{"en":"create-roles","ru":"добавить ролей","tj":"хамроз кардани макомхо"}',
            "edit-roles" => '{"en":"edit-roles","ru":"изменить ролей","tj":"тагйир додани макомхо"}',
            "delete-roles" => '{"en":"delete-roles","ru":"удалить ролей","tj":"хорич кардани макомхо"}',
        ];
        foreach ($permissions as $name => $lang) {
            Permission::create([
                "name" => $name,
                "lang" => $lang
            ]);
        }
    }
}
