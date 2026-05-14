<?php

declare(strict_types=1);

namespace Vanilo\Contracts;

interface LineItemCollection extends ItemCollection
{
    public function total(): float;

    public function create(string $type, string $name, float $price, int|float $quantity = 1, array $attributes = []): LineItem;

    public function add(LineItem $item): void;

    public function remove(LineItem $item): void;

    public function deleteByType(LineItemType $type): void;

    public function first(): ?LineItem;

    public function last(): ?LineItem;

    public function byType(LineItemType $type): self;

    public function exceptTypes(LineItemType ...$types): self;
}
