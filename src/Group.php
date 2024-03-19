<?php

namespace MKrawczyk\FunQuery;

class Group extends ArrayNode
{
    /**
     * @var mixed
     */
    public $key;

    public function __construct($key, array $values)
    {
        parent::__construct($values);
        $this->key = $key;
    }
}
