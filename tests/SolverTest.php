<?php
declare(strict_types=1);

namespace Dijkstra\Tests;

use Dijkstra\Graph;
use Dijkstra\Node;
use Dijkstra\Solver;
use Dijkstra\Path;
use Dijkstra\Rib;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SolverTest extends TestCase
{
    /**
     * @return MockObject|Graph
     */
    private function createGraph()
    {
        /**
         * [ 2,  3,  5,  0,  0]
         * [-2,  0,  0,  0,  7]
         * [ 0, -3,  0,  1,  0]
         * [ 0,  0, -5, -1, -7]
         */
        $node1 = new Node();
        $node1->setName('1');
        $node2 = new Node();
        $node2->setName('2');
        $node3 = new Node();
        $node3->setName('3');
        $node4 = new Node();
        $node4->setName('4');

        // Ribs
        $rib12 = new Rib();
        $rib12->setFrom($node1);
        $rib12->setTo($node2);
        $rib12->setWeight(2.0);
        $node1->addOutgoing($rib12);
        $node2->addIncoming($rib12);

        $rib13 = new Rib();
        $rib13->setFrom($node1);
        $rib13->setTo($node3);
        $rib13->setWeight(3.0);
        $node1->addOutgoing($rib13);
        $node3->addIncoming($rib13);

        $rib14 = new Rib();
        $rib14->setFrom($node1);
        $rib14->setTo($node4);
        $rib14->setWeight(5.0);
        $node1->addOutgoing($rib14);
        $node4->addIncoming($rib14);

        $rib24 = new Rib();
        $rib24->setFrom($node2);
        $rib24->setTo($node4);
        $rib24->setWeight(7.0);
        $node2->addOutgoing($rib24);
        $node4->addIncoming($rib24);

        $rib34 = new Rib();
        $rib34->setFrom($node3);
        $rib34->setTo($node4);
        $rib34->setWeight(1.0);
        $node3->addOutgoing($rib34);
        $node4->addIncoming($rib34);

        $graph = $this
            ->getMockBuilder(Graph::class)
            ->setMethods(['getInitialNode'])
            ->disableOriginalConstructor()
            ->getMock();

        $graph
            ->method('getInitialNode')
            ->willReturn($node1);

        return $graph;
    }

    /**
     * @return MockObject|Graph
     */
    private function createGraph2()
    {
        /**
         * [7,  9,   14,  0,   0,   0,  0,  0,  0]
         * [-7, 0,   0,  10,  15,   0,  0,  0,  0]
         * [0, -9,   0, -10,   0,  11,  2,  0,  0]
         * [0,  0,   0,  -0, -15, -11,  0,  6,  0]
         * [0,  0,   0,  -0,  -0,   0,  0, -6, -2]
         * [0,  0, -14,  -0,  -0,   0, -2,  0,  2]
         */
        $node1 = new Node();
        $node1->setName('1');
        $node2 = new Node();
        $node2->setName('2');
        $node3 = new Node();
        $node3->setName('3');
        $node4 = new Node();
        $node4->setName('4');
        $node5 = new Node();
        $node5->setName('5');
        $node6 = new Node();
        $node6->setName('6');

        // Ribs
        $rib12 = new Rib();
        $rib12->setFrom($node1);
        $rib12->setTo($node2);
        $rib12->setWeight(7.0);
        $node1->addOutgoing($rib12);
        $node2->addIncoming($rib12);

        $rib13 = new Rib();
        $rib13->setFrom($node1);
        $rib13->setTo($node3);
        $rib13->setWeight(9.0);
        $node1->addOutgoing($rib13);
        $node3->addIncoming($rib13);

        $rib16 = new Rib();
        $rib16->setFrom($node1);
        $rib16->setTo($node6);
        $rib16->setWeight(14.0);
        $node1->addOutgoing($rib16);
        $node6->addIncoming($rib16);

        $rib23 = new Rib();
        $rib23->setFrom($node2);
        $rib23->setTo($node3);
        $rib23->setWeight(10.0);
        $node2->addOutgoing($rib23);
        $node3->addIncoming($rib23);

        $rib24 = new Rib();
        $rib24->setFrom($node2);
        $rib24->setTo($node4);
        $rib24->setWeight(15.0);
        $node2->addOutgoing($rib24);
        $node4->addIncoming($rib24);

        $rib34 = new Rib();
        $rib34->setFrom($node3);
        $rib34->setTo($node4);
        $rib34->setWeight(11.0);
        $node3->addOutgoing($rib34);
        $node4->addIncoming($rib34);

        $rib36 = new Rib();
        $rib36->setFrom($node3);
        $rib36->setTo($node6);
        $rib36->setWeight(2.0);
        $node3->addOutgoing($rib36);
        $node6->addIncoming($rib36);

        $rib45 = new Rib();
        $rib45->setFrom($node4);
        $rib45->setTo($node5);
        $rib45->setWeight(6.0);
        $node4->addOutgoing($rib45);
        $node5->addIncoming($rib45);

        $rib65 = new Rib();
        $rib65->setFrom($node6);
        $rib65->setTo($node5);
        $rib65->setWeight(2.0);
        $node6->addOutgoing($rib65);
        $node5->addIncoming($rib65);

        $graph = $this
            ->getMockBuilder(Graph::class)
            ->setMethods(['getInitialNode'])
            ->disableOriginalConstructor()
            ->getMock();

        $graph
            ->method('getInitialNode')
            ->willReturn($node1);

        return $graph;
    }

    /**
     * @return MockObject|Graph
     */
    private function createGraph3()
    {
        /**
         * [ 3, 7, 13, 23, 0,  0, 0, 0, 0,  0, 0, 0, 0, 0,  0, 0, 0,  0,  0, 0, 0]
         * [-3, 0,  0,  0, 1, 15, 6, 0, 0,  0, 0, 0, 0, 0,  0, 0, 0,  0,  0, 0, 0]
         * [ 0, 0,  0,  0,-1,  0, 0, 4, 3, 11, 0, 0, 0, 0,  0, 0, 0,  0,  0, 0, 0]
         * [ 0,-7,  0,  0, 0,-15, 0, 0, 0,  0, 2, 5, 3, 0,  0, 0, 0,  0,  0, 0, 0]
         * [ 0, 0,  0,  0, 0,  0,-6,-4, 0,  0,-2, 0, 0, 2, 12, 0, 0,  0,  0, 0, 0]
         * [ 0, 0,-13,  0, 0,  0, 0, 0, 0,  0, 0,-5, 0,-2,  0, 5, 9,  0,  0, 0, 0]
         * [ 0, 0,  0,  0, 0,  0, 0, 0,-3,  0, 0, 0,-3, 0,-12,-5, 0, 30, 13, 0, 0]
         * [ 0, 0,  0,-23, 0,  0, 0, 0, 0,  0, 0, 0, 0, 0,  0, 0,-9,-30,  0, 1, 0]
         * [ 0, 0,  0,  0, 0,  0, 0, 0, 0,-11, 0, 0, 0, 0,  0, 0, 0,  0,-13, 0, 4]
         * [ 0, 0,  0,  0, 0,  0, 0, 0, 0,  0, 0, 0, 0, 0,  0, 0, 0,  0,  0,-1,-4]
         */
        $node1 = new Node();
        $node1->setName('1');
        $node2 = new Node();
        $node2->setName('2');
        $node3 = new Node();
        $node3->setName('3');
        $node4 = new Node();
        $node4->setName('4');
        $node5 = new Node();
        $node5->setName('5');
        $node6 = new Node();
        $node6->setName('6');
        $node7 = new Node();
        $node7->setName('7');
        $node8 = new Node();
        $node8->setName('8');
        $node9 = new Node();
        $node9->setName('9');
        $node10 = new Node();
        $node10->setName('10');

        // Ribs
        $rib12 = new Rib();
        $rib12->setFrom($node1);
        $rib12->setTo($node2);
        $rib12->setWeight(3.0);
        $node1->addOutgoing($rib12);
        $node2->addIncoming($rib12);

        $rib14 = new Rib();
        $rib14->setFrom($node1);
        $rib14->setTo($node4);
        $rib14->setWeight(7.0);
        $node1->addOutgoing($rib14);
        $node4->addIncoming($rib14);

        $rib16 = new Rib();
        $rib16->setFrom($node1);
        $rib16->setTo($node6);
        $rib16->setWeight(13.0);
        $node1->addOutgoing($rib16);
        $node6->addIncoming($rib16);

        $rib18 = new Rib();
        $rib18->setFrom($node1);
        $rib18->setTo($node8);
        $rib18->setWeight(23.0);
        $node1->addOutgoing($rib18);
        $node8->addIncoming($rib18);

        $rib23 = new Rib();
        $rib23->setFrom($node2);
        $rib23->setTo($node3);
        $rib23->setWeight(1.0);
        $node2->addOutgoing($rib23);
        $node3->addIncoming($rib23);

        $rib24 = new Rib();
        $rib24->setFrom($node2);
        $rib24->setTo($node4);
        $rib24->setWeight(15.0);
        $node2->addOutgoing($rib24);
        $node4->addIncoming($rib24);

        $rib25 = new Rib();
        $rib25->setFrom($node2);
        $rib25->setTo($node5);
        $rib25->setWeight(6.0);
        $node2->addOutgoing($rib25);
        $node5->addIncoming($rib25);

        $rib35 = new Rib();
        $rib35->setFrom($node3);
        $rib35->setTo($node5);
        $rib35->setWeight(4.0);
        $node3->addOutgoing($rib35);
        $node5->addIncoming($rib35);

        $rib37 = new Rib();
        $rib37->setFrom($node3);
        $rib37->setTo($node7);
        $rib37->setWeight(3.0);
        $node3->addOutgoing($rib37);
        $node7->addIncoming($rib37);

        $rib39 = new Rib();
        $rib39->setFrom($node3);
        $rib39->setTo($node9);
        $rib39->setWeight(11.0);
        $node3->addOutgoing($rib39);
        $node9->addIncoming($rib39);

        $rib45 = new Rib();
        $rib45->setFrom($node4);
        $rib45->setTo($node5);
        $rib45->setWeight(2.0);
        $node4->addOutgoing($rib45);
        $node5->addIncoming($rib45);

        $rib46 = new Rib();
        $rib46->setFrom($node4);
        $rib46->setTo($node6);
        $rib46->setWeight(5.0);
        $node4->addOutgoing($rib46);
        $node6->addIncoming($rib46);

        $rib47 = new Rib();
        $rib47->setFrom($node4);
        $rib47->setTo($node7);
        $rib47->setWeight(3.0);
        $node4->addOutgoing($rib47);
        $node7->addIncoming($rib47);

        $rib56 = new Rib();
        $rib56->setFrom($node5);
        $rib56->setTo($node6);
        $rib56->setWeight(2.0);
        $node5->addOutgoing($rib56);
        $node6->addIncoming($rib56);

        $rib57 = new Rib();
        $rib57->setFrom($node5);
        $rib57->setTo($node7);
        $rib57->setWeight(12.0);
        $node5->addOutgoing($rib57);
        $node7->addIncoming($rib57);

        $rib67 = new Rib();
        $rib67->setFrom($node6);
        $rib67->setTo($node7);
        $rib67->setWeight(5.0);
        $node6->addOutgoing($rib67);
        $node7->addIncoming($rib67);

        $rib68 = new Rib();
        $rib68->setFrom($node6);
        $rib68->setTo($node8);
        $rib68->setWeight(9.0);
        $node6->addOutgoing($rib68);
        $node8->addIncoming($rib68);

        $rib78 = new Rib();
        $rib78->setFrom($node7);
        $rib78->setTo($node8);
        $rib78->setWeight(30);
        $node7->addOutgoing($rib78);
        $node8->addIncoming($rib78);

        $rib79 = new Rib();
        $rib79->setFrom($node7);
        $rib79->setTo($node9);
        $rib79->setWeight(13.0);
        $node7->addOutgoing($rib79);
        $node9->addIncoming($rib79);

        $rib810 = new Rib();
        $rib810->setFrom($node8);
        $rib810->setTo($node10);
        $rib810->setWeight(1.0);
        $node8->addOutgoing($rib810);
        $node10->addIncoming($rib810);

        $rib910 = new Rib();
        $rib910->setFrom($node9);
        $rib910->setTo($node10);
        $rib910->setWeight(4.0);
        $node9->addOutgoing($rib910);
        $node10->addIncoming($rib910);

        $graph = $this
            ->getMockBuilder(Graph::class)
            ->setMethods(['getInitialNode'])
            ->disableOriginalConstructor()
            ->getMock();

        $graph
            ->method('getInitialNode')
            ->willReturn($node1);

        return $graph;
    }

    /**
     * @return MockObject|Graph
     */
    private function createEmptyGraph()
    {
        $node1 = new Node();
        $graph = $this
            ->getMockBuilder(Graph::class)
            ->setMethods(['getInitialNode'])
            ->disableOriginalConstructor()
            ->getMock();

        $graph
            ->method('getInitialNode')
            ->willReturn($node1);

        return $graph;
    }

    /**
     * Tests that the class is the instance of SolverResult
     */
    public function testEmptyGraph()
    {
        $graph = $this->createEmptyGraph();
        $solver = new Solver();
        $result = $solver->solve($graph);

        $this->assertEquals([], $result);
    }

    /**
     * Tests the result has the minimum path to the target node
     */
    public function testResult()
    {
        $graph = $this->createGraph();

        $solver = new Solver();
        $result = $solver->solve($graph);

        $nodeNameMap = [];
        /** @var Path $v */
        foreach ($result as $v) {
            $path = array_map(
                function(Rib $p){
                    return $p->getName();
                },
                $v->getParts()
            );
            $nodeNameMap[$v->getTarget()] = implode(',', $path);
        }

        $this->assertFalse(isset($nodeNameMap['1']));
        $this->assertEquals('1->2', $nodeNameMap['2']);
        $this->assertEquals('1->3', $nodeNameMap['3']);
        $this->assertEquals('1->3,3->4', $nodeNameMap['4']);
    }

    /**
     * Tests the result has the minimum path to the target node
     */
    public function testResult2()
    {
        $graph = $this->createGraph2();

        $solver = new Solver();
        $result = $solver->solve($graph);

        $nodeNameMap = [];
        /** @var Path $v */
        foreach ($result as $v) {
            $path = array_map(
                function(Rib $p){
                    return $p->getName();
                },
                $v->getParts()
            );
            $nodeNameMap[$v->getTarget()] = implode(',', $path);
        }

        $this->assertFalse(isset($nodeNameMap['1']));
        $this->assertEquals('1->2', $nodeNameMap['2']);
        $this->assertEquals('1->3', $nodeNameMap['3']);
        $this->assertEquals('1->3,3->4', $nodeNameMap['4']);
        $this->assertEquals('1->3,3->6,6->5', $nodeNameMap['5']);
        $this->assertEquals('1->3,3->6', $nodeNameMap['6']);
    }

    /**
     * Tests the result has the minimum path to the target node
     */
    public function testResult3()
    {
        $graph = $this->createGraph3();

        $solver = new Solver();
        $result = $solver->solve($graph);

        $nodeNameMap = [];
        /** @var Path $v */
        foreach ($result as $v) {
            $path = array_map(
                function(Rib $p){
                    return $p->getName();
                },
                $v->getParts()
            );
            $nodeNameMap[$v->getTarget()] = implode(',', $path);
        }

        $this->assertFalse(isset($nodeNameMap['1']));
        $this->assertEquals('1->2', $nodeNameMap['2']);
        $this->assertEquals('1->2,2->3', $nodeNameMap['3']);
        $this->assertEquals('1->4', $nodeNameMap['4']);
        $this->assertEquals('1->2,2->3,3->5', $nodeNameMap['5']);
        $this->assertEquals('1->2,2->3,3->5,5->6', $nodeNameMap['6']);
        $this->assertEquals('1->2,2->3,3->7', $nodeNameMap['7']);
        $this->assertEquals('1->2,2->3,3->5,5->6,6->8', $nodeNameMap['8']);
        $this->assertEquals('1->2,2->3,3->9', $nodeNameMap['9']);
        $this->assertEquals('1->2,2->3,3->9,9->10', $nodeNameMap['10']);
    }
}
