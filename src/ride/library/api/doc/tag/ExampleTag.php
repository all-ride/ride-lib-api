<?php

namespace ride\library\api\doc\tag;

use ride\library\api\doc\Doc;

/**
 * Parser for the example tag
 */
class ExampleTag extends Tag {

    /**
     * Name of this tag
     * @var string
     */
    const NAME = 'example';

    /**
     * Parses the lines for this tag into the Doc data container
     * @param \ride\library\api\doc\Doc $doc Doc data container
     * @param array $lines Doc comment lines for this tag
     * @return null
     */
    public function parse(Doc $doc, array $lines) {
        $example = implode("\n", $lines);
        $doc->setExample($example);
    }

}