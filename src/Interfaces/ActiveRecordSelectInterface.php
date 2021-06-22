<?php
/**
 * @package Magnate
 * @author Dmitry Shumilin (chirontex@yandex.ru)
 */
namespace Magnate\Interfaces;

use Magnate\Tables\ActiveRecordJoinEnum;
use IteratorAggregate;

/**
 * ActiveRecordSelect interface.
 * @since 0.0.7
 */
interface ActiveRecordSelectInterface extends IteratorAggregate
{

    /**
     * @since 0.0.7
     * 
     * @param string $class
     * ActiveRecord class name.
     */
    public function __construct(string $class);

    /**
     * Add WHERE condition.
     * @since 0.0.7
     * 
     * @param array[] $conditions
     * List of associative arrays with conditions.
     * 
     * @return $this
     */
    public function where(array $conditions) : self;

    /**
     * Add JOIN condition.
     * @since 0.9.8
     * 
     * @param string $ActiveRecord
     * ActiveRecord model class.
     * 
     * @param string $on
     * ON condition.
     * 
     * @param string $type
     * JOIN type.
     * 
     * @return $this
     * 
     * @throws \Magnate\Exceptions\ActiveRecordSelectException
     */
    public function join(string $ActiveRecord, string $on = "ON t0.id = t%d.t0_id", string $type = ActiveRecordJoinEnum::INNER_JOIN) : self;

    /**
     * Add INNER JOIN condition.
     * @since 0.9.8
     * 
     * @param string $ActiveRecord
     * ActiveRecord model class.
     * 
     * @param string $on
     * ON condition.
     * 
     * @return $this
     * 
     * @throws \Magnate\Exceptions\ActiveRecordSelectException
     */
    public function innerJoin(string $ActiveRecord, string $on = "ON t0.id = t%d.t0_id") : self;

    /**
     * Add FULL JOIN condition.
     * @since 0.9.8
     * 
     * @param string $ActiveRecord
     * ActiveRecord model class.
     * 
     * @param string $on
     * ON condition.
     * 
     * @return $this
     * 
     * @throws \Magnate\Exceptions\ActiveRecordSelectException
     */
    public function fullJoin(string $ActiveRecord, string $on = "ON t0.id = t%d.t0_id") : self;

    /**
     * Add RIGHT JOIN condition.
     * @since 0.9.8
     * 
     * @param string $ActiveRecord
     * ActiveRecord model class.
     * 
     * @param string $on
     * ON condition.
     * 
     * @return $this
     * 
     * @throws \Magnate\Exceptions\ActiveRecordSelectException
     */
    public function rightJoin(string $ActiveRecord, string $on = "ON t0.id = t%d.t0_id") : self;

    /**
     * Add LEFT JOIN condition.
     * @since 0.9.8
     * 
     * @param string $ActiveRecord
     * ActiveRecord model class.
     * 
     * @param string $on
     * ON condition.
     * 
     * @return $this
     * 
     * @throws \Magnate\Exceptions\ActiveRecordSelectException
     */
    public function leftJoin(string $ActiveRecord, string $on = "ON t0.id = t%d.t0_id") : self;

    /**
     * Add CROSS JOIN condition.
     * @since 0.9.8
     * 
     * @param string $ActiveRecord
     * ActiveRecord model class.
     * 
     * @param string $on
     * ON condition.
     * 
     * @return $this
     * 
     * @throws \Magnate\Exceptions\ActiveRecordSelectException
     */
    public function crossJoin(string $ActiveRecord, string $on = "ON t0.id = t%d.t0_id") : self;

    /**
     * Add ESCAPE expression.
     * @since 0.9.2
     * 
     * @param string $character
     * Escaping character.
     * 
     * @return $this
     */
    public function escape(string $character) : self;

    /**
     * Add GROUP BY condition.
     * @since 0.8.4
     * 
     * @param array $conditions
     * Associative array with conditions.
     * 
     * @return $this
     */
    public function groupBy(array $conditions) : self;

    /**
     * Add HAVING condition.
     * @since 0.8.4
     * 
     * @param string $conditions
     * Associative array with conditions.
     * 
     * @return $this
     */
    public function having(array $conditions) : self;

    /**
     * Add ORDER BY condition.
     * @since 0.0.7
     * 
     * @param array $conditions
     * Associative array with conditions.
     * 
     * @return $this
     */
    public function order(array $conditions) : self;

    /**
     * Add LIMIT condition.
     * @since 0.0.7
     * 
     * @param int $limit
     * Limit condition.
     * 
     * @return $this
     */
    public function limit(int $limit) : self;

    /**
     * Get the Collection after all.
     * @since 0.0.7
     * 
     * @return ActiveRecordCollection
     */
    public function get() : ActiveRecordCollectionInterface;

    /**
     * Return list of the ActiveRecordCollection members.
     * @since 0.0.7
     * 
     * @return array
     */
    public function all() : array;

    /**
     * Get first collection member.
     * @since 0.0.7
     * 
     * @return ActiveRecordInterface
     */
    public function first() : ActiveRecordCollectionMemberInterface;

    /**
     * Get last collection member.
     * @since 0.0.7
     * 
     * @return ActiveRecordInterface
     */
    public function last() : ActiveRecordCollectionMemberInterface;

}
