<?php
declare(strict_types=1);

use Dijkstra\GraphFactory;
use Dijkstra\Helpers\Matrix;
use Dijkstra\Path;
use Dijkstra\Rib;
use Dijkstra\Solver;

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Usage:
 *   php run.php -f <path>
 * Output:
 *   Node1 => Distance(path)
 *   Node2 => Distance(path)
 *   Node3 => Distance(path)
 * ...
 */

$options = getopt('f:');

if (empty($options['f'])) {
    die(sprintf("%s -f <path>\n", $argv[0]));
}

if (!is_file($options['f'])) {
    die(sprintf("File %s doesn't exist\n", $options['f']));
}

$input = json_decode(file_get_contents($options['f']), true);
$parseErrors = json_last_error();

if ($parseErrors !== JSON_ERROR_NONE) {
    die(sprintf("Failed to parse the file %s, error: %s\n", $options['f'], $parseErrors));
}

$matrix = new Matrix();
if (!$matrix->validateMatrix($input)) {
    die("Input data is not valid\n");
}

$graph = GraphFactory::createByMatrix($input);
$solver = new Solver();
$result = $solver->solve($graph);

// Output
echo sprintf("1 => 0\n");
/** @var Path $v */
foreach ($result as $v) {
    $parts = array_map(
        function (Rib $p) {
            return $p->getName();
        },
        $v->getParts()
    );

    echo sprintf("%s => %f(%s)\n", $v->getTarget(), $v->getDistance(), implode(',', $parts));
}
