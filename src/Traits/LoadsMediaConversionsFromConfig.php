<?php

declare(strict_types=1);

/**
 * Contains the HasImages trait.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-19
 *
 */

namespace Vanilo\Framework\Traits;

use Spatie\Image\Manipulations;

trait LoadsMediaConversionsFromConfig
{
    public function loadConversionsFromVaniloConfig(): void
    {
        $shortname = shorten(static::class);

        $variants = config("vanilo.framework.image.$shortname.variants");
        if (!is_array($variants)) {
            $variants = config('vanilo.framework.image.variants', []);
        }

        foreach ($variants as $name => $settings) {
            $conversion = $this->addMediaConversion($name)
               ->fit(
                   $settings['fit'] ?? Manipulations::FIT_CONTAIN,
                   $settings['width'] ?? 250,
                   $settings['height'] ?? 250
               );

            if (!($settings['queued'] ?? false)) {
                $conversion->nonQueued();
            }
        }
    }
}
