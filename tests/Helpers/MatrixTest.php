<?php
declare(strict_types=1);

namespace Dijkstra\Tests;

use Dijkstra\Helpers\Matrix;
use PHPUnit\Framework\TestCase;

class MatrixTest extends TestCase
{
    /**
     * Validate a matrix
     *
     * @param array $matrix
     * @param bool $expected
     *
     * @dataProvider validateMatrixProvider
     */
    public function testValidateMatrix($matrix, $expected)
    {
        $helper = new Matrix();
        $this->assertEquals($expected, $helper->validateMatrix($matrix));
    }

    /**
     * Check the return value if the argument is not array
     */
    public function testValidateMatrixNonArray()
    {
        $helper = new Matrix();
        $this->assertEquals(false, $helper->validateMatrix('123'));
    }

    /**
     * @return array
     */
    public function validateMatrixProvider()
    {
        return [
            [
                // The correct matrix
                [
                    [4, 42, 66],
                    [-4, 0, 0],
                    [0, -42, 0],
                    [0, 0, -66]
                ],
                true
            ],
            [
                // Ribs which have the same nodes
                [
                    [1, 1, 1],
                    [-1, -1, 0],
                    [0, 0, 0],
                    [0, 0, -1]
                ],
                false
            ],
            [
                // Two positive numbers in a column
                [
                    [1, 1, 1],
                    [-1, 0, 0],
                    [0, 1, 0],
                    [0, 0, -1]
                ],
                false
            ],
            [
                // Two negative numbers in a column
                [
                    [1, -1, 1],
                    [-1, 0, 0],
                    [0, -1, 0],
                    [0, 0, -1]
                ],
                false
            ],
            [
                // There is an empty column
                [
                    [1, 0, 1],
                    [-1, 0, 0],
                    [0, 0, 0],
                    [0, 0, -1]
                ],
                false
            ],
            [
                // A different row
                [
                    [1, 0, 1, 7],
                    [-1, 0, 0],
                    [0, 0, 0],
                    [0, 0, -1]
                ],
                false
            ],
            [
                // Characters
                [
                    ['a', 0, 1, 7],
                    [-1, 'b', 0],
                    [0, 0, 0],
                    [0, 0, -1]
                ],
                false
            ],
            [
                // There have to be more than one row
                [
                    [1, 0, 1]
                ],
                false
            ],
            [
                // Empty matrix
                [],
                false
            ],
        ];
    }
}
