<?php

namespace ride\library\api\doc\tag;

use ride\library\api\doc\Doc;
use ride\library\api\doc\DocParameter;

/**
 * Parser for the param tag
 */
class ParamTag extends Tag {

    /**
     * Name of this tag
     * @var string
     */
    const NAME = 'param';

    /**
     * Parses the lines for this tag into the Doc data container
     * @param ride\library\api\doc\Doc $doc Doc data container
     * @param array $lines Doc comment lines for this tag
     * @return null
     */
    public function parse(Doc $doc, array $lines) {
        $param = implode("\n", $lines);

        $positionSpace = strpos($param, ' ');
        if ($positionSpace === false) {
            $type = $param;
            $name = null;
            $description = null;
        } else {
            $type = substr($param, 0, $positionSpace);

            $positionDescription = strpos($param, ' ', $positionSpace + 1);
            if ($positionDescription === false) {
                $name = substr($param, $positionSpace + 1);
                $description = null;
            } else {
                $positionName = $positionSpace + 1;
                $name = substr($param, $positionName, $positionDescription - ($positionName));
                $description = substr($param, $positionDescription + 1);
            }

            if ($name && $name[0] != '$') {
                $description = $name . ($description ? ' ' . $description : '');
                $name = null;
            }
        }

        $parameter = new DocParameter();
        $parameter->setType($type);
        $parameter->setName($name);
        $parameter->setDescription($description);

        $doc->addParameter($parameter);
    }

}