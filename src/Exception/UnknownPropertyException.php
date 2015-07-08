<?php

namespace FastSMS\Exception;

class UnknownPropertyException extends \Exception
{

    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Unknown Property';
    }

}
