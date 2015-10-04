<?php

namespace App\Interfaces;

/**
 * Interface for API of nagios.
 */
interface NagiosInterface
{
    public function getCgiResult($sCommand);
}
