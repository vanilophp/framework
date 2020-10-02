<?php
/**
 * Contains the ProductImageTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-05-12
 *
 */

namespace Vanilo\Framework\Tests;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Vanilo\Framework\Models\Product;

class ProductImageTest extends TestCase
{
    protected const TEST_IMAGE = __DIR__ . '/img/vanilo_640.png';

    /** @test */
    public function an_image_can_be_added_to_a_product()
    {
        /** @var Product $product */
        $product = Product::create([
            'name' => 'Nokia 7 Plus',
            'sku'  => 'TA-1062'
        ]);

        $product->addMedia(self::TEST_IMAGE)
                ->preservingOriginal()
                ->toMediaCollection();

        $this->assertInstanceOf(Media::class, $product->getFirstMedia());
        $this->assertNotEmpty($product->getFirstMediaUrl());
        $this->assertNotEmpty($product->getFirstMediaPath());
        $this->assertSame(
            filesize(self::TEST_IMAGE),
            filesize($product->getFirstMediaPath())
        );
    }
}
