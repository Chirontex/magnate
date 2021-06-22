<?php
/**
 * @package Magnate
 * @author Dmitry Shumilin (chirontex@yandex.ru)
 */
namespace Magnate\Tables;

use Magnate\Interfaces\ActiveRecordCollectionInterface;
use Magnate\Interfaces\ActiveRecordInterface;
use Magnate\Exceptions\ActiveRecordCollectionException;
use Magnate\Tables\SetterGetterTrait;

/**
 * ActiveRecord objs collection.
 * @since 0.0.6
 */
class ActiveRecordCollection implements ActiveRecordCollectionInterface
{

    /**
     * @var ActiveRecordInterface[] $collection
     * @since 0.0.6
     */
    protected $collection = [];

    /**
     * @var int $position
     * @since 0.9.5
     */
    protected $position = -1;

    /**
     * @since 0.0.6
     * 
     * @param ActiveRecordInterface[] $collection
     */
    public function __construct(array $collection)
    {
        
        foreach ($collection as $ar) {

            if (!($ar instanceof ActiveRecordInterface) &&
                array_search(
                    SetterGetterTrait::class,
                    class_uses($ar)
                ) === false) throw new ActiveRecordCollectionException(
                sprintf(ActiveRecordCollectionException::pickMessage(
                    ActiveRecordCollectionException::NOT_TYPE
                ), 'Collection member', ActiveRecordInterface::class),
                ActiveRecordCollectionException::pickCode(
                    ActiveRecordCollectionException::NOT_TYPE
                )
            );

        }

        $this->collection = $collection;

    }

    /**
     * @since 0.0.6
     */
    public function all() : array
    {

        return $this->collection;

    }

    /**
     * @since 0.0.6
     */
    public function first() : ActiveRecordInterface
    {

        if (empty($this->collection)) throw new ActiveRecordCollectionException(
            sprintf(ActiveRecordCollectionException::pickMessage(
                ActiveRecordCollectionException::IS_EMPTY
            ), 'Collection'),
            ActiveRecordCollectionException::pickCode(
                ActiveRecordCollectionException::IS_EMPTY
            )
        );

        return $this->collection[0];

    }

    /**
     * @since 0.0.6
     */
    public function last() : ActiveRecordInterface
    {

        if (empty($this->collection)) throw new ActiveRecordCollectionException(
            sprintf(ActiveRecordCollectionException::pickMessage(
                ActiveRecordCollectionException::IS_EMPTY
            ), 'Collection'),
            ActiveRecordCollectionException::pickCode(
                ActiveRecordCollectionException::IS_EMPTY
            )
        );

        return $this->collection[count($this->collection) - 1];

    }

    /**
     * @since 0.9.4
     */
    public function deleteAll() : self
    {

        foreach ($this->collection as $entity) {

            $entity->delete();

        }

        $this->collection = [];

        return $this;

    }

    /**
     * @since 0.9.5
     */
    public function count() : int
    {
        
        return count($this->collection);

    }

    /**
     * @since 0.9.5
     * 
     * @return int
     * If collection is empty, -1 will be returned.
     */
    public function key() : int
    {
        
        if (empty($this->collection)) $this->position = -1;

        return $this->position;

    }

    /**
     * @since 0.9.5
     */
    public function valid() : bool
    {
        
        return isset($this->collection[$this->position]);

    }

    /**
     * @since 0.9.5
     * 
     * @return ActiveRecordInterface
     */
    public function current() : ActiveRecordInterface
    {

        if (!$this->valid()) throw new ActiveRecordCollectionException(
            sprintf(ActiveRecordCollectionException::pickMessage(
                ActiveRecordCollectionException::NOT_EXISTS
            ), 'Member with \''.$this->position.'\' key'),
            ActiveRecordCollectionException::pickCode(
                ActiveRecordCollectionException::NOT_EXISTS
            )
        );
        
        return $this->collection[$this->position];

    }

    /**
     * @since 0.9.5
     */
    public function next()
    {
        
        ++$this->position;

    }

    /**
     * @since 0.9.5
     */
    public function rewind()
    {
        
        if (empty($this->collection)) $this->position = -1;
        else $this->position = 0;

    }

}
