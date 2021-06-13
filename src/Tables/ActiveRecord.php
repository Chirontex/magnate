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
     * 
     * @throws Magnate\Exceptions\ActiveRecordException
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
     * Return raw data fro wpdb::get_results().
     * @since 0.9.2
     * 
     * @param int $id
     * Entry ID.
     * 
     * @return array
     */
    protected static function getRawData(int $id) : array
    {

        $wpdb = static::wpdb();

        $table_name = static::tableName();

        $primary_column = static::getPrimaryFieldName();

        $select = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT *
                    FROM `".$wpdb->prefix.$table_name."` AS t
                    WHERE t.".$primary_column." = %d",
                $id
            ),
            ARRAY_A
        );

        if (empty($select)) return [];
        else return $select[0];

    }

    /**
     * @since 0.0.5
     */
    abstract public static function tableName() : string;

    /**
     * @since 0.9.6
     */
    public static function getPrimaryFieldName() : string
    {

        $wpdb = static::wpdb();

        $table_name = static::tableName();

        $column_name = $wpdb->get_var(
            "SELECT t.COLUMN_NAME
                FROM information_schema.KEY_COLUMN_USAGE AS t
                WHERE t.CONSTRAINT_SCHEMA = '".DB_NAME."'
                AND t.CONSTRAINT_NAME = 'PRIMARY'
                AND t.TABLE_SCHEMA = '".DB_NAME."'
                AND t.TABLE_NAME = '".$wpdb->prefix.$table_name."'"
        );

        return (string)$column_name;

    }

    /**
     * @since 0.0.5
     * 
     * @throws Magnate\Exceptions\ActiveRecordException
     */
    public static function find(int $id) : self
    {

        $data = static::getRawData($id);

        if (empty($data)) throw new ActiveRecordException(
            sprintf(ActiveRecordException::pickMessage(
                ActiveRecordException::NOT_FOUND
            ), 'Entry'),
            ActiveRecordException::pickCode(
                ActiveRecordException::NOT_FOUND
            )
        );

        $obj = new static;

        foreach ($data as $key => $value) {

            $obj->$key = $value;

        }

        return $obj;

    }

    /**
     * @since 0.0.8
     */
    public function refresh() : self
    {

        $data = $this->getRawData((int)$this->id);

        if (empty($data)) throw new ActiveRecordException(
            sprintf(ActiveRecordException::pickMessage(
                ActiveRecordException::NOT_FOUND
            ), 'Entry'),
            ActiveRecordException::pickCode(
                ActiveRecordException::NOT_FOUND
            )
        );

        $this->ar_fields_types = [];
        $this->ar_fields_values = [];

        foreach ($data as $key => $value) {

            $this->$key = $value;

        }

        return $this;

    }

    /**
     * @since 0.0.8
     * 
     * @throws Magnate\Exceptions\ActiveRecordException
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
     * @since 0.9.3
     * 
     * @throws Magnate\Exceptions\ActiveRecordException
     */
    public function delete() : self
    {

        $id = (int)$this->id;

        if (empty($id)) throw new ActiveRecordException(
            sprintf(ActiveRecordException::pickMessage(
                ActiveRecordException::EMPTY
            ), 'Entry ID'),
            ActiveRecordException::pickCode(ActiveRecordException::EMPTY)
        );

        $data = $this->getRawData($id);

        if (empty($data)) throw new ActiveRecordException(
            sprintf(ActiveRecordException::pickMessage(
                ActiveRecordException::NOT_FOUND
            ), 'Entry'),
            ActiveRecordException::pickCode(ActiveRecordException::NOT_FOUND)
        );

        $wpdb = $this->wpdb();

        $primary_column = static::getPrimaryFieldName();

        if (empty($wpdb->delete(
            $wpdb->prefix.$this->tableName(),
            [
                $primary_column => $id
            ],
            ['%d']
        ))) throw new ActiveRecordException(
            sprintf(ActiveRecordException::pickMessage(
                ActiveRecordException::ENTRY
            ), 'deleting'),
            ActiveRecordException::pickCode(ActiveRecordException::ENTRY)
        );

        unset($this->ar_fields_types[$primary_column]);
        unset($this->ar_fields_values[$primary_column]);

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
     * @since 0.9.2
     */
    public static function escape(string $character) : ActiveRecordSelectInterface
    {

        return (new ActiveRecordSelect(static::class))->escape($character);

    }

    /**
     * @since 0.8.4
     */
    public static function groupBy(array $conditions) : ActiveRecordSelectInterface
    {

        return (new ActiveRecordSelect(static::class))->groupBy($conditions);

    }

    /**
     * @since 0.8.4
     */
    public static function having(array $conditions) : ActiveRecordSelectInterface
    {

        return (new ActiveRecordSelect(static::class))->having($conditions);

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
