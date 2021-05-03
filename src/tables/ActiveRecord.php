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
     * @since 0.0.7
     */
    public function __set($name, $value)
    {
        
        if (is_int($value)) $type = '%d';
        elseif (is_float($value)) $type = '%f';
        else $type = '%s';

        $this->ar_fields_types[$name] = $type;

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

        $table_name = self::tableName();

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

        $obj = new self;

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

        $keys = array_keys($this->ar_fields_types);

        $types = array_values($this->ar_fields_types);

        $values = [];

        foreach ($keys as $key) {

            $values[$key] = $this->$key;

        }

        $wpdb = $this->wpdb();

        if ($wpdb->replace(
            $wpdb->prefix.$this->tableName(),
            $values,
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

        return (new ActiveRecordSelect(self::class))->where($conditions);

    }

    /**
     * @since 0.0.8
     */
    public static function order(array $conditions) : ActiveRecordSelectInterface
    {

        return (new ActiveRecordSelect(self::class))->order($conditions);

    }

    /**
     * @since 0.0.8
     */
    public static function limit(int $limit) : ActiveRecordSelectInterface
    {

        return (new ActiveRecordSelect(self::class))->limit($limit);

    }

}
