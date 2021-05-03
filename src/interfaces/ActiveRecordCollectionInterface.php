<?php
/**
 * @package Magnate
 * @author Dmitry Shumilin (chirontex@yandex.ru)
 */
namespace Magnate\Interfaces;

interface ActiveRecordCollectionInterface
{

    /**
     * Return AR collection list.
     * @since 0.0.6
     * 
     * @return ActiveRecordInterface[]
     */
    public function all() : array;

    /**
     * Return first AR object.
     * @since 0.0.6
     * 
     * @return ActiveRecordInterface
     */
    public function first() : ActiveRecordInterface;

    /**
     * Return last AR object.
     * @since 0.0.6
     * 
     * @return ActiveRecordInterface
     */
    public function last() : ActiveRecordInterface;

}
