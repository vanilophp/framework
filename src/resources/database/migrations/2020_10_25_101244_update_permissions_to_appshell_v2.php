<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Konekt\Acl\Models\Permission;

class UpdatePermissionsToAppshellV2 extends Migration
{
    private $permissionsToMigrate = [
        'list propertyvalues' => 'list property values',
        'create propertyvalues' => 'create property values',
        'view propertyvalues' => 'view property values',
        'edit propertyvalues' => 'edit property values',
        'delete propertyvalues' => 'delete property values',
    ];

    public function up()
    {
        foreach ($this->permissionsToMigrate as $old => $new) {
            $permission = Permission::findByName($old);
            if (null !== $permission) {
                $permission->update(['name' => $new]);
            }
        }
    }

    public function down()
    {
        foreach ($this->permissionsToMigrate as $old => $new) {
            $permission = Permission::findByName($new);
            if (null !== $permission) {
                $permission->update(['name' => $old]);
            }
        }
    }
}
