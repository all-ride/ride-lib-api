<?php

namespace ride\library\api\doc\tag;

use ride\library\api\doc\Doc;

/**
 * Parser for the API doc tags
 */
class TagParser {

    /**
     * Start character of a doc tag
     * @var string
     */
    const DELIMITER_TAG_START = '@';

    /**
     * Stop character of a doc tag
     * @var string
     */
    const DELIMITER_TAG_STOP = ' ';

    /**
     * Registered tags
     * @var array
     */
    protected $tags;

    /**
     * Construct a new parser for doc tags
     * @return null
     */
    public function __construct() {
        $this->tags = array();

        $this->addTag(new AbstractTag());
        $this->addTag(new AccessTag());
        $this->addTag(new AuthorTag());
        $this->addTag(new CopyrightTag());
        $this->addTag(new DeprecatedTag());
        $this->addTag(new ExampleTag());
        $this->addTag(new ExceptionTag());
        $this->addTag(new GlobalTag());
        $this->addTag(new IgnoreTag());
        $this->addTag(new InternalTag());
        $this->addTag(new LinkTag());
        $this->addTag(new NameTag());
        $this->addTag(new PackageTag());
        $this->addTag(new ParamTag());
        $this->addTag(new ReturnTag());
        $this->addTag(new SeeTag());
        $this->addTag(new SinceTag());
        $this->addTag(new StaticTag());
        $this->addTag(new StaticVarTag());
        $this->addTag(new SubPackageTag());
        $this->addTag(new ThrowsTag());
        $this->addTag(new TodoTag());
        $this->addTag(new VarTag());
        $this->addTag(new VersionTag());
    }

    /**
     * Adds a tag to this parser
     * @param Tag $tag
     * @return null
     */
    public function addTag(Tag $tag) {
        $this->tags[$tag->getName()] = $tag;
    }

    /**
     * Removes a tag from this parser if it's set
     * @param string $tagName
     * @return null
     */
    public function removeTag($tagName) {
        if (isset($this->tags[$tagName])) {
            unset($this->tags[$tagName]);
        }
    }

    /**
     * Parse the tags from the lines array into the Doc data container
     * @param ride\library\api\doc\Doc $doc Doc data container
     * @param array $lines doc comment lines
     * @return null
     */
    public function parse(Doc $doc, array $lines) {
        $tag = false;
        $tagLines = array();

        foreach ($lines as $line) {
            $lineTag = $this->getTag($line);
            if (!$lineTag) {
                $tagLines[] = $line;

                continue;
            }

            if ($tag) {
                $this->tags[$tag]->parse($doc, $tagLines);
            }

            $tagDoc = self::DELIMITER_TAG_START . $lineTag . self::DELIMITER_TAG_STOP;

            $tag = $lineTag;
            $tagLines = array(substr($line, strlen($tagDoc)));
        }

        if ($tag) {
            $this->tags[$tag]->parse($doc, $tagLines);
        }
    }

    /**
     * Get the tag of a string
     * @param string $string
     * @return boolean|Tag the tag if the string starts with a registered tag, false otherwise
     */
    public function getTag($string) {
        if (!$string || $string[0] != self::DELIMITER_TAG_START) {
            return false;
        }

        $positionStop = strpos($string, self::DELIMITER_TAG_STOP);
        if ($positionStop === false) {
            $tag = substr($string, 1);
        } elseif ($positionStop > 3) {
            $tag = substr($string, 1, $positionStop - strlen(self::DELIMITER_TAG_START));
        } else {
            return false;
        }

        if (!$tag) {
            return false;
        }

        if (!array_key_exists($tag, $this->tags)) {
            return false;
        }

        return $tag;
    }

}