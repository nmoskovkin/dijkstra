<?php
declare(strict_types=1);

namespace Dijkstra;

use Dijkstra\Exceptions\FailedToAttachRibException;

class Node
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array|Rib[]
     */
    private $outgoing = [];

    /**
     * @var array|Rib[]
     */
    private $incoming = [];

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param Rib $rib
     *
     * @throws FailedToAttachRibException
     */
    public function addOutgoing(Rib $rib)
    {
        if ($rib->getFrom()->getName() !== $this->getName()) {
            throw new FailedToAttachRibException(sprintf('Failed to attach %s', $rib->getName()));
        }

        $this->outgoing[] = $rib;
    }

    /**
     * @param Rib $rib
     *
     * @throws FailedToAttachRibException
     */
    public function addIncoming(Rib $rib)
    {
        if ($rib->getTo()->getName() !== $this->getName()) {
            throw new FailedToAttachRibException(sprintf('Failed to attach %s', $rib->getName()));
        }

        $this->incoming[] = $rib;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array|Rib[]
     */
    public function getOutgoing()
    {
        return $this->outgoing;
    }

    /**
     * @return array|Rib[]
     */
    public function getIncoming()
    {
        return $this->incoming;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
