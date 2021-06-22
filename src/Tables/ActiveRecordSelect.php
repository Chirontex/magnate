<?php
/**
 * @package Magnate
 * @author Dmitry Shumilin (chirontex@yandex.ru)
 */
namespace Magnate\Tables;

use Magnate\Interfaces\ActiveRecordSelectInterface;
use Magnate\Interfaces\ActiveRecordInterface;
use Magnate\Interfaces\ActiveRecordCollectionMemberInterface;
use Magnate\Interfaces\ActiveRecordCollectionInterface;
use Magnate\Exceptions\ActiveRecordSelectException;
use Magnate\Tables\ActiveRecordJoinEnum;
use Magnate\Tables\ActiveRecordCollectionMemberTrait;
use wpdb;

/**
 * ActiveRecord select class.
 * @since 0.0.6
 */
class ActiveRecordSelect implements ActiveRecordSelectInterface
{

    /**
     * @var wpdb $wpdb
     * WPDB global singleton.
     * @since 0.0.7
     */
    protected $wpdb;

    /**
     * @var string $class
     * ActiveRecord class name.
     * @since 0.0.6
     */
    protected $class = '';

    /**
     * @var string $where_cond
     * Selecting conditions.
     * @since 0.0.7
     */
    protected $where_cond = '';

    /**
     * @var array[] $join_conds
     * Join conditions.
     * @since 0.9.8
     */
    protected $join_conds = [];

    /**
     * @var string $escape_cond
     * ESCAPE expression.
     * @since 0.9.2
     */
    protected $escape_exp = '';

    /**
     * @var string $group_cond
     * Group conditions.
     * @since 0.8.4
     */
    protected $group_cond = '';

    /**
     * @var string $having_cond
     * Having conditions.
     * @since 0.8.4
     */
    protected $having_cond = '';

    /**
     * @var string $order_cond
     * Order conditions.
     * @since 0.0.7
     */
    protected $order_cond = '';

    /**
     * @var string $limit_cond
     * Limit condition.
     * @since 0.0.7
     */
    protected $limit_cond = '';

    /**
     * @since 0.0.6
     */
    public function __construct(string $class)
    {

        global $wpdb;

        if (!($wpdb instanceof wpdb)) throw new ActiveRecordSelectException(
            ActiveRecordSelectException::pickMessage(
                ActiveRecordSelectException::NOT_WPDB
            ),
            ActiveRecordSelectException::pickCode(
                ActiveRecordSelectException::NOT_WPDB
            )
        );

        $this->wpdb = $wpdb;
        
        $e = $this->isActiveRecord($class);

        if ($e !== null) throw $e;

        $this->class = $class;
        
    }

    /**
     * @since 0.0.7
     */
    public function where(array $conditions) : self
    {

        $where = "";

        foreach ($conditions as $condition) {

            $where .= empty($where) ? " WHERE" : " OR";

            $keys = array_keys($condition);

            for ($i = 0; $i < count($condition); $i++) {

                $key = $keys[$i];

                $where .= $i === 0 ? "" : " AND";

                if (is_array($condition[$key])) {

                    if (isset($condition[$key]['table'])) {

                        if (!is_int($condition[$key]['table'])) {
                            
                            throw new ActiveRecordSelectException(
                                sprintf(
                                    ActiveRecordSelectException::pickMessage(
                                        ActiveRecordSelectException::NOT_TYPE
                                    ), "Table key", "integer"
                                ),
                                ActiveRecordSelectException::pickCode(
                                    ActiveRecordSelectException::NOT_TYPE
                                )
                            );
                        
                        }

                        $table_key = "t".$condition[$key]['table'].".";

                    } else $table_key = "t0.";

                    if (isset($condition[$key]['condition']) &&
                        isset($condition[$key]['value'])) $where .=
                            " ".$table_key.$key." ".$this->wpdb->prepare(
                                $condition[$key]['condition'],
                                $condition[$key]['value']
                            );
                    else {

                        foreach ($condition[$key] as $cond) {

                            $where .= $cond;

                            break;

                        }

                    }

                } else $where .= $condition[$key];

            }

        }

        $this->where_cond = $where;

        return $this;

    }

    /**
     * @since 0.9.8
     */
    public function join(string $ActiveRecord, string $on = "ON t0.id = t%d.t0_id", string $type = ActiveRecordJoinEnum::INNER_JOIN) : self
    {

        $e = $this->isActiveRecord($ActiveRecord);

        if ($e !== null) throw $e;

        if (array_search($type, ActiveRecordJoinEnum::JOIN_TYPES) ===
            false) throw new ActiveRecordSelectException(
                sprintf(ActiveRecordSelectException::pickMessage(
                    ActiveRecordSelectException::NOT_TYPE
                ), 'The type', 'join type'),
                ActiveRecordSelectException::pickCode(
                    ActiveRecordSelectException::NOT_TYPE
                )
            );

        $count_joins = count($this->join_conds);

        $this->join_conds[] = [
            'model' => $ActiveRecord,
            'on' => sprintf($on, $count_joins === 0 ? 1 : $count_joins),
            'type' => $type
        ];

        return $this;

    }

    /**
     * @since 0.9.8
     */
    public function innerJoin(string $ActiveRecord, string $on = "ON t0.id = t%d.t0_id") : self
    {

        return $this->join($ActiveRecord, $on, ActiveRecordJoinEnum::INNER_JOIN);

    }

