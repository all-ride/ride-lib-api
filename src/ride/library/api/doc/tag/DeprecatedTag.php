<?php

namespace ride\library\api\doc\tag;

use ride\library\api\doc\Doc;

/**
 * Parser for the deprecated tag
 */
class DeprecatedTag extends Tag {

    /**
     * Name of this tag
     * @var string
     */
    const NAME = 'deprecated';

    /**
     * Parses the lines for this tag into the Doc data container
     * @param \ride\library\api\doc\Doc $doc Doc data container
     * @param array $lines Doc comment lines for this tag
     * @return null
     */
    public function parse(Doc $doc, array $lines) {
        $message = implode("\n", $lines);
        $doc->setDeprecatedMessage($message);
        $doc->setIsDeprecated(true);
    }

}