<?php

namespace MKrawczyk\FunQuery;

class Group extends ArrayNode
{
    public function __construct($key, array $values)
    {
        parent::__construct($values);
        $this->key = $key;
    }
}