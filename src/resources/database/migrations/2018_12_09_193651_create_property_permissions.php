<?php

use Illuminate\Database\Migrations\Migration;
use Konekt\Acl\Models\RoleProxy;
use Konekt\AppShell\Acl\ResourcePermissions;

class CreatePropertyPermissions extends Migration
{
    protected $resources = ['property', 'propertyvalue'];

    public function up()
    {
        $permissions = ResourcePermissions::createPermissionsForResource($this->resources);
        $adminRole   = RoleProxy::where(['name' => 'admin'])->first();

        if ($adminRole) {
            $adminRole->givePermissionTo($permissions);
        }
    }

    public function down()
    {
        ResourcePermissions::deletePermissionsForResource($this->resources);
    }
}
