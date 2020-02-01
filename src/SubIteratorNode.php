<?php


namespace MKrawczyk\FunQuery;


class SubIteratorNode implements \Iterator
{
    /**
     * @var IteratorNode
     */
    private IteratorNode $parent;
    /**
     * @var int
     */
    private int $position;

    public function __construct(IteratorNode $parent)
    {
        $this->parent = $parent;
        $this->position = 0;
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return $this->parent->getItem($this->position);
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        return $this->parent->hasItem($this->position);
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        $this->position = 0;
    }
}