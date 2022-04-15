<?php


namespace MKrawczyk\FunQuery;

use Iterator;

class IteratorNode extends FunQuery
{
    private Iterator $source;
    private array $content = [];
    private bool $completed = false;

    public function __construct(Iterator $source)
    {
        $this->source = $source;
    }

    /**
     * @inheritDoc
     */
    public function getIterator():SubIteratorNode
    {
        return new SubIteratorNode($this);
    }

    public function getItem(int $position)
    {
        $this->read($position);
        return $this->content[$position];
    }

    private function read(int $position): void
    {
        while (!$this->completed && count($this->content) <= $position) {
            if ($this->source->valid()) {
                $this->content[] = $this->source->current();
                $this->source->next();
            } else {
                $this->completed = true;
            }
        }
    }

    public function hasItem(int $position)
    {
        $this->read($position);
        return isset($this->content[$position]);
    }

}