<?php
/**
 * @package Magnate
 * @author Dmitry Shumilin (chirontex@yandex.ru)
 */
namespace Magnate\Interfaces;

/**
 * Exception interface.
 * @since 0.0.1
 */
interface ExceptionInterface
{

    /**
     * @since 0.0.1
     */
    const NOT_WPDB = '-1 ||| $wpdb global variable is not WPDB instance.';

    /**
     * @since 0.0.2
     */
    const NOT_DIR = '-2 ||| %1$s is not a directory.';
    
    /**
     * @since 0.0.4
     */
    const EMPTY = '-3 ||| %1$s cannot be empty.';

    /**
     * @since 0.0.4
     */
    const CREATE_TABLE = '-4 ||| %1$s table creation failure.';

    /**
     * @since 0.0.5
     */
    const INSERT_ENTRY = '-5 ||| Entry insertion failure.';

    /**
     * @since 0.0.5
     */
    const NOT_FOUND = '-6 ||| %1$s not found.';

    /**
     * @since 0.0.6
     */
    const NOT_TYPE = '-7 ||| %1$s must be %2$s.';

    /**
     * @since 0.0.6
     */
    const NOT_EXISTS = '-8 ||| %1$s not exists.';

    /**
     * Return error code number.
     * @since 0.0.1
     * 
     * @param string $error
     * Whole error message.
     * 
     * @return int
     */
    public static function pickCode(string $error) : int;

    /**
     * Return error message.
     * @since 0.0.1
     * 
     * @param string $error
     * Error message with code from constant.
     * 
     * @return string
     */
    public static function pickMessage(string $error) : string;

}
