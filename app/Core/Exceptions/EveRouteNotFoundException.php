<?php

namespace App\Core\Exceptions;

class EveRouteNotFoundException extends \Exception
{
    public function __construct($message = null, $code = 0)
    {
        parent::__construct($message ?? 'route waypoint not found', $code);
    }
}
