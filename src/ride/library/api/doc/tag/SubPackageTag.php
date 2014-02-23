<?php

namespace ride\library\api\doc\tag;

use ride\library\api\doc\Doc;

/**
 * Parser for the subpackage tag
 */
class SubPackageTag extends Tag {

    /**
     * Name of this tag
     * @var string
     */
    const NAME = 'subpackage';

    /**
     * Parses the lines for this tag into the Doc data container
     * @param ride\library\api\doc\Doc $doc Doc data container
     * @param array $lines Doc comment lines for this tag
     * @return null
     */
    public function parse(Doc $doc, array $lines) {
        $subPackage = implode("\n", $lines);
        $doc->setSubPackage($subPackage);
    }

}