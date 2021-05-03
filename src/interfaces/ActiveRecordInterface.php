<?php
/**
 * @package Magnate
 * @author Dmitry Shumilin (chirontex@yandex.ru)
 */
namespace Magnate\Interfaces;

/**
 * ActiveRecord interface.
 * @since 0.0.6
 */
interface ActiveRecordInterface
{

    /**
     * Return table name.
     * @since 0.0.7
     * 
     * @return string
     */
    public static function tableName() : string;

    /**
     * Return Active Record object by ID.
     * @since 0.0.5
     * 
     * @param int $id
     * Entry ID.
     * 
     * @return self
     */
    public static function find(int $id) : self;

    /**
     * Get an actual state of record in table.
     * @since 0.0.8
     * 
     * @return self
     */
    public function refresh() : self;

    /**
     * @since 0.0.8
     */
    public function save() : self;

    /**
     * Create an ActiveRecordSelect instance and calls its where() method.
     * @since 0.0.8
     * 
     * @param array $conditions
     * WHERE conditions.
     * 
     * @return ActiveRecordSelectInterface
     */
    public static function where(array $conditions) : ActiveRecordSelectInterface;

    /**
     * Create an ActiveRecordSelect instance and calls its order() method.
     * @since 0.0.8
     * 
     * @param array $conditions
     * ORDER BY conditions.
     * 
     * @return ActiveRecordSelectInterface
     */
    public static function order(array $conditions) : ActiveRecordSelectInterface;

    /**
     * Create an ActiveRecordSelect instance and calls its limit() method.
     * @since 0.0.8
     * 
     * @param int $limit
     * LIMIT condition.
     * 
     * @return ActiveRecordSelectInterface
     */
    public static function limit(int $limit) : ActiveRecordSelectInterface;

}
