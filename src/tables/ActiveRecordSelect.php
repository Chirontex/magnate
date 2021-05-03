<?php
/**
 * @package Magnate
 * @author Dmitry Shumilin (chirontex@yandex.ru)
 */
namespace Magnate\Tables;

use Magnate\Exceptions\ActiveRecordSelectException;

/**
 * ActiveRecord select class.
 * @since 0.0.6
 */
class ActiveRecordSelect
{

    /**
     * @var string $class
     * ActiveRecord class name.
     * @since 0.0.6
     */
    protected $class;

    /**
     * @since 0.0.6
     * 
     * @param string $class
     * ActiveRecord class name.
     */
    public function __construct(string $class)
    {
        
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

}
