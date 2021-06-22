<?php

namespace Magnate\Tables;

/**
 * @package Magnate
 * @author Dmitry Shumilin (chirontex@yandex.ru)
 */
trait ActiveRecordCollectionMemberTrait
{

    /**
     * @var array $ar_fields_types
     * Contains types of the fields.
     * @since 0.9.8
     */
    protected $ar_fields_types = [];

    /**
     * @var array $ar_fields_values
     * Contains fields values.
     * @since 0.9.8
     */
    protected $ar_fields_values = [];

    /**
     * @since 0.9.8
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
     * @since 0.9.8
     */
    public function __get($name)
    {
        
        return isset($this->ar_fields_values[$name]) ?
            $this->ar_fields_values[$name] : NULL;

    }

}
