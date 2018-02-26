<?php
declare(strict_types=1);

namespace Dijkstra;

class Path
{
    /**
     * @var string
     */
    private $target;

    /**
     * @var array|Rib[]
     */
    private $parts;

    /**
     * @param string $target
     * @param array $parts
     */
    public function __construct(string $target, array $parts)
    {
        $this->target = $target;
        $this->parts = $parts;
    }

    /**
     * @return array|Rib[]
     */
    public function getParts(): array
    {
        return $this->parts;
    }

    /**
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * @return float
     */
    public function getDistance(): float
    {
        $result = 0.0;
        foreach ($this->parts as $rib) {
            $result += $rib->getWeight();
        }

        return $result;
    }
}
