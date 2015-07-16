<?php
namespace Haydn\Examples;

use Quartet\Haydn\IO\Source\SingleColumnArraySource;
use Quartet\Haydn\IO\Source\SingleRowSource;
use Quartet\Haydn\Matcher\Matcher;
use Quartet\Haydn\Set;
use Quartet\Haydn\SetInterface;

class Permutation
{
    public function generate($indexes, $max = null)
    {
        $max = ($max === null) ? count($indexes) : $max;
        return $this->make($indexes, 0, $max);
    }

    private function make($sourceIndexes, $count, $max)
    {
        if ($count === $max) return new Set\IdenticalSet();

        $sourceSet = new Set(new SingleColumnArraySource($count, $sourceIndexes));
        return new Set\GroupingSet($sourceSet, null, function($currentRow) use ($sourceSet, $count, $max) {
            $rowSet = new Set(new SingleRowSource('r'.$count, $currentRow));

            $restSourceSet = $sourceSet->filter(new Matcher([$count => function ($value) use ($currentRow, $count) {
                return !in_array($value, $currentRow, true);
            }]));

            $restSet = $this->make($this->setToSimpleArray($restSourceSet), $count + 1, $max);

            return $rowSet->product($restSet);
        }, null);
    }

    private function setToSimpleArray(SetInterface $set)
    {
        $result = [];
        foreach ($set as $row)
        {
            $result[] = array_shift($row);
        }

        return $result;
    }
}
