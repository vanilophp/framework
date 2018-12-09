<?php

use Illuminate\Database\Migrations\Migration;
use Konekt\Acl\Models\RoleProxy;
use Konekt\AppShell\Acl\ResourcePermissions;

class CreatePropertyPermissions extends Migration
{
    protected $resources = ['property', 'propertyvalue'];

    public function up()
    {
        $adminRole = RoleProxy::where(['name' => 'admin'])->firstOrFail();

        $permissions = ResourcePermissions::createPermissionsForResource($this->resources);

        if ($adminRole) {
            $adminRole->givePermissionTo($permissions);
        }
    }

    public function down()
    {
        ResourcePermissions::deletePermissionsForResource($this->resources);
    }
}
