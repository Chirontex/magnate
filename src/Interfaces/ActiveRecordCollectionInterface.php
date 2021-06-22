<?php
/**
 * @package Magnate
 * @author Dmitry Shumilin (chirontex@yandex.ru)
 */
namespace Magnate\Interfaces;

use Countable;
use Iterator;

interface ActiveRecordCollectionInterface extends Countable, Iterator
{

    /**
     * Return AR collection list.
     * @since 0.0.6
     * 
     * @return ActiveRecordCollectionMemberInterface[]
     */
    public function all() : array;

    /**
     * Return first AR object.
     * @since 0.0.6
     * 
     * @return ActiveRecordCollectionMemberInterface
     */
    public function first() : ActiveRecordCollectionMemberInterface;

    /**
     * Return last AR object.
     * @since 0.0.6
     * 
     * @return ActiveRecordCollectionMemberInterface
     */
    public function last() : ActiveRecordCollectionMemberInterface;

    /**
     * Delete all AR objects.
     * @since 0.9.4
     * 
     * @return $this
     */
    public function deleteAll() : self;

}
