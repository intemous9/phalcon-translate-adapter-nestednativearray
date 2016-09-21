<?php
/**
 * NestedNativeArray.php
 *
 * @copyright   Copyright (c) 2016 T.Muroi
 * @package
 * @subpackage
 * @version     $Id$
 */

namespace Muroi\Phalcon\Translate\Adapter;

use Phalcon\Translate\Exception;
use Phalcon\Translate\AdapterInterface;
use Phalcon\Translate\Adapter;

class NestedNativeArray extends Adapter implements AdapterInterface, \ArrayAccess
{

    protected $_translate;

    private $_delimiter;

    /**
     * Muroi\Phalcon\Translate\Adapter\NestedNativeArray constructor
     *
     * @param array  $options
     * @param string $delimiter = '.'
     */
    public function __construct($options, $delimiter = '.')
    {
        parent::__construct($options);

        if (!array_key_exists('content', $options)) {
            throw new Exception("Translation content was not provided");
        }

        if (!is_array($options['content'])) {
            throw new Exception("Translation data must be an array");
        }

        $this->_translate = $options['content'];
        $this->_delimiter = $delimiter;
    }

    /**
     * Returns the translation related to the given key
     *
     * @param   string $index
     * @param   array $placeholders
     * @return  string
     */
    public function query($index, $placeholders = null)
    {
        $translation = $this->_translate;

        foreach (explode($this->_delimiter, $index) as $nested_index) {

            if (is_array($translation) && array_key_exists($nested_index, $translation)) {
                $translation = $translation[$nested_index];
            } else {
                $translation = $index;
                break;
            }

        }

        return $this->replacePlaceholders($translation, $placeholders);
    }

    /**
     * Check whether is defined a translation key in the internal array
     *
     * @param   string $index
     * @return  bool
     */
    public function exists($index)
    {
        $translation = $this->_translate;

        foreach (explode($this->_delimiter, $index) as $nested_index) {

            if (is_array($translation) && array_key_exists($nested_index, $translation)) {
                $translation = $translation[$nested_index];
            } else {
                return false;
            }

        }

        return true;
    }

}
