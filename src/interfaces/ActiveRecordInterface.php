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
     * Return Active Record object by ID.
     * @since 0.0.5
     * 
     * @param int $id
     * Entry ID.
     * 
     * @return self
     */
    public static function find(int $id) : self;

}
