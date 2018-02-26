<?php
declare(strict_types=1);

namespace Dijkstra\Tests;

use Dijkstra\GraphFactory;
use Dijkstra\Node;
use Dijkstra\Rib;
use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    /**
     * @param Node $node
     *
     * @return array
     */
    private function getAllNodesAndRibs(Node $node)
    {
        $nodes = $ribs = [];

        $nodes[$node->getName()] = $node;
        foreach ($node->getOutgoing() as $rib) {
            $childNode = $rib->getTo();

            $ribs[] = $rib;
            [$n, $r] = $this->getAllNodesAndRibs($childNode);

            $nodes = array_merge($nodes, $n);
            $ribs = array_merge($ribs, $r);
        }

        return [array_unique($nodes), array_unique($ribs)];
    }

    /**
     * Tests the initial node name and count of ribs
     */
    public function testInitialNode()
    {
        $matrix = [
            [2, 3, 5, 0, 0],
            [-2, 0, 0, 0, 7],
            [0, -3, 0, 9, 0],
            [0, 0, -5, -9, -7]
        ];

        $graph = GraphFactory::createByMatrix($matrix);
        $this->assertEquals('1', $graph->getInitialNode()->getName());
        $this->assertEquals(3, count($graph->getInitialNode()->getOutgoing()));
        $this->assertEquals(0, count($graph->getInitialNode()->getIncoming()));
    }

    /**
     * Tests nodes count
     */
    public function testNodesCount()
    {
        $matrix = [
            [2, 3, 5, 0, 0],
            [-2, 0, 0, 0, 7],
            [0, -3, 0, 9, 0],
            [0, 0, -5, -9, -7]
        ];

        $graph = GraphFactory::createByMatrix($matrix);
        $result = $this->getAllNodesAndRibs($graph->getInitialNode());
        $this->assertEquals(4, count($result[0]));
    }

    /**
     * Tests ribs count
     */
    public function testRibsCount()
    {
        $matrix = [
            [2, 3, 5, 0, 0],
            [-2, 0, 0, 0, 7],
            [0, -3, 0, 9, 0],
            [0, 0, -5, -9, -7]
        ];

        $graph = GraphFactory::createByMatrix($matrix);
        $result = $this->getAllNodesAndRibs($graph->getInitialNode());
        $this->assertEquals(5, count($result[1]));
    }

    /**
     * Tests ribs count
     */
    public function testRibWeights()
    {
        $matrix = [
            [2, 3, 5, 0, 0],
            [-2, 0, 0, 0, 7],
            [0, -3, 0, 9, 0],
            [0, 0, -5, -9, -7]
        ];

        $graph = GraphFactory::createByMatrix($matrix);
        $result = $this->getAllNodesAndRibs($graph->getInitialNode());

        $weightExpected = [2.0, 3.0, 5.0, 9.0, 7.0];
        $weights = [];
        /** @var Rib $rib */
        foreach ($result[1] as $rib) {
            $weights[] = $rib->getWeight();
        }

        sort($weightExpected);
        sort($weights);
        $this->assertEquals($weightExpected, $weights);
    }

    /**
     * Tests ribs
     */
    public function testRibs()
    {
        $matrix = [
            [2, 3, 5, 0, 0],
            [-2, 0, 0, 0, 7],
            [0, -3, 0, 9, 0],
            [0, 0, -5, -9, -7]
        ];

        $graph = GraphFactory::createByMatrix($matrix);
        $result = $this->getAllNodesAndRibs($graph->getInitialNode());

        $ribsExpected = ['1->2', '1->3', '1->4', '3->4', '2->4'];
        $ribs = [];
        /** @var Rib $rib */
        foreach ($result[1] as $rib) {
            $ribs[] = sprintf('%s->%s', $rib->getFrom()->getName(), $rib->getTo()->getName());
        }

        sort($ribsExpected);
        sort($ribs);
        $this->assertEquals($ribsExpected, $ribs);
    }
}
