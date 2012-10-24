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
 * @version    $Id: Float.php 23775 2011-03-01 17:25:24Z ralph $
 */


/**
 * @category   Zend
 * @package    Validate
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Validate_Float extends Validate_Abstract
{
    const NOT_FLOAT = 'number';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_FLOAT => "'%value%' does not appear to be a float",
    );

    protected $_locale;

    /**
     * Constructor for the float validator
     *
     * @param string|Config|Locale $locale
     */
    public function __construct()
    {

    }

    /**
     * Defined by Validate_Interface
     *
     * Returns true if and only if $value is a floating-point value
     *
     * @param  string $value
     * @return boolean
     */
    public function isValid($value)
    {
        if (is_float($value) || preg_match('/^([\d]){1,4}\.([\d]){1,2}$/', (string)$value)) {
            return true;
        }
        $this->_error(self::NOT_FLOAT);

        return false;
    }
}
