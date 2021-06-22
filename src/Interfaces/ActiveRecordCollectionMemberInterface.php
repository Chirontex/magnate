<?php

namespace Magnate\Interfaces;

/**
 * @package Magnate
 * @author Dmitry Shumilin (chirontex@yandex.ru)
 */
interface ActiveRecordCollectionMemberInterface
{

    /**
     * @since 0.9.8
     */
    public function __set($name, $value);

    /**
     * @since 0.9.8
     */
    public function __get($name);

}
