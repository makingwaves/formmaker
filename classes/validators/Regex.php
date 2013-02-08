<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Validate
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Regex.php 23775 2011-03-01 17:25:24Z ralph $
 */

/**
 * @category   Zend
 * @package    Validate
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class FormMaker_Validate_Regex extends FormMaker_Validate_Abstract
{
    const INVALID   = 'invalid';
    const NOT_MATCH = 'regex';
    const ERROROUS  = 'invalid';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID   => "Invalid type given. String, integer or float expected",
        self::NOT_MATCH => "Value does not match against pattern %pattern%",
        self::ERROROUS  => "There was an internal error while using the pattern '%pattern%'",
    );

    /**
     * @var array
     */
    protected $_messageVariables = array(
        'pattern' => '_pattern'
    );

    /**
     * Regular expression pattern
     *
     * @var string
     */
    protected $_pattern;

    /**
     * Sets validator options
     *
     * @param  string|Config $pattern
     * @throws Validate_Exception On missing 'pattern' parameter
     * @return void
     */
    public function __construct($pattern)
    {
        if (is_array($pattern)) {
            if (array_key_exists('pattern', $pattern)) {
                $pattern = $pattern['pattern'];
            } else {
                throw new FormMaker_Validate_Exception("Missing option 'pattern'");
            }
        }

        $this->setPattern($pattern);
    }

    /**
     * Returns the pattern option
     *
     * @return string
     */
    public function getPattern()
    {
        return $this->_pattern;
    }

    /**
     * Sets the pattern option
     *
     * @param  string $pattern
     * @throws Validate_Exception if there is a fatal error in pattern matching
     * @return Validate_Regex Provides a fluent interface
     */
    public function setPattern($pattern)
    {
        $this->_pattern = (string) $pattern;
        $status         = @preg_match($this->_pattern, "Test");

        if (false === $status) {
            throw new FormMaker_Validate_Exception("Internal error while using the pattern '$this->_pattern'");
        }

        return $this;
    }

    /**
     * Defined by Validate_Interface
     *
     * Returns true if and only if $value matches against the pattern option
     *
     * @param  string $value
     * @return boolean
     */
    public function isValid($value)
    {
        if (!is_string($value) && !is_int($value) && !is_float($value)) {
            $this->_error(self::INVALID);
            return false;
        }

        $this->_setValue($value);

        $status = @preg_match($this->_pattern, $value);
        if (false === $status) {
            $this->_error(self::ERROROUS);
            return false;
        }

        if (!$status) {
            $this->_error(self::NOT_MATCH);
            return false;
        }

        return true;
    }
}
