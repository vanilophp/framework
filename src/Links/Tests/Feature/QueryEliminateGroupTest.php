<?php

declare(strict_types=1);

/**
 * Contains the QueryEliminateGroupTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-23
 *
 */

namespace Vanilo\Links\Tests\Feature;

use Vanilo\Links\Models\LinkType;
use Vanilo\Links\Query\Eliminate;
use Vanilo\Links\Query\Establish;
use Vanilo\Links\Query\Get;
use Vanilo\Links\Tests\Dummies\TestProduct;
use Vanilo\Links\Tests\TestCase;

class QueryEliminateGroupTest extends TestCase
{
    /** @test */
    public function it_removes_the_model_and_all_linked_models_from_the_existing_links()
    {
        $productA = TestProduct::create(['name' => 'Product A'])->fresh();
        $productB = TestProduct::create(['name' => 'Product B'])->fresh();

        LinkType::create(['name' => 'Matches Style']);

        Establish::a('matches-style')->link()->between($productA)->and($productB);
        $this->assertCount(1, Get::the('matches-style')->links()->of($productA));
        $this->assertCount(1, Get::the('matches-style')->groups()->of($productA));

        Eliminate::the('matches-style')->group()->of($productA);
        $this->assertCount(0, Get::the('matches-style')
            ->links()
            ->of($productA));
        $this->assertCount(0, Get::the('matches-style')->links()->of($productB));
        $this->assertCount(0, Get::the('matches-style')->groups()->of($productA));
        $this->assertCount(0, Get::the('matches-style')->groups()->of($productB));
    }
}
