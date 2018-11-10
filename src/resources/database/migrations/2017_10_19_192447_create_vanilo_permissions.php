<?php

use Illuminate\Database\Migrations\Migration;
use Konekt\Acl\Models\RoleProxy;
use Konekt\AppShell\Acl\ResourcePermissions;

class CreateVaniloPermissions extends Migration
{
    protected $resources = ['product', 'order'];

    public function up()
    {
        $adminRole = RoleProxy::where(['name' => 'admin'])->firstOrFail();

        $adminRole->givePermissionTo(
            ResourcePermissions::createPermissionsForResource($this->resources)
        );
    }

    public function down()
    {
        ResourcePermissions::deletePermissionsForResource($this->resources);
    }
}
