<?php

namespace ride\library\api\io;

/**
 * Interface to retrieve the include paths for the API browser
 */
interface ApiIO {

    /**
     * Gets the include paths for the API browser
     * @return array Array with include paths of files and/or directories
     */
    public function getIncludePaths();

}