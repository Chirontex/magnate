<?php
/**
 * @package Magnate
 * @author Dmitry Shumilin (chirontex@yandex.ru)
 */
namespace Magnate\Exceptions;

use Magnate\Interfaces\ExceptionInterface;
use Exception as BasicException;

/**
 * Basic Magnate Exception class.
 * @since 0.0.1
 */
class Exception extends BasicException implements ExceptionInterface
{

    /**
     * @since 0.0.1
     */
    public static function pickCode(string $error) : int
    {

        $error = self::separator($error);

        return (int)$error[0];

    }

    /**
     * @since 0.0.1
     */
    public static function pickMessage(string $error): string
    {
        
        $error = self::separator($error);

        return $error[1];

    }

    /**
     * Separate error code and message.
     * @since 0.0.1
     * 
     * @param string $error
     * Whole error message.
     * 
     * @return array
     */
    protected static function separator(string $error) : array
    {

        $error = explode('|||', $error);

        $error = array_map(function($value) {

            return trim($value);

        }, $error);

        return $error;

    }

}
