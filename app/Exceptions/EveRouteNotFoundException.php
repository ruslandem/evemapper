<?php

namespace App\Exceptions;

class EveRouteNotFoundException extends \RuntimeException
{
    public function __construct($message = null, $code = 0)
    {
        parent::__construct($message ?? 'route waypoint not found', $code);
    }
}
