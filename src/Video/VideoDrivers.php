<?php

declare(strict_types=1);

namespace Vanilo\Video;

use Illuminate\Support\Facades\App;
use Konekt\Extend\Concerns\HasRegistry;
use Konekt\Extend\Concerns\RequiresClassOrInterface;
use Konekt\Extend\Contracts\Registry;
use Vanilo\Video\Contracts\VideoDriver;
use Vanilo\Video\Exceptions\UnknownVideoDriverException;

final class VideoDrivers implements Registry
{
    use HasRegistry;
    use RequiresClassOrInterface;

    private static string $requiredInterface = VideoDriver::class;

    public static function register(string $id, string $class)
    {
        return self::add($id, $class);
    }

    public static function make(string $id, array $parameters = []): VideoDriver
    {
        $class = self::getClassOf($id);

        if (null === $class) {
            throw new UnknownVideoDriverException("No video driver is registered with the id `$id`.");
        }

        return App::make($class, $parameters);
    }
}
