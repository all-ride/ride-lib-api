<?php

namespace ride\library\api\doc\tag;

use ride\library\api\doc\Doc;
use ride\library\api\doc\DocParameter;

/**
 * Parser for the throws tag
 */
class ThrowsTag extends Tag {

    /**
     * Name of this tag
     * @var string
     */
    const NAME = 'throws';

    /**
     * Parses the lines for this tag into the Doc data container
     * @param \ride\library\api\doc\Doc $doc Doc data container
     * @param array $lines Doc comment lines for this tag
     * @return null
     */
    public function parse(Doc $doc, array $lines) {
        $exception = implode("\n", $lines);

        $positionSpace = strpos($exception, ' ');
        if ($positionSpace === false) {
            $type = $exception;
            $description = null;
        } else {
            $type = substr($exception, 0, $positionSpace);
            $description = substr($exception, $positionSpace + 1);
        }

        $exception = new DocParameter();
        $exception->setType($type);
        $exception->setDescription($description);

        $doc->addException($exception);
    }

}