<?php

declare(strict_types=1);

namespace Vanilo\Video\Contracts;

use Illuminate\Database\Eloquent\Model;

interface Video
{
    public function getId(): string|int;

    public function getHash(): string;

    public function getReference(): string;

    public function getDriver(): VideoDriver;

    public function getTitle(): ?string;

    public function getDescription(): ?string;

    public function getData(): ?array;

    public function getModel(): Model;

    public function getWidth(): ?int;

    public function getHeight(): ?int;

    public function getDuration(): ?int;

    public function isPublished(): bool;
}
