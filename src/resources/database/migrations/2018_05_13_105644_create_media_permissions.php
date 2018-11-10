<?php

use Illuminate\Database\Migrations\Migration;
use Konekt\Acl\Models\RoleProxy;
use Konekt\AppShell\Acl\ResourcePermissions;

class CreateMediaPermissions extends Migration
{
    protected $resources = ['media'];

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
