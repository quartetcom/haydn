<?php
namespace Quartet\Haydn\IO;

interface SourceInterface extends \IteratorAggregate, \Countable
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

    /**
     * @return ColumnMapperInterface
     */
    public function getColumnMapper();

    /**
     * @param ColumnMapperInterface $mapper
     */
    public function setColumnMapper(ColumnMapperInterface $mapper);

    /**
     * @return bool
     */
    public function getSkipIllegalRow();

    /**
     * @param bool $skip
     */
    public function setSkipIllegalRow($skip);
}
