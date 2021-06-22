<?php

namespace Magnate\Tables;

class ActiveRecordJoinEnum
{

    public const INNER_JOIN = 'INNER JOIN';
    public const FULL_JOIN = 'FULL JOIN';
    public const RIGHT_JOIN = 'RIGHT JOIN';
    public const LEFT_JOIN = 'LEFT JOIN';
    public const CROSS_JOIN = 'CROSS JOIN';

    public const JOIN_TYPES = [
        self::INNER_JOIN,
        self::FULL_JOIN,
        self::RIGHT_JOIN,
        self::LEFT_JOIN,
        self::CROSS_JOIN
    ];

}
