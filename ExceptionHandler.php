<?php

use compact\logging\Logger;

/**
 *
 * @author elger
 */
class ExceptionHandler
{

    /**
     * Constructor
     */
    public function __construct()
    {
        set_exception_handler(array(
            $this,
            "handle"
        ));
    }

    /**
     * Handles the exception
     *
     * @param $aException Exception            
     */
    public function handle(\Exception $aException)
    {
        Logger::get()->logError("Handle exception " . get_class($aException) . " " . $aException->getMessage());
        Logger::get()->logError($aException->getTraceAsString());
        if ($aException->getPrevious()) {
            Logger::get()->logError($aException->getPrevious()
                ->getTraceAsString());
        }
    }
}
