<?php

use Illuminate\Database\Migrations\Migration;
use Konekt\Acl\Models\PermissionProxy;
use Konekt\Acl\Models\RoleProxy;
use Konekt\Acl\PermissionRegistrar;
use Konekt\AppShell\Acl\ResourcePermissionMapper;

class AddPaymentMethodPermissions extends Migration
{
    private array $resources = ['payment-method'];

    private ?ResourcePermissionMapper $mapper = null;

    public function up()
    {
        $adminRole = RoleProxy::where('name', 'admin')->first();

        foreach ($this->resources as $resource) {
            $permissions = $this->getPermissionsForResource($resource);

            foreach ($permissions as $permission) {
                PermissionProxy::create([
                    'name' => $permission
                ]);
            }

            if ($adminRole) {
                $adminRole->givePermissionTo($permissions);
            }
        }
    }

    public function down()
    {
        foreach ($this->resources as $resource) {
            foreach ($this->getPermissionsForResource($resource) as $permissionName) {
                PermissionProxy::where(['name' => $permissionName])->delete();
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function getPermissionsForResource(string $resource)
    {
        return $this->mapper()->allPermissionsFor($resource);
    }

    private function mapper(): ResourcePermissionMapper
    {
        if (!$this->mapper) {
            $this->mapper = new ResourcePermissionMapper();
        }

        return $this->mapper;
    }
}
