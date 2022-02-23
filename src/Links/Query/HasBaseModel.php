<?php

declare(strict_types=1);

/**
 * Contains the HasBaseModel trait.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-23
 *
 */

namespace Vanilo\Links\Query;

use Illuminate\Database\Eloquent\Model;

trait HasBaseModel
{
    private Model $baseModel;

    public function between(Model $model): self
    {
        $this->baseModel = $model;

        return $this;
    }
}
