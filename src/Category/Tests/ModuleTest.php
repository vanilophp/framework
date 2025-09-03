<?php

declare(strict_types=1);

/**
 * Contains the ModuleTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-08-27
 *
 */

namespace Vanilo\Category\Tests;

use PHPUnit\Framework\Attributes\Test;
use Vanilo\Category\Contracts\Taxon as TaxonContract;
use Vanilo\Category\Contracts\Taxonomy as TaxonomyContract;
use Vanilo\Category\Models\Taxon;
use Vanilo\Category\Models\Taxonomy;
use Vanilo\Category\Providers\ModuleServiceProvider;

class ModuleTest extends TestCaseWithoutDB
{
    #[Test] public function module_loads()
    {
        $this->assertInstanceOf(
            ModuleServiceProvider::class,
            $this->app->concord->module('vanilo.category')
        );
    }

    #[Test] public function models_are_registered()
    {
        $models = $this->app->concord->getModelBindings();

        $this->assertCount(2, $models);
        // Default model bindings should be registered by default
        $this->assertEquals(Taxonomy::class, $models->get(TaxonomyContract::class));
        $this->assertEquals(Taxon::class, $models->get(TaxonContract::class));
    }

    #[Test] public function shorts_are_registered()
    {
        $this->assertEquals(TaxonContract::class, concord()->short('taxon'));
        $this->assertEquals(TaxonomyContract::class, concord()->short('taxonomy'));
    }
}
