<?php
/**
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * -----------------------------------------------------------------------------
 *
 * SOAP API Client Factory
 * 
 * @author     Nicholas Miller <nicholas.j.miller@gmail.com>
 * @package    WildWest
 * @subpackage Reseller
 */
class WildWest_Reseller_Factory
{
    /**
     * @var string
     */
    protected static $_defaultWsdl;

    /**
     * @var string
     */
    protected static $_defaultAccount;

    /**
     * @var string
     */
    protected static $_defaultPassword;

    /**
     * Protected Constructor to Prohibit Instantiation
     */
    protected function __construct() {}

    /**
     * Sets the default wsdl to be used
     * @param string $wsdl
     */
    public static function setDefaultWsdl($wsdl)
    {
        self::$_defaultWsdl = $wsdl;
    }

    /**
     * Returns the the default wsdl
     * @return string
     */
    public static function getDefaultWsdl()
    {
        return self::$_defaultWsdl;
    }

    /**
     * Sets the default account
     * @param string $account
     */
    public static function setDefaultAccount($account)
    {
        self::$_defaultAccount = $account;
    }

    /**
     * Gets the default account
     * @return string
     */
    public static function getDefaultAccount()
    {
        return self::$_defaultAccount;
    }

    /**
     * Sets a default password for building a new
     * @param string $password
     */
    public static function setDefaultPassword($password)
    {
        self::$_defaultPassword = $password;
    }

    /**
     * Gets the default password
     * @return string
     */
    public static function getDefaultPassword()
    {
        return self::$_defaultPassword;
    }

    /**
     * Creates a new WildWest_Reseller_Client object
     *
     * @param string $wsdl
     * @param string $account
     * @param string $password
     */
    public static function build($wsdl = null, $account = null, $password = null)
    {
        $wsdl     = empty($wsdl) ? self::getDefaultWsdl() : $wsdl;
        $account  = empty($account) ? self::getDefaultAccount() : $account;
        $password = empty($password) ? self::getDefaultPassword() : $password;

        return new WildWest_Reseller_Client($wsdl, $account, $password);
    }
}


