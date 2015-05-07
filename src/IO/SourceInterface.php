<?php
namespace Quartet\Haydn\IO;

interface SourceInterface extends \IteratorAggregate
{
    /**
     * Get name of this source specified with constructor args.
     * @return string
     */
    public function getName();

    /**
     * Rewind iterator.
     */
    public function rewind();

    /**
     * Gets all records
     * @return array
     */
    public function toArray();

    /**
     * @param $prefixing
     * @return mixed
     */
    public function setPrefixing($prefixing);
}
