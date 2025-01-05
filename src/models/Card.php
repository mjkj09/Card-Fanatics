<?php

namespace models;

class Card
{
    private $code;
    private $collection;

    public function __construct(string $code, string $collection)
    {
        $this->code = $code;
        $this->collection = $collection;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code)
    {
        $this->code = $code;
    }

    public function getCollection(): string
    {
        return $this->collection;
    }

    public function setCollection(string $collection)
    {
        $this->collection = $collection;
    }
}