<?php
require_once __DIR__.'/../../vendor/autoload.php';

$start = microtime(true);
$input = range(1,20);
$p = new \Haydn\Examples\Permutation();
$ret = $p->generate($input,3);
printf("%f sec.\n", microtime(true) - $start);
$count = 0;
foreach ($ret as $value) {
    $count++;
    echo '[', implode(',', $value), ']', "\n";
}
printf("%d row.\n", $count);
printf("%f sec.\n", microtime(true) - $start);
