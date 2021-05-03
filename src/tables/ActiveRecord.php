<?php
/**
 * @package Magnate
 * @author Dmitry Shumilin (chirontex@yandex.ru)
 */
namespace Magnate\Tables;

use Magnate\Interfaces\ActiveRecordInterface;
use Magnate\Interfaces\ActiveRecordSelectInterface;
use Magnate\Exceptions\ActiveRecordException;
use wpdb;

/**
 * @abstract
 * Active Record implementation.
 * @since 0.0.5
 */
abstract class ActiveRecord implements ActiveRecordInterface
{

    /**
     * @var array $ar_fields_types
     * Contains types of the fields.
     * @since 0.0.8
     */
    protected $ar_fields_types = [];

    /**
     * @var array $ar_fields_values
     * Contains fields values.
     * @since 0.1.0
     */
    protected $ar_fields_values = [];

    /**
     * @since 0.0.7
     */
    public function __set($name, $value)
    {
        
        if (is_int($value)) $type = '%d';
        elseif (is_float($value)) $type = '%f';
        else {
            
            $type = '%s';

            $value = (string)$value;
        
        }

        $this->ar_fields_types[$name] = $type;
        $this->ar_fields_values[$name] = $value;

    }

    /**
     * @since 0.1.0
     */
    public function __get($name)
    {
        
        return isset($this->ar_fields_values[$name]) ?
            $this->ar_fields_values[$name] : NULL;

    }

    /**
     * Returns WPDB global singleton link.
     * @since 0.0.5
     * 
     * @return wpdb
     */
    protected static function wpdb() : wpdb
    {

        global $wpdb;

        if (!($wpdb instanceof wpdb)) throw new ActiveRecordException(
            ActiveRecordException::pickMessage(
                ActiveRecordException::NOT_WPDB
            ),
            ActiveRecordException::pickCode(
                ActiveRecordException::NOT_WPDB
            )
        );

        return $wpdb;

    }

    /**
     * @since 0.0.5
     */
    public static function tableName() : string
    {

        return '';

    }

    /**
     * @since 0.0.5
     */
    public static function find(int $id) : self
    {

        $wpdb = self::wpdb();

        $table_name = static::tableName();

        $select = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT *
                    FROM `".$wpdb->prefix.$table_name."` AS t
                    WHERE t.id = %d",
                $id
            ),
            ARRAY_A
        );

        if (empty($select)) throw new ActiveRecordException(
            sprintf(ActiveRecordException::pickMessage(
                ActiveRecordException::NOT_FOUND
            ), 'Entry'),
            ActiveRecordException::pickCode(
                ActiveRecordException::NOT_FOUND
            )
        );

        $obj = new static;

        foreach ($select[0] as $key => $value) {

            $obj->$key = $value;

        }

        return $obj;

    }

    /**
     * @since 0.0.8
     */
    public function refresh() : self
    {

        return $this->find($this->id);

    }

    /**
     * @since 0.0.8
     */
    public function save() : self
    {

        $types = array_values($this->ar_fields_types);

        $wpdb = $this->wpdb();

        if ($wpdb->replace(
            $wpdb->prefix.$this->tableName(),
            $this->ar_fields_values,
            $types
        ) === false) throw new ActiveRecordException(
            sprintf(ActiveRecordException::pickMessage(
                ActiveRecordException::ENTRY
            ), 'insertion or updating'),
            ActiveRecordException::pickCode(
                ActiveRecordException::ENTRY
            )
        );

        return $this;

    }

    /**
     * @since 0.0.8
     */
    public static function where(array $conditions) : ActiveRecordSelectInterface
    {

        return (new ActiveRecordSelect(static::class))->where($conditions);

    }

    /**
     * @since 0.0.8
     */
    public static function order(array $conditions) : ActiveRecordSelectInterface
    {

        return (new ActiveRecordSelect(static::class))->order($conditions);

    }

    /**
     * @since 0.0.8
     */
    public static function limit(int $limit) : ActiveRecordSelectInterface
    {

        return (new ActiveRecordSelect(static::class))->limit($limit);

    }

}
