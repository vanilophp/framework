<?php

declare(strict_types=1);

namespace Vanilo\Video\Tests;

use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Video\Models\Video;
use Vanilo\Video\Tests\Examples\Product;

class VideoTest extends TestCase
{
    #[Test] public function it_can_be_created_with_minimal_data(): void
    {
        $product = Product::create([
            'name' => 'Nicer Dicer',
        ]);

        $hash = Str::uuid()->getHex();
        $product->videos()->create([
            'hash' => $hash,
            'type' => 'youtube',
            'reference' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ&ab',
        ])->fresh();

        $video = $product->videos->first();

        $this->assertInstanceOf(Video::class, $video);
        $this->assertEquals($hash, $video->hash);
        $this->assertEquals('youtube', $video->type);
        $this->assertEquals('https://www.youtube.com/watch?v=dQw4w9WgXcQ&ab', $video->reference);

        $this->assertDatabaseHas('videos', [
            'hash' => $hash,
            'type' => 'youtube',
            'reference' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ&ab',
        ]);
    }

    #[Test] public function it_can_be_created_with_full_data(): void
    {
        $product = Product::create([
            'name' => 'Neuro Socks',
        ]);

        $hash = Str::uuid()->getHex();

        $data = [
            'author' => '😎',
            'thumbnail_url' => 'https://img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg',
            'resolution' => '640x480',
            'language' => 'en',
            'tags' => ['music', '80s', 'classic'],
            'subtitles' => true,
            'likes' => 18_000_000,
            'views' => 1_600_000_000,
        ];

        $product->videos()->create([
            'hash' => $hash,
            'type' => 'youtube',
            'title' => 'Totally Not Suspicious URL',
            'description' => 'Watch it right now!',
            'width' => 640,
            'height' => 480,
            'duration' => 212,
            'size_in_bytes' => 398_400_000,
            'reference' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ&ab',
            'format' => 'video/mp4',
            'is_published' => true,
            'data' => $data
        ])->fresh();

        $video = $product->videos->first();

        $this->assertInstanceOf(Video::class, $video);
        $this->assertEquals($hash, $video->hash);
        $this->assertEquals('youtube', $video->type);
        $this->assertEquals('Totally Not Suspicious URL', $video->title);
        $this->assertEquals('Watch it right now!', $video->description);
        $this->assertEquals(640, $video->width);
        $this->assertEquals(480, $video->height);
        $this->assertEquals(212, $video->duration);
        $this->assertEquals(398400000, $video->size_in_bytes);
        $this->assertEquals('https://www.youtube.com/watch?v=dQw4w9WgXcQ&ab', $video->reference);
        $this->assertEquals('video/mp4', $video->format);
        $this->assertTrue($video->is_published);
        $this->assertEquals($data, $video->data);

        $this->assertDatabaseHas('videos', [
            'hash' => $hash,
            'type' => 'youtube',
            'title' => 'Totally Not Suspicious URL',
            'description' => 'Watch it right now!',
            'width' => 640,
            'height' => 480,
            'duration' => 212,
            'size_in_bytes' => 398400000,
            'reference' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ&ab',
            'format' => 'video/mp4',
            'is_published' => true,
            'data' => json_encode($data),
        ]);
    }

    #[Test]
    public function it_fails_when_hash_is_not_unique(): void
    {
        $product = Product::create([
            'name' => 'Neuro Socks',
        ]);

        $hash = Str::uuid()->getHex();

        $product->videos()->create([
            'hash' => $hash,
            'type' => 'youtube',
            'reference' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ&ab',
        ]);

        $this->assertDatabaseHas('videos', ['hash' => $hash]);

        $this->expectException(QueryException::class);

        $product->videos()->create([
            'hash' => $hash,
            'type' => 'youtube',
            'reference' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ&ab',
        ]);
    }
}
