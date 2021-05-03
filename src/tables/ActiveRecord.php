<?php
/**
 * @package Magnate
 * @author Dmitry Shumilin (chirontex@yandex.ru)
 */
namespace Magnate\Tables;

use Magnate\Exceptions\ActiveRecordException;
use wpdb;

/**
 * @abstract
 * Active Record implementation.
 * @since 0.0.5
 */
abstract class ActiveRecord
{

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
     * Return table name.
     * @since 0.0.5
     * 
     * @return string
     */
    protected static function tableName() : string
    {

        return '';

    }

    /**
     * Return Active Record object by ID.
     * @since 0.0.5
     * 
     * @param int $id
     * Entry ID.
     * 
     * @return self
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

}
