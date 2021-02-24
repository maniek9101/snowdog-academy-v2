<?php

namespace Snowdog\Academy\Model;

class UserCryptocurrency
{
    private string $id;
    private string $name;
    private int $amount;

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
