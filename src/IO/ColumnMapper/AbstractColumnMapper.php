<?php
namespace Quartet\Haydn\IO\ColumnMapper;

use Quartet\Haydn\IO\ColumnMapperInterface;
use Quartet\Haydn\IO\SourceInterface;

abstract class AbstractColumnMapper implements ColumnMapperInterface
{
    const NAME_DELIMITER = '.';

    /**
     * @var array
     */
    protected $map;

    /**
     * @var SourceInterface
     */
    protected $source = null;

    /**
     * @var array
     */
    protected $columnNamesCache = null;

    /**
     * @var bool
     */
    protected $enablePrefix = false;

    /**
     * {@inheritdoc}
     */
    public function setMap($map)
    {
        $this->map = $map;
    }

    /**
     * @param $data
     * @return array
     */
    public function makeRow($data)
    {
        if ($this->columnNamesCache === null) {
            $this->initColumnNameCache($data);
        }

        return array_combine($this->columnNamesCache, $data);
    }

    /**
     * @param array $data
     */
    protected function initColumnNameCache($data)
    {
        $map = static::makeMap($data);

        $this->columnNamesCache = array_map(function ($name) {
            if ($this->enablePrefix) {
                return implode(self::NAME_DELIMITER, [$this->source->getName(), $name]);
            } else {
                return $name;
            }
        }, $map);
    }

    /**
     * {@inheritdoc}
     */
    public function setSource(SourceInterface $source)
    {
        if ($this->source !== null) {
            throw new \RuntimeException('Cannot reuse column mapper for multiple sources.');
        }
        $this->source = $source;
    }

    /**
     * @param $prefixing
     * @return mixed|void
     */
    public function setPrefixing($prefixing)
    {
        $this->enablePrefix = $prefixing;
    }
}