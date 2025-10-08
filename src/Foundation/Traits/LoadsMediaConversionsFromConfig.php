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

namespace Vanilo\Foundation\Traits;

use Spatie\Image\Enums\Fit;

trait LoadsMediaConversionsFromConfig
{
    public function loadConversionsFromVaniloConfig(): void
    {
        $shortname = shorten(static::class);

        $variants = config("vanilo.foundation.image.$shortname.variants");
        if (!is_array($variants)) {
            $variants = config('vanilo.foundation.image.variants', []);
        }

        foreach ($variants as $name => $settings) {
            $conversion = $this->addMediaConversion($name)
               ->fit(
                   Fit::from($settings['fit'] ?? Fit::Contain->value),
                   $settings['width'] ?? 250,
                   $settings['height'] ?? 250
               );

            if (isset($settings['background'])) {
                $conversion->background($settings['background']);
            }

            if (is_string($fmt = $settings['format'] ?? null)) {
                if ('original' === strtolower($fmt)) {
                    $conversion->keepOriginalImageFormat();
                } else {
                    $conversion->format($fmt);
                }
            }

            if (!($settings['queued'] ?? false)) {
                $conversion->nonQueued();
            }
        }
    }
}
