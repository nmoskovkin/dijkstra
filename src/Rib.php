<?php
declare(strict_types=1);

namespace Dijkstra;

class Rib
{
    /**
     * @var Node
     */
    private $from;

    /**
     * @var Node
     */
    private $to;

    /**
     * @var float
     */
    private $weight;

    /**
     * @param Node $from
     */
    public function setFrom(Node $from)
    {
        $this->from = $from;
    }

    /**
     * @param Node $to
     */
    public function setTo(Node $to)
    {
        $this->to = $to;
    }

    /**
     * @param float $weight
     */
    public function setWeight(float $weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return Node
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return Node
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @return string
     */
    public function getName() :string
    {
        return sprintf(
            '%s->%s',
            !empty($this->from) ? $this->from->getName() : '',
            !empty($this->to) ? $this->to->getName() : ''
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
