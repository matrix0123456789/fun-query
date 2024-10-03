<?php


namespace MKrawczyk\FunQuery;


class SubConcatNode implements \Iterator
{

    private \Iterator $source1;
    private \Iterator $source2;
    private \Iterator $currentSource;

    public function __construct(\Iterator $source1, \Iterator $source2)
    {
        $this->source1 = $source1;
        $this->source2 = $source2;
        $this->currentSource = $source1;
    }

    /**
     * @inheritDoc
     */
    public function current():mixed
    {
        return $this->currentSource->current();
    }

    /**
     * @inheritDoc
     */
    public function next(): void
    {
        $this->currentSource->next();
        if (!$this->currentSource->valid()) {
            if ($this->currentSource === $this->source1)
                $this->currentSource = $this->source2;

        }
    }

    /**
     * @inheritDoc
     */
    public function key() :mixed
    {
        return $this->currentSource->key();
    }

    /**
     * @inheritDoc
     */
    public function valid(): bool
    {
        return $this->currentSource->valid();
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        $this->source1->rewind();
        $this->source2->rewind();
        if ($this->source1->valid())
            $this->currentSource = $this->source1;
        else
            $this->currentSource = $this->source2;
    }
}
