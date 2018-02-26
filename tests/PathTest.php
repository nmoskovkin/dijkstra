<?php
declare(strict_types=1);

namespace Dijkstra\Tests;

use Dijkstra\Path;
use Dijkstra\Rib;
use PHPUnit\Framework\TestCase;

class PathTest extends TestCase
{
    /**
     * Tests calculating entire distance by path parts
     */
    public function testGetDistance()
    {
        $ribs = [];
        $rib = new Rib();
        $rib->setWeight(2);
        $ribs[] = $rib;
        $rib = new Rib();
        $rib->setWeight(4.2);
        $ribs[] = $rib;
        $rib = new Rib();
        $rib->setWeight(5.3);
        $ribs[] = $rib;

        $sr = new Path('1', $ribs);
        $this->assertTrue(abs(11.5 - $sr->getDistance()) < 0.0000001);
    }
}
