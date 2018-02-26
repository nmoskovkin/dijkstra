<?php
declare(strict_types=1);

namespace Dijkstra;

class Graph
{
    /**
     * @var Node
     */
    private $initialNode;

    /**
     * @param Node $initialNode
     */
    public function __construct(Node $initialNode)
    {
        $this->initialNode = $initialNode;
    }

    /**
     * @return Node
     */
    public function getInitialNode()
    {
        return $this->initialNode;
    }
}
