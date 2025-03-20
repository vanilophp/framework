<?php

declare(strict_types=1);

namespace Vanilo\Video\Tests;

use PHPUnit\Framework\Attributes\Test;
use Vanilo\Video\Drivers\Youtube;
use Vanilo\Video\Dto\DriverCapabilities;
use Vanilo\Video\Models\Video;

class YoutubeDriverTest extends TestCase
{
    #[Test] public function it_can_be_instantiated()
    {
        $driver = new Youtube();

        $this->assertInstanceOf(Youtube::class, new Youtube());
    }

    #[Test] public function it_supports_embedding_thumbnails_and_stats()
    {
        $caps = Youtube::capabilities();
        $this->assertInstanceOf(DriverCapabilities::class, $caps);
        $this->assertTrue($caps->embedding);
        $this->assertTrue($caps->thumbnails);
        $this->assertTrue($caps->stats);
    }

    #[Test] public function it_can_return_the_video_url()
    {
        $driver = new Youtube();
        $video = new Video(['reference' => '7sfftjuYdG4']);
        $url = $driver->getVideoUrl($video);
        $this->assertStringContainsString('watch?v=7sfftjuYdG4', $url);
        $this->assertStringStartsWith('https://', $url);
        $this->assertStringContainsString('youtub', $url);
    }

    #[Test] public function it_can_return_the_embed_code()
    {
        $driver = new Youtube();
        $video = new Video(['reference' => '7sfftjuYdG4']);
        $this->assertStringContainsString('7sfftjuYdG4', $driver->getEmbedCode($video));
    }
}
