<?php
/**
 * @package Magnate
 * @author Dmitry Shumilin (chirontex@yandex.ru)
 */
namespace Magnate\Interfaces;

use Magnate\Tables\ActiveRecordJoinEnum;

/**
 * ActiveRecord interface.
 * @since 0.0.6
 */
interface ActiveRecordInterface extends ActiveRecordCollectionMemberInterface
{

    /**
     * @since 0.9.8
     */
    public function __set($name, $value);

    /**
     * @since 0.9.8
     */
    public function __get($name);

    /**
     * Return table name.
     * @since 0.0.7
     * 
     * @return string
     */
    public static function tableName() : string;

    /**
     * Return primary field name.
     * @since 0.9.6
     * 
     * @return string
     */
    public static function getPrimaryFieldName() : string;

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
     * Delete an entry.
     * @since 0.9.3
     * 
     * @return $this
     * 
     * @throws Magnate\Exceptions\ActiveRecordException
     */
    public function delete() : self;

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
     * Create an ActiveRecordSelect instance and calls its join() method.
     * @since 0.9.8
     * 
     * @param string $ActiveRecord
     * ActiveRecord model.
     * 
     * @param string $on
     * ON condition.
     * 
     * @param string $type
     * JOIN type.
     * 
     * @return ActiveRecordInterface
     */
    public static function join(string $ActiveRecord, string $on = "ON t0.id = t%d.t0_id", string $type = ActiveRecordJoinEnum::INNER_JOIN) : ActiveRecordSelectInterface;

    /**
     * Create an ActiveRecordSelect instance and calls its innerJoin() method.
     * @since 0.9.8
     * 
     * @param string $ActiveRecord
     * ActiveRecord model.
     * 
     * @param string $on
     * ON condition.
     * 
     * @return ActiveRecordInterface
     */
    public static function innerJoin(string $ActiveRecord, string $on = "ON t0.id = t%d.t0_id") : ActiveRecordSelectInterface;

    /**
     * Create an ActiveRecordSelect instance and calls its rightJoin() method.
     * @since 0.9.8
     * 
     * @param string $ActiveRecord
     * ActiveRecord model.
     * 
     * @param string $on
     * ON condition.
     * 
     * @return ActiveRecordInterface
     */
    public static function rightJoin(string $ActiveRecord, string $on = "ON t0.id = t%d.t0_id") : ActiveRecordSelectInterface;

    /**
     * Create an ActiveRecordSelect instance and calls its leftJoin() method.
     * @since 0.9.8
     * 
     * @param string $ActiveRecord
     * ActiveRecord model.
     * 
     * @param string $on
     * ON condition.
     * 
     * @return ActiveRecordInterface
     */
    public static function leftJoin(string $ActiveRecord, string $on = "ON t0.id = t%d.t0_id") : ActiveRecordSelectInterface;

    /**
     * Create an ActiveRecordSelect instance and calls its crossJoin() method.
     * @since 0.9.8
     * 
     * @param string $ActiveRecord
     * ActiveRecord model.
     * 
     * @param string $on
     * ON condition.
     * 
     * @return ActiveRecordInterface
     */
    public static function crossJoin(string $ActiveRecord, string $on = "ON t0.id = t%d.t0_id") : ActiveRecordSelectInterface;

    /**
     * Create an ActiveRecordSelect instance and calls its escape() method.
     * @since 0.9.2
     * 
     * @param string $character
     * Escaping character.
     * 
     * @return ActiveRecordSelectInterface
     */
    public static function escape(string $character) : ActiveRecordSelectInterface;

    /**
     * Create an ActiveRecordSelect instance and calls its groupBy() method.
     * @since 0.8.4
     * 
     * @param array $conditions
     * GROUP BY conditions.
     * 
     * @return ActiveRecordSelectInterface
     */
    public static function groupBy(array $conditions) : ActiveRecordSelectInterface;

    /**
     * Create an ActiveRecordSelect instance and calls its having() method.
     * @since 0.8.4
     * 
     * @param array $conditions
     * HAVING conditions.
     * 
     * @return ActiveRecordSelectInterface
     */
    public static function having(array $conditions) : ActiveRecordSelectInterface;

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
