<?php

declare(strict_types = 1);

namespace App\Entity;

class AbstractEntity
{
    public const OUTPUT_GROUP = 'output';
    public const INPUT_GROUP = 'input';

    protected ?int $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
}
