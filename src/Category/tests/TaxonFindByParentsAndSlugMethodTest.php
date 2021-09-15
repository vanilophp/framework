<?php

declare(strict_types=1);

/**
 * Contains the TaxonFindByParentsAndSlugMethodTest class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-11-28
 *
 */

namespace Vanilo\Category\Tests;

use Vanilo\Category\Models\Taxon;
use Vanilo\Category\Models\Taxonomy;

class TaxonFindByParentsAndSlugMethodTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        if ('6' === $this->app->version()[0]) {
            $this->markTestSkipped('This method is incompatible with Laravel 6');
        }
    }

    /** @test */
    public function it_returns_the_taxon_by_its_taxonomy_slug_and_his_own_slug()
    {
        $taxonomy = Taxonomy::create(['name' => 'Brand']);
        Taxon::create(['name' => 'Me me mee', 'taxonomy_id' => $taxonomy->id]);

        $foundTaxon = Taxon::findOneByParentsAndSlug('brand', 'me-me-mee');
        $this->assertInstanceOf(Taxon::class, $foundTaxon);
        $this->assertNull(Taxon::findOneByParentsAndSlug('no-such-taxonomy', 'me-me-mee'));
    }

    /** @test */
    public function it_returns_null_if_taxonomy_with_the_given_slug_does_not_exist()
    {
        $taxonomy = Taxonomy::create(['name' => 'Seasons']);
        $taxon = Taxon::create(['name' => 'Spring', 'taxonomy_id' => $taxonomy->id]);

        $this->assertNull(Taxon::findOneByParentsAndSlug('no-such-taxonomy', 'spring'));
    }

    /** @test */
    public function it_returns_null_if_the_passed_slug_exists_but_not_within_the_given_taxonomy()
    {
        $seasons = Taxonomy::create(['name' => 'Seasons']);
        $looks = Taxonomy::create(['name' => 'Looks']);
        Taxon::create(['name' => 'Spring', 'taxonomy_id' => $seasons->id]);
        Taxon::create(['name' => 'Chaos Look', 'taxonomy_id' => $looks->id]);

        $this->assertNull(Taxon::findOneByParentsAndSlug('seasons', 'chaos-look'));
    }

    /** @test */
    public function it_returns_the_taxon_from_the_given_taxonomy_if_two_taxons_with_the_same_slug_exist()
    {
        $seasons = Taxonomy::create(['name' => 'Seasons']);
        $looks = Taxonomy::create(['name' => 'Looks']);
        $springSeason = Taxon::create(['name' => 'Spring', 'taxonomy_id' => $seasons->id]);
        $springLook = Taxon::create(['name' => 'Spring', 'taxonomy_id' => $looks->id]);

        $foundSpringSeason = Taxon::findOneByParentsAndSlug('seasons', 'spring');
        $this->assertInstanceOf(Taxon::class, $foundSpringSeason);
        $this->assertEquals('Spring', $foundSpringSeason->name);
        $this->assertEquals('spring', $foundSpringSeason->slug);
        $this->assertEquals($seasons->id, $foundSpringSeason->taxonomy->id);
        $this->assertEquals($springSeason->id, $foundSpringSeason->id);

        $foundSpringLook = Taxon::findOneByParentsAndSlug('looks', 'spring');
        $this->assertInstanceOf(Taxon::class, $foundSpringLook);
        $this->assertEquals('Spring', $foundSpringLook->name);
        $this->assertEquals('spring', $foundSpringLook->slug);
        $this->assertEquals($looks->id, $foundSpringLook->taxonomy->id);
        $this->assertEquals($springLook->id, $foundSpringLook->id);
    }

    /** @test */
    public function it_returns_the_taxon_by_its_taxonomy_slug_his_own_slug_and_its_parent_taxon_slug()
    {
        $taxonomy = Taxonomy::create(['name' => 'Wine Regions']);
        $hungary = Taxon::create(['name' => 'Hungary', 'taxonomy_id' => $taxonomy->id]);
        $slovakia = Taxon::create(['name' => 'Slovakia', 'taxonomy_id' => $taxonomy->id]);
        $huTokaj = Taxon::create(['name' => 'Tokaj', 'parent_id' => $hungary->id, 'taxonomy_id' => $taxonomy->id]);
        $skTokaj = Taxon::create(['name' => 'Tokaj', 'parent_id' => $slovakia->id, 'taxonomy_id' => $taxonomy->id]);

        $foundSkTokaj = Taxon::findOneByParentsAndSlug('wine-regions', 'tokaj', 'slovakia');
        $this->assertInstanceOf(Taxon::class, $foundSkTokaj);
        $this->assertEquals($skTokaj->id, $foundSkTokaj->id);
        $this->assertEquals($skTokaj->slug, $foundSkTokaj->slug);
        $this->assertEquals($skTokaj->name, $foundSkTokaj->name);
        $this->assertEquals($skTokaj->taxonomy->id, $foundSkTokaj->taxonomy->id);
        $this->assertEquals($slovakia->id, $foundSkTokaj->parent->id);
        $this->assertEquals($slovakia->name, $foundSkTokaj->parent->name);
        $this->assertEquals($slovakia->slug, $foundSkTokaj->parent->slug);

        $foundHuTokaj = Taxon::findOneByParentsAndSlug('wine-regions', 'tokaj', 'hungary');
        $this->assertInstanceOf(Taxon::class, $foundHuTokaj);
        $this->assertEquals($huTokaj->id, $foundHuTokaj->id);
        $this->assertEquals($huTokaj->slug, $foundHuTokaj->slug);
        $this->assertEquals($huTokaj->name, $foundHuTokaj->name);
        $this->assertEquals($huTokaj->taxonomy->id, $foundHuTokaj->taxonomy->id);
        $this->assertEquals($hungary->id, $foundHuTokaj->parent->id);
        $this->assertEquals($hungary->name, $foundHuTokaj->parent->name);
        $this->assertEquals($hungary->slug, $foundHuTokaj->parent->slug);
    }

    /** @test */
    public function it_returns_the_taxon_by_its_taxonomy_slug_his_own_slug_and_its_parent_taxon_slug_regardless_of_levels()
    {
        $locations = Taxonomy::create(['name' => 'Locations']);
        $usa = Taxon::create(['name' => 'USA', 'taxonomy_id' => $locations->id]);
        $tennessee = Taxon::create(['name' => 'Tennessee', 'parent_id' => $usa->id, 'taxonomy_id' => $locations->id]);
        $egypt = Taxon::create(['name' => 'Egypt', 'taxonomy_id' => $locations->id]);

        $memphisTennessee = Taxon::create(['name' => 'Memphis', 'parent_id' => $tennessee->id, 'taxonomy_id' => $locations->id]);
        $memphisEgypt = Taxon::create(['name' => 'Memphis', 'parent_id' => $egypt->id, 'taxonomy_id' => $locations->id]);

        $foundMemphisEgypt = Taxon::findOneByParentsAndSlug('locations', 'memphis', 'egypt');
        $foundmemphisTennessee = Taxon::findOneByParentsAndSlug('locations', 'memphis', 'tennessee');

        $this->assertInstanceOf(Taxon::class, $foundMemphisEgypt);
        $this->assertEquals($memphisEgypt->id, $foundMemphisEgypt->id);
        $this->assertEquals($memphisEgypt->slug, $foundMemphisEgypt->slug);
        $this->assertEquals($memphisEgypt->name, $foundMemphisEgypt->name);
        $this->assertEquals($locations->id, $foundMemphisEgypt->taxonomy->id);
        $this->assertEquals($egypt->id, $foundMemphisEgypt->parent->id);
        $this->assertEquals($egypt->name, $foundMemphisEgypt->parent->name);
        $this->assertEquals($egypt->slug, $foundMemphisEgypt->parent->slug);

        $this->assertInstanceOf(Taxon::class, $foundmemphisTennessee);
        $this->assertEquals($memphisTennessee->id, $foundmemphisTennessee->id);
        $this->assertEquals($memphisTennessee->slug, $foundmemphisTennessee->slug);
        $this->assertEquals($memphisTennessee->name, $foundmemphisTennessee->name);
        $this->assertEquals($locations->id, $foundmemphisTennessee->taxonomy->id);
        $this->assertEquals($tennessee->id, $foundmemphisTennessee->parent->id);
        $this->assertEquals($tennessee->name, $foundmemphisTennessee->parent->name);
        $this->assertEquals($tennessee->slug, $foundmemphisTennessee->parent->slug);
    }

    /** @test */
    public function it_returns_null_if_the_passed_parent_taxon_does_not_exist()
    {
        $taxonomy = Taxonomy::create(['name' => 'Wine Regions']);
        $hungary = Taxon::create(['name' => 'Hungary', 'taxonomy_id' => $taxonomy->id]);
        $slovakia = Taxon::create(['name' => 'Slovakia', 'taxonomy_id' => $taxonomy->id]);
        Taxon::create(['name' => 'Tokaj', 'parent_id' => $hungary->id, 'taxonomy_id' => $taxonomy->id]);
        Taxon::create(['name' => 'Tokaj', 'parent_id' => $slovakia->id, 'taxonomy_id' => $taxonomy->id]);

        $this->assertNull(Taxon::findOneByParentsAndSlug('wine-regions', 'tokaj', 'romania'));
    }

    /** @test */
    public function it_returns_null_if_the_passed_parent_taxon_does_is_not_the_parent()
    {
        $taxonomy = Taxonomy::create(['name' => 'Countries']);
        $europe = Taxon::create(['name' => 'Europe', 'taxonomy_id' => $taxonomy->id]);
        Taxon::create(['name' => 'America', 'taxonomy_id' => $taxonomy->id]);
        Taxon::create(['name' => 'Italy', 'parent_id' => $europe->id, 'taxonomy_id' => $taxonomy->id]);

        $this->assertNull(Taxon::findOneByParentsAndSlug('countries', 'italy', 'america'));
    }

    /** @test */
    public function it_returns_null_if_there_is_a_passed_parent_taxon_but_the_taxon_with_the_given_taxon_has_no_parent()
    {
        $taxonomy = Taxonomy::create(['name' => 'Animals']);
        $mammals = Taxon::create(['name' => 'Mammals', 'taxonomy_id' => $taxonomy->id]);
        Taxon::create(['name' => 'Duck', 'taxonomy_id' => $taxonomy->id]);
        Taxon::create(['name' => 'Tiger', 'parent_id' => $mammals->id, 'taxonomy_id' => $taxonomy->id]);

        $this->assertNull(Taxon::findOneByParentsAndSlug('animals', 'duck', 'mammals'));
        // But it finds it if no parent slug gets passed:
        $this->assertInstanceOf(Taxon::class, Taxon::findOneByParentsAndSlug('animals', 'duck'));
    }
}
