<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            ['name'=>'create'],
            ['name'=>'read'],
            ['name'=>'update'],
            ['name'=>'delete'],
        ];

        foreach ($permissions as $permission){
            Permission::updateOrCreate(['name'=>$permission['name']],$permission);
        }

        // this can be done as separate statements
        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());

        // or may be done by chaining
        Role::create(['name' => 'manager'])->givePermissionTo(['create','read','update']);

        $role = Role::create(['name' => 'moderator']);
        $role->givePermissionTo('read','update');

        $role = Role::create(['name' => 'guest']);
        $role->givePermissionTo('read');
    }
}
