<?php


namespace MKrawczyk\FunQuery;


class SortNode extends PipelineNode
{
    /**
     * @var PipelineNode
     */
    private PipelineNode $source;
    /**
     * @var callable
     */
    private $fun;
    private $result = null;

    public function __construct(PipelineNode $source, callable $fun)
    {
        $this->source = $source;
        $this->fun = $fun;
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        $fun = $this->fun;
        if ($this->result === null) {
            $sourceArray = $this->source->toArray();
            usort($sourceArray, fn($a, $b) => $fun($a) <=> $fun($b));
            $this->result = $sourceArray;
        }
        return new \ArrayIterator($this->result);
    }
}