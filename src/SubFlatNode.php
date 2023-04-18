<?php


namespace MKrawczyk\FunQuery;


class SubFlatNode implements \Iterator
{
    /**
     * @var \Iterator
     */
    private \Iterator $source;
    /**
     * @var ?\Iterator
     */
    private ?\Iterator $subSource;

    public function __construct(\Iterator $source)
    {
        $this->source = $source;
        $this->applyCurrent();
    }

    private function applyCurrent()
    {
        $current = $this->source->current();
        if ($current == null)
            $this->subSource = null;
        else if ($current instanceof Traversable)
            $this->subSource = $current->getIterator();
        else
            $this->subSource = FunQuery::create($current)->getIterator();
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        if ($this->subSource == null)
            return null;
        else
            return $this->subSource->current();
    }

    /**
     * @inheritDoc
     */
    public function next(): void
    {
        if ($this->subSource != null) {
            $this->subSource->next();
            if (!$this->subSource->valid()) {
                $this->source->next();
                $this->applyCurrent();
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        if ($this->subSource)
            return [$this->source->key(), $this->subSource->key()];
        else
            return null;
    }

    /**
     * @inheritDoc
     */
    public function valid(): bool
    {
        return $this->source->valid() && $this->subSource != null && $this->subSource->valid();
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        $this->source->rewind();
        $this->applyCurrent();

    }
}