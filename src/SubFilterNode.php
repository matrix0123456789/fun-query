<?php


namespace MKrawczyk\FunQuery;


class SubFilterNode implements \Iterator
{
    /**
     * @var \Iterator
     */
    private \Iterator $source;
    /**
     * @var callable
     */
    private $fun;
    private bool $started;

    public function __construct(\Iterator $source, callable $fun)
    {
        $this->source = $source;
        $this->fun = $fun;
        $this->started=false;
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        $fun = $this->fun;
        while ($this->source->valid()) {
            $current = $this->source->current();
            if ($fun($current))
                return $current;
            else
                $this->source->next();
        }
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        $fun = $this->fun;
        if(!$this->started){
            while ($this->source->valid()) {
                $current = $this->source->current();
                if ($fun($this->source->current()))
                    break;
                else
                    $this->source->next();
            }
            $this->started=true;
        }

        $this->source->next();
        while ($this->source->valid()) {
            $current = $this->source->current();
            if ($fun($this->source->current()))
                return;
            else
                $this->source->next();
        }
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        $fun = $this->fun;
        while ($this->source->valid()) {
            $current = $this->source->current();
            if ($fun($this->source->current()))
                return $this->source->key();
            else
                $this->source->next();
        }
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        $fun = $this->fun;
        while ($this->source->valid()) {
            if ($fun($this->source->current()))
                return true;
            else
                $this->source->next();
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        $this->source->rewind();
        $this->started=false;
    }
}