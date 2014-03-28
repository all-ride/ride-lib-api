<?php

namespace ride\library\api\doc\tag;

use ride\library\api\doc\Doc;
use ride\library\api\doc\DocParameter;

/**
 * Parser for the return tag
 */
class ReturnTag extends Tag {

    /**
     * Name of this tag
     * @var string
     */
    const NAME = 'return';

    /**
     * Parses the lines for this tag into the Doc data container
     * @param \ride\library\api\doc\Doc $doc Doc data container
     * @param array $lines Doc comment lines for this tag
     * @return null
     */
    public function parse(Doc $doc, array $lines) {
        $return = implode("\n", $lines);

        $positionSpace = strpos($return, ' ');
        if ($positionSpace === false) {
            $type = trim($return);
            $description = null;
        } else {
            $type = substr($return, 0, $positionSpace);
            $description = trim(substr($return, $positionSpace));
        }

        if ($type == 'null' && !$description) {
            return;
        }

        $parameter = new DocParameter();
        $parameter->setType($type);
        $parameter->setDescription($description);

        $doc->setReturn($parameter);
    }

}