<?php
declare(strict_types=1);

namespace Dijkstra\Helpers;

class Matrix
{
    /**
     * Validate a matrix
     *
     * @param array $matrix
     *
     * @return bool
     */
    public function validateMatrix($matrix): bool
    {
        if (!is_array($matrix)) {
            return false;
        }

        if (count($matrix) < 2) {
            return false;
        }

        // For the purpose to check length of each row is the same
        $rowLengthMap
            // Each column has to have 0 as the sum of its items
            = $columnItemSum
            // Column's size must be equal to 2
            = $columnItemCount
            // Try to find ribs which are between the same nodes
            = $ribs
            = [];

        /**
         * To calculate the sum and count items in each column,
         * to write count characters in row
         */
        foreach ($matrix as $rowNumber => $row) {
            $rightCharactersCount = 0;

            foreach ($row as $columnNumber => $value) {
                if (!is_numeric($value)) {
                    continue;
                }

                $rightCharactersCount++;

                if ($value === 0) {
                    continue;
                }

                if (!isset($columnItemSum[$columnNumber])) {
                    $columnItemSum[$columnNumber] = $columnItemCount[$columnNumber] = 0;
                    $ribs[$columnNumber] = [];
                }

                // includes the sum of items for each column
                $columnItemSum[$columnNumber] += $value;

                //includes the count of items of each column
                $columnItemCount[$columnNumber]++;

                $ribs[$columnNumber][] = $rowNumber;
            }

            $rowLengthMap[$rightCharactersCount] = 'foo';
        }

        // To check that there are no rows with a different size than others
        if (count($rowLengthMap) !== 1) {
            return false;
        }
        $rowLengthMap = array_keys($rowLengthMap);
        $columnCount = $rowLengthMap[0];

        // This might be 0 if each row has only wrong characters
        if (empty($columnCount)) {
            return false;
        }

        $ribsMap = [];
        // To check each column sum and count
        for ($i = 0; $i < $columnCount; $i++) {
            if (!isset($columnItemSum[$i])
                || !isset($columnItemCount[$i])
                || !isset($ribs[$i])
                || $columnItemSum[$i] !== 0
                || $columnItemCount[$i] !== 2
            ) {
                return false;
            }

            // Try to found the similar ribs
            $ribKey = implode('_', $ribs[$i]);
            if (isset($ribsMap[$ribKey])) {
                return false;
            }

            $ribsMap[$ribKey] = true;
        }

        return true;
    }
}
