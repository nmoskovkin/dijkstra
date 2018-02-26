<?php
declare(strict_types=1);

namespace Dijkstra;

class Solver
{
    /**
     * @var array|Path[]
     */
    private $pathToNode = array();

    /**
     * @var array
     */
    private $processedNodes = array();

    /**
     * Create a new path from the old node path adding the new rib to that.
     *
     * @param \Dijkstra\Rib $rib
     *
     * @return Path
     */
    private function createPath(Rib $rib)
    {
        /** @var Path $currentPath */
        $currentPath = $this->pathToNode[$rib->getFrom()->getName()] ?? false;
        if ($currentPath === false) {
            return new Path($rib->getTo()->getName(), [$rib]);
        }

        $pathComponents = $currentPath->getParts();
        $pathComponents[] = $rib;

        return new Path($rib->getTo()->getName(), $pathComponents);
    }

    /**
     * Process a node
     *
     * @param \Dijkstra\Node $node
     */
    private function processNode(Node $node)
    {
        if (!empty($this->processedNodes[$node->getName()])) {
            return;
        }

        foreach ($node->getOutgoing() as $rib) {
            $targetName = $rib->getTo()->getName();
            $currentPath = $this->createPath($rib);

            if (!isset($this->pathToNode[$targetName])) {
                $this->pathToNode[$targetName] = $currentPath;
            } elseif ($currentPath->getDistance() < $this->pathToNode[$targetName]->getDistance()) {
                $this->pathToNode[$targetName] = $currentPath;
            }
        }

        foreach ($node->getOutgoing() as $rib) {
            $this->processNode($rib->getTo());
        }

        $this->processedNodes[$node->getName()] = true;
    }

    /**
     * Solve a problem
     *
     * @param \Dijkstra\Graph $graph
     *
     * @return array|Path[]
     */
    public function solve(Graph $graph)
    {
        $this->processNode($graph->getInitialNode());

        return array_values($this->pathToNode);
    }
}
