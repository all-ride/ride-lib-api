<?php

namespace ride\library\api\reflection;

use ride\library\api\doc\DocParser;

use \ReflectionProperty as PhpReflectionProperty;

/**
 * Overriden the ReflectionProperty to get more usefull output for API documentation
 */
class ReflectionProperty extends PhpReflectionProperty {

    /**
     * Instance of the doc parser
     * @var ride\library\api\doc\DocParser
     */
    private $docParser;

    /**
     * API documentation object for this property
     * @var ride\library\api\doc\Doc
     */
    private $doc;

    /**
     * Sets the doc parser to this instance
     * @param ride\library\api\doc\DocParser $docParser
     * @return null
     */
    public function setDocParser(DocParser $docParser) {
        $this->docParser = $docParser;
    }

    /**
     * Get the documentation object for this property
     * @return ride\library\api\doc\Doc
     */
    public function getDoc() {
        if ($this->doc) {
            return $this->doc;
        }

        $doc = $this->getDocComment();
        $this->doc = $this->docParser->parse($doc);

        return $this->doc;
    }

    /**
     * Get the type of this property as a string
     * @return string
     */
    public function getTypeString() {
        $string = '';

        if ($this->isPublic()) {
            $string .= 'public';
        } elseif ($this->isProtected()) {
            $string .= 'protected';
        } elseif ($this->isPrivate()) {
            $string .= 'private';
        }

        if ($this->isStatic()) {
            $string .= ' static';
        }

        return $string;
    }

}