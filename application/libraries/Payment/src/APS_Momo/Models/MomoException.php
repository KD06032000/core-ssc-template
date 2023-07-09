<?php

namespace APS_Momo\Models;

use Exception;

class MomoException extends Exception
{
    public function getErrorMessage()
    {
        $errorMg = 'Error on line' . $this->getLine() . 'in' . $this->getFile()
            . ":\n" . $this->getMessage()
            . ":\n" . $this->getTraceAsString()
            . "\n";
        return $errorMg;
    }
}
