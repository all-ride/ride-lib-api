<?php

namespace ride\library\api\doc\tag;

use ride\library\api\doc\Doc;

/**
 * Parser for the access tag
 */
class AccessTag extends Tag {

    /**
     * Name of this tag
     * @var string
     */
    const NAME = 'access';

    /**
     * Parses the lines for this tag into the Doc data container
     * @param ride\library\api\doc\Doc $doc Doc data container
     * @param array $lines Doc comment lines for this tag
     * @return null
     */
    public function parse(Doc $doc, array $lines) {
        $access = array_shift($lines);
        if ($access == Doc::ACCESS_PRIVATE || $access == Doc::ACCESS_PROTECTED || $access == Doc::ACCESS_PUBLIC) {
            $doc->setAccess($access);
        }
    }

}