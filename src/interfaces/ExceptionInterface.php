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
