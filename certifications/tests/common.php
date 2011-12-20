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
 * Configuration file for the WildWest Certification Tests
 * Please keep in mind these scripts are quite minimal and are used to pass
 * the WildWest reseller certification.
 *
 * @author     Nicholas Miller <nicholas.j.miller@gmail.com>
 * @package    WildWest
 * @subpackage Cetification
 */

define('API_ACCOUNT', '');
define('API_PASSWORD', '');

if (API_ACCOUNT == '') {
    die('Set the API_ACCOUNT Constant in ' . __FILE__);
}

if (API_PASSWORD == '') {
    die('Set the API_PASSWORD Constant in ' . __FILE__);
}


set_include_path(
    get_include_path() . PATH_SEPARATOR .
    realpath(__DIR__ . '/../library')
);

require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('WildWest_');

class SessionSingleton
{
    /**
     * Gets an instance of the session
     * @return Zend_Session_Namespace
     */
    public static function getInstance()
    {
        static $session = null;

        if (!is_null($session)) {
            return $session;
        }

        $session = new Zend_Session_Namespace('certification');
        return $session;
    }
}


/**
 * Soap Client Factory
 */
class Factory
{
    protected function  __construct()
    {
    }

    /**
     * @return WildWest_Reseller_Client
     */
    public static function buildClient()
    {
        return new WildWest_Reseller_Client(
            WildWest_Reseller_Client::WSDL_OTE_TESTING,
            API_ACCOUNT, API_PASSWORD
        );
    }
}


