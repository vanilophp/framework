<?php

use Illuminate\Database\Migrations\Migration;
use Konekt\Acl\Models\PermissionProxy;

/**
 * @see https://github.com/vanilophp/framework/issues/74
 *
 * This migration is to mitigate cases caused by Doctrine Inflector 1.4+
 * where the plural of 'taxon' has been changed from 'taxons'->'taxa'
 * When Vanilo migrations were ran with Inflector 1.4+, then these
 * taxon related permissions were created with incorrect naming
 * in the Database. If they're present, this SQL fixes them.
 */
class FixAccidentalTaxaNamedPermissions extends Migration
{
    /**
     * This migration fixes accidental
     */
    public function up()
    {
        PermissionProxy::where(['name' => 'list taxa'])->update(['name' => 'list taxons']);
        PermissionProxy::where(['name' => 'create taxa'])->update(['name' => 'create taxons']);
        PermissionProxy::where(['name' => 'view taxa'])->update(['name' => 'view taxons']);
        PermissionProxy::where(['name' => 'edit taxa'])->update(['name' => 'edit taxons']);
        PermissionProxy::where(['name' => 'delete taxa'])->update(['name' => 'delete taxons']);
    }

    public function down()
    {
        // It's not going downwards since the original state is unknown/inconsistent
    }
}
