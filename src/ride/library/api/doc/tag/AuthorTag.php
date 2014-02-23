<?php

namespace ride\library\api\doc\tag;

use ride\library\api\doc\Doc;

/**
 * Parser for the author tag
 */
class AuthorTag extends Tag {

    /**
     * Name of this tag
     * @var string
     */
    const NAME = 'author';

    /**
     * Parses the lines for this tag into the Doc data container
     * @param ride\library\api\doc\Doc $doc Doc data container
     * @param array $lines Doc comment lines for this tag
     * @return null
     */
    public function parse(Doc $doc, array $lines) {
        $author = implode("\n", $lines);
        $doc->setAuthor($author);
    }

}