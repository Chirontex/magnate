<?php
/**
 * @package Magnate
 * @author Dmitry Shumilin (chirontex@yandex.ru)
 */
namespace Magnate\Tables;

use Magnate\Interfaces\ActiveRecordInterface;
use Magnate\Interfaces\ActiveRecordCollectionInterface;
use Magnate\Exceptions\ActiveRecordSelectException;
use wpdb;

/**
 * ActiveRecord select class.
 * @since 0.0.6
 */
class ActiveRecordSelect
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
        
        if (!class_exists($class)) throw new ActiveRecordSelectException(
            sprintf(ActiveRecordSelectException::pickMessage(
                ActiveRecordSelectException::NOT_EXISTS
            ), $class),
            ActiveRecordSelectException::pickCode(
                ActiveRecordSelectException::NOT_EXISTS
            )
        );

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

                $key = $this->wpdb->prepare("%s", (string)$keys[$i]);

                $where .= $i === 0 ? "" : " AND";
                $where .= " t.".$key." ";

                if (is_array($condition[$key])) {

                    if (isset($condition[$key]['condition']) &&
                        isset($condition[$key]['value'])) $where .=
                            $this->wpdb->prepare(
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
     * @since 0.0.7
     */
    public function order(array $conditions) : self
    {

        $order = "";

        foreach ($conditions as $key => $cond) {

            $key = $this->wpdb->prepare("%s", (string)$key);

            $cond = $this->wpdb->prepare("%s", (string)$cond);

            $order .= empty($order) ? " ORDER BY" : ", ";

            $order .= " t.".$key." ".$cond;

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
                    $this->class::tableName()."` AS t".
                $this->where_cond.$this->order_cond.$this->limit_cond,
            ARRAY_A
        );

        $result = [];

        foreach ($select as $row) {

            $obj = new $this->class;

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
    public function first() : ActiveRecordInterface
    {

        return $this->get()->first();

    }

    /**
     * @since 0.0.7
     */
    public function last() : ActiveRecordInterface
    {

        return $this->get()->last();

    }

}
