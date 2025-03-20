<?php

declare(strict_types=1);

namespace Vanilo\Video\Tests;

use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Video\Models\Video;
use Vanilo\Video\Tests\Examples\Product;

class ModelVideoTest extends TestCase
{
    #[Test] public function it_can_be_created_through_the_relationship(): void
    {
        $product = Product::create([
            'name' => 'Dreamolino',
        ]);

        $product->videos()->create([
            'hash' => Str::uuid()->getHex(),
            'driver' => 'youtube',
            'reference' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ&ab',
        ]);

        $this->assertInstanceOf(Video::class, $product->videos()->first());
    }
}
