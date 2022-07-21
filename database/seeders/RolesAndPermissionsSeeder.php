<?php

namespace Database\Seeders;

use App\Security\Permissions;
use App\Security\Roles;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{

    /**
     * Roles.
     *
     * @var array
     */
    protected array $roles = [
        Roles::ADMIN => [
            Permissions::READ_USER,
            Permissions::EDIT_USER,
            Permissions::DELETE_USER,
            Permissions::CREATE_PRODUCT,
            Permissions::EDIT_PRODUCT,
            Permissions::DELETE_PRODUCT,
            Permissions::READ_CATEGORY,
            Permissions::CREATE_CATEGORY,
            Permissions::EDIT_CATEGORY,
            Permissions::DELETE_CATEGORY,
            Permissions::READ_SALES,
        ],
        Roles::EDIT => [
            Permissions::CREATE_PRODUCT,
            Permissions::EDIT_PRODUCT,
            Permissions::DELETE_PRODUCT,
            Permissions::READ_CATEGORY,
            Permissions::CREATE_CATEGORY,
            Permissions::EDIT_CATEGORY,
            Permissions::DELETE_CATEGORY,
            Permissions::READ_SALES,
        ]
    ];

    /**
     * Todos los permisos.
     *
     * @var array
     */
    protected array $permissions = [
        Permissions::READ_USER,
        Permissions::EDIT_USER,
        Permissions::DELETE_USER,
        Permissions::CREATE_PRODUCT,
        Permissions::EDIT_PRODUCT,
        Permissions::DELETE_PRODUCT,
        Permissions::READ_CATEGORY,
        Permissions::CREATE_CATEGORY,
        Permissions::EDIT_CATEGORY,
        Permissions::DELETE_CATEGORY,
        Permissions::READ_SALES,
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedPermissions();
        $this->seedRoles();
    }

    private function seedPermissions()
    {

        foreach ($this->permissions as $permission) {

            Permission::create([
                'name' => $permission
            ]);
        }
    }

    private function seedRoles()
    {
        foreach ($this->roles as $role => $permissions) {

            $role = Role::create([
                'name' => $role
            ]);

            $role->syncPermissions($permissions);
        }
    }
}
