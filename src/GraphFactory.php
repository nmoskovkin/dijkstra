<?php
declare(strict_types=1);

namespace Dijkstra;

class GraphFactory
{
    /**
     * @var array|Node[]
     */
    private static $nodes = array();

    /**
     * @param array $matrix
     *
     * @return Graph
     */
    public static function createByMatrix(array $matrix)
    {
        // Count of the first row
        $ribsCount = count(reset($matrix));

        for ($colNumber = 0; $colNumber < $ribsCount; $colNumber++) {
            $from = $to = $weight = null;

            // To get rib data from a column
            for ($rowNumber = 0; $rowNumber < count($matrix); $rowNumber++) {
                $val = $matrix[$rowNumber][$colNumber];
                if ($val === 0) {
                    continue;
                }
                if ($val > 0) {
                    // The beginning of a rib
                    $from = $rowNumber + 1;
                    $weight = $val;
                }
                if ($val < 0) {
                    // Point at
                    $to = $rowNumber + 1;
                }
            }

            if (!$from || !$to || !$weight) {
                continue;
            }

            // To create a node and a rib
            if (!isset(self::$nodes[$from])) {
                self::$nodes[$from] = new Node();
                self::$nodes[$from]->setName((string)$from);
            }

            if (!isset(self::$nodes[$to])) {
                self::$nodes[$to] = new Node();
                self::$nodes[$to]->setName((string)$to);
            }

            $rib = new Rib();
            $rib->setFrom(self::$nodes[$from]);
            $rib->setTo(self::$nodes[$to]);
            $rib->setWeight((float)$weight);

            self::$nodes[$from]->addOutgoing($rib);
            self::$nodes[$to]->addIncoming($rib);
        }

        return new Graph(self::$nodes[1]);
    }
}