    /**
     * @since 0.9.8
     */
    public function fullJoin(string $ActiveRecord, string $on = "ON t0.id = t%d.t0_id") : self
    {

        return $this->join($ActiveRecord, $on, ActiveRecordJoinEnum::FULL_JOIN);

    }

    /**
     * @since 0.9.8
     */
    public function rightJoin(string $ActiveRecord, string $on = "ON t0.id = t%d.t0_id") : self
    {

        return $this->join($ActiveRecord, $on, ActiveRecordJoinEnum::RIGHT_JOIN);

    }

    /**
     * @since 0.9.8
     */
    public function leftJoin(string $ActiveRecord, string $on = "ON t0.id = t%d.t0_id") : self
    {

        return $this->join($ActiveRecord, $on, ActiveRecordJoinEnum::LEFT_JOIN);

    }

    /**
     * @since 0.9.8
     */
    public function crossJoin(string $ActiveRecord, string $on = "ON t0.id = t%d.t0_id") : self
    {

        return $this->join($ActiveRecord, $on, ActiveRecordJoinEnum::CROSS_JOIN);

    }

    /**
     * @since 0.9.2
     */
    public function escape(string $character) : self
    {

        $this->escape_exp = $this->wpdb->prepare(" ESCAPE %s", $character);

        return $this;

    }

    /**
     * @since 0.8.4
     */
    public function groupBy(array $conditions) : self
    {

        $group = "";

        foreach ($conditions as $key) {

            $group .= empty($group) ? " GROUP BY" : ",";

            $group .= " t0.".$key;

        }

        $this->group_cond = $group;

        return $this;

    }

    /**
     * @since 0.8.4
     */
    public function having(array $conditions) : self
    {

        $having = "";

        foreach ($conditions as $key => $parts) {

            $having .= empty($having) ? " HAVING" : ",";

            $having .= " t0.".$key." ".$this->wpdb->prepare(
                $parts['condition'], $parts['value']
            );

        }

        $this->having_cond = $having;

        return $this;

    }

    /**
     * @since 0.0.7
     */
    public function order(array $conditions) : self
    {

        $order = "";

        foreach ($conditions as $key => $cond) {

            $order .= empty($order) ? " ORDER BY" : ",";

            $order .= " t0.".$key." ".$cond;

        }

        $this->order_cond = $order;

        return $this;

    }

    /**
     * @since 0.0.7
     */
    public function limit(int $limit) : self
    {

        $this->limit_cond = " LIMIT ".$limit;

        return $this;

    }

    /**
     * @since 0.0.7
     */
    public function get() : ActiveRecordCollectionInterface
    {

        $select = $this->wpdb->get_results(
            "SELECT *
                FROM `".$this->wpdb->prefix.
                    $this->class::tableName()."` AS t0".
                    $this->handleJoinConditions().
                $this->where_cond.$this->escape_exp.$this->group_cond.
                    $this->having_cond.$this->order_cond.$this->limit_cond,
            ARRAY_A
        );

        $result = [];

        foreach ($select as $row) {

            $obj = empty($this->join_conds) ?
                new $this->class :
                new class implements ActiveRecordCollectionMemberInterface {
                    use ActiveRecordCollectionMemberTrait;
                };

            foreach ($row as $key => $value) {

                $obj->$key = $value;

            }

            $result[] = $obj;

        }

        return new ActiveRecordCollection($result);

    }

    /**
     * @since 0.0.7
     */
    public function all() : array
    {

        return $this->get()->all();

    }

    /**
     * @since 0.0.7
     */
    public function first() : ActiveRecordCollectionMemberInterface
    {
 
        return $this->get()->first();

    }

    /**
     * @since 0.0.7
     */
    public function last() : ActiveRecordCollectionMemberInterface
    {

        return $this->get()->last();

    }

    /**
     * @since 0.9.5
     */
    public function getIterator() : ActiveRecordCollectionInterface
    {
        
        return $this->get();

    }

    /**
     * Checks if class exist and ActiveRecordInterface implementer.
     * @since 0.9.8
     * 
     * @param string $class
     * 
     * @return \Magnate\Exceptions\ActiveRecordSelectException|null
     */
    protected function isActiveRecord(string $class) : ?ActiveRecordSelectException
    {

        if (!class_exists($class)) $e = new ActiveRecordSelectException(
            sprintf(ActiveRecordSelectException::pickMessage(
                ActiveRecordSelectException::NOT_EXISTS
            ), $class),
            ActiveRecordSelectException::pickCode(
                ActiveRecordSelectException::NOT_EXISTS
            )
        );

        $interfaces = class_implements($class);

        if (array_search(ActiveRecordInterface::class, $interfaces) ===
            false) $e = new ActiveRecordSelectException(
                sprintf(ActiveRecordSelectException::pickMessage(
                    ActiveRecordSelectException::NOT_TYPE
                ), $class, 'Magnate\\Interfaces\\ActiveRecordInterface implementer'),
                ActiveRecordSelectException::pickCode(
                    ActiveRecordSelectException::NOT_TYPE
                )
        );

        return $e instanceof ActiveRecordSelectException ? $e : null;

    }

    /**
     * Handles join conditions.
     * 
     * @return string
     */
    protected function handleJoinConditions() : string
    {

        $result = '';

        if (!empty($this->join_conds)) {

            foreach ($this->join_conds as $key => $cond) {

                $result .= ' '.$cond['type'].
                    ' `'.$this->wpdb->prefix.$cond['model']::tableName().
                    '` AS t'.($key + 1).' '.$cond['on'];

            }

        }

        return $result;

    }

}
