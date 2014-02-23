<?php

namespace ride\library\api\doc\tag;

use ride\library\api\doc\Doc;

/**
 * Abstract parser for a doc tag
 */
abstract class Tag {

    /**
     * Gets the name of this tag
     * @return string name
     */
    public function getName() {
        return static::NAME;
    }

    /**
     * Parses the lines for this tag into the Doc data container
     * @param ride\library\api\doc\Doc $doc Doc data container
     * @param array $lines Doc comment lines for this tag
     * @return null
     */
    public function parse(Doc $doc, array $lines) {

    }

}