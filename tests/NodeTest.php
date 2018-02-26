<?php
declare(strict_types=1);

namespace Dijkstra\Tests;

use Dijkstra\Exceptions\FailedToAttachRibException;
use Dijkstra\Node;
use Dijkstra\Rib;
use PHPUnit\Framework\TestCase;

class NodeTest extends TestCase
{
    /**
     * Tests that a node will throw an exception if we try to attach a wrong rib with a different from field
     */
    public function testOutgoingException()
    {
        $this->expectException(FailedToAttachRibException::class);

        $node1 = new Node();
        $node1->setName('1');
        $node2 = new Node();
        $node2->setName('2');
        $node3 = new Node();
        $node3->setName('3');

        $wrongRib = new Rib();
        $wrongRib->setFrom($node1);
        $wrongRib->setTo($node2);
        $node3->addOutgoing($wrongRib);
    }

    /**
     * Is similar to testOutgoingException, but tests "to" field
     */
    public function testIncomingException()
    {
        $this->expectException(FailedToAttachRibException::class);

        $node1 = new Node();
        $node1->setName('1');
        $node2 = new Node();
        $node2->setName('2');
        $node3 = new Node();
        $node3->setName('3');

        $wrongRib = new Rib();
        $wrongRib->setFrom($node1);
        $wrongRib->setTo($node2);
        $node3->addIncoming($wrongRib);
    }

    /**
     * Tests that a rib being added to the outgoing field
     */
    public function testOutgoing()
    {
        $node1 = new Node();
        $node1->setName('1');
        $node2 = new Node();
        $node2->setName('2');

        $wrongRib = new Rib();
        $wrongRib->setFrom($node1);
        $wrongRib->setTo($node2);
        $node1->addOutgoing($wrongRib);

        $this->assertCount(1, $node1->getOutgoing());
    }

    /**
     * Is similar to testOutgoing but tests incoming field
     */
    public function testIncoming()
    {
        $node1 = new Node();
        $node1->setName('1');
        $node2 = new Node();
        $node2->setName('2');

        $wrongRib = new Rib();
        $wrongRib->setFrom($node1);
        $wrongRib->setTo($node2);
        $node2->addIncoming($wrongRib);

        $this->assertCount(1, $node2->getIncoming());
    }
}
