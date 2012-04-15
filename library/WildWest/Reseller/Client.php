<?php

/**
 * Used at various points in this class.
 * @see WildWest_Reseller_Exception
 */
require_once realpath(dirname(__FILE__) . '/Exception.php');

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
 * SOAP API Client for GoDaddy's Wild West Reseller System
 *
 * @author     Nicholas Miller <nicholas.j.miller@gmail.com>
 * @package    WildWest
 * @subpackage Reseller
 */
class WildWest_Reseller_Client extends SoapClient
{
    /**
     * OTE Testing WSDL File
     */
    const WSDL_OTE_TESTING = 'https://api.ote.wildwestdomains.com/wswwdapi/wapi.asmx?WSDL';

    /**
     * Production WSDL File
     */
    const WSDL_PRODUCTION = 'https://api.wildwestdomains.com/wswwdapi/wapi.asmx?WSDL';

    /**
     * Credential
     * @var WildWest_Reseller_Credential
     */
    protected $_credential;

    /**
     * @param string $wsdl
     * @param string $account
     * @param string $password
     */
    public function __construct($wsdl, $account, $passowrd)
    {
        $this->_credential           = new WildWest_Reseller_Credential();
        $this->_credential->Account  = $account;
        $this->_credential->Password = $passowrd;

        parent::__construct($wsdl, array('soap_version' => SOAP_1_2));
    }

    /**
     * Orders a domain
     * 
     * @param  WildWest_Reseller_Shopper $shopper
     * @param  array $items
     * @return array keys 'user' and 'orderid'
     */
    public function OrderDomains(WildWest_Reseller_Shopper $shopper, array $items)
    {
        $data = array (
            'credential' => $this->_credential,
            'sCLTRID' => $this->getClientTransactionId(),
            'items' => $items,
            'shopper' => $shopper
        );

        $response = $this->__call('OrderDomains', array($data));
        $xml      = new SimpleXMLElement($response->OrderDomainsResult);
        $path     = $xml->xpath('/response/resdata/orderid');
        
        if (empty($path)) {
            throw new RuntimeException(
                'The order did not process as expected. Raw Response: ' .
                $response
            );
        }

        return array(
            'user' => (string)$xml['user'],
            'orderid' => (string)$xml->resdata->orderid
        );
    }

    /**
     * Restarts the certification process
     * @return void
     * @throws WildWest_Reseller_Exception If the certification was not successful. Contains error message from server.
     */
    public function RestartCertification()
    {
        $xml =  '<wapi clTRID="%s" account="%s" pwd="%s">' .
                    '<manage><script cmd="reset" /></manage>' .
                '</wapi>';

        $xml = sprintf($xml,
            htmlentities($this->getClientTransactionId()),
            htmlentities($this->_credential->Account),
            htmlentities($this->_credential->Password)
        );

        $data = array(
            'sRequestXML' => $xml
        );
        
        $result = $this->__call('ProcessRequest', array($data));
        
        if (strcasecmp('scripting status reset', $result->ProcessRequestResult) !== 0) {
            // XML is only returned when there's an error. Thanks for the consitency, Godaddy!
            $xml = simplexml_load_string($result->ProcessRequestResult);
            throw new WildWest_Reseller_Exception((string)$xml->result->msg);
        }
    }

    /**
     * Checks if a given domain name is available
     *
     * @param array of string $domain names to be checked
     * @return array ['domain_name'] => [1|0] 1 = Available, 0 = Taken
     */
    public function CheckAvailability(array $domains)
    {
        $data = array(
            'sDomainArray' => $domains,
            'credential' => $this->_credential,
            'sCLTRID' => $this->getClientTransactionId()
        );

        $response = $this->__call('CheckAvailability', array($data));
        
        $xml = new SimpleXMLElement($response->CheckAvailabilityResult);
        /* @var $xml SimpleXMLElement */

        if (strcasecmp($xml->getName(), 'check')) {
            $this->_throw(
                __METHOD__, $response,
                $this->getLastRequest(),
                'Maformed result'
            );
        }
        
        $available = array();
        foreach ($xml as $element) {
            $available[(string)$element['name']] = (string)$element['avail'];
        }

        return $available;
    }

    /**
     * Orders Privacy for a given domain
     *
     * Indicies returned in the array are:
     * 'user' => (string) GoDaddy user id
     * 'dbpuser' => (string) Domains by proxy user id
     * 'svTRID' => (string)
     * 'clTRID' => The unique transaction id
     * 'orderid' => (string) order id
     *
     * @param  WildWest_Reseller_Shopper $shopper
     * @param  array $dbpitems
     * @param  string $sROID
     * @throws Godaddy_Reseller_Exception If the response is not as expected
     * @return array 
     */
    public function OrderDomainPrivacy(WildWest_Reseller_Shopper $shopper, array $dbpitems, $sROID = null)
    {
        $data = array (
            'credential' => $this->_credential,
            'sCLTRID' => $this->getClientTransactionId(),
            'items'   => $dbpitems,
            'shopper' => $shopper,
            'sROID' => $sROID
        );

        $response = $this->__call('OrderDomainPrivacy', array($data));

        $xml  = new SimpleXMLElement($response->OrderDomainPrivacyResult);
        
        if (empty($xml->resdata) || empty($xml->resdata->orderid)) {
            throw new WildWest_Reseller_Exception (
                'Did not receive an orderid from order domain with privacy'
            );
        }

        return array(
            'user' => (string)$xml['user'],
            'dbpuser' => (string)$xml['dbpuser'],
            'svTRID' => (string)$xml['svTRID'],
            'clTRID' => (string)$xml['clTRID'],
            'orderid' => (string)$xml->resdata->orderid
        );
    }

    /**
     *
     * @param WildWest_Reseller_Shopper $shopper
     * @param array $items Array of WildWest_Reseller_DomainRenewal items
     * @param string $sROID (Optional) Vendor specific order id
     * @return array 
     */
    public function OrderDomainRenewals(WildWest_Reseller_Shopper $shopper, array $items, $sROID = null)
    {
         $data = array (
            'credential' => $this->_credential,
            'sCLTRID' => $this->getClientTransactionId(),
            'items'   => $items,
            'shopper' => $shopper,
            'sROID' => $sROID
        );

        $response = $this->__call('OrderDomainRenewals', array($data));

        $xml  = new SimpleXMLElement($response);
        $path = $xml->xpath('/response/resdata/orderid');
        if (empty($path)) {
            $this->_throw(
                __METHOD__, $response, $this->getLastRequest(),
                'Should have recieved xpath:/response/resdata/orderid'
            );
        }

        return array(
            'user' => (string)$xml['user'],
            'user' => (string)$xml['dbpuser'],
            'svTRID' => (string)$xml['svTRID'],
            'clTRID' => (string)$xml['clTRID'],
            'orderid' => (string)$xml->resdata->orderid
        );
    }


    /**
     *
     * @param WildWest_Reseller_Shopper $shopper
     * @param array $items Array of WildWest_Reseller_DomainRenewal items
     * @param string $sROID (Optional) Vendor specific order id
     */
    public function OrderPrivateDomainRenewals(WildWest_Reseller_Shopper $shopper, array $items, array $dbpItems, $sROID = null)
    {
         $data = array (
            'credential' => $this->_credential,
            'sCLTRID' => $this->getClientTransactionId(),
            'items'   => $items,
            'dbpItems' => $dbpItems,
            'shopper' => $shopper,
            'sROID' => $sROID
        );

        $response = $this->__call('OrderPrivateDomainRenewals', array($data));
        $xml      = new SimpleXMLElement($response);
        $path     = $xml->xpath('/response/resdata/orderid');

        if (empty($path)) {
            $this->_throw(
                __METHOD__, $response,
                $this->getLastRequest(),
                'expected xpath:/response/resdata/orderid'
            );
        }

        return array(
            'user' => (string)$xml['user'],
            'dbpuser' => (string)$xml['dbpuser'],
            'svTRID' => (string)$xml['svTRID'],
            'clTRID' => (string)$xml['clTRID'],
            'orderid' => (string)$xml->resdata->orderid
        );
    }


    /**
     * Transfers one or more domains
     * 
     * @param WildWest_Reseller_Shopper $shopper
     * @param array  $items array of WildWest_Reseller_DomainTransfer items
     * @param string $sROID (Optional) Reseller's own order id
     * @return array Order information
     */
    public function OrderDomainTransfers(WildWest_Reseller_Shopper $shopper, array $items, $sROID = null)
    {
         $data = array (
            'credential' => $this->_credential,
            'sCLTRID' => $this->getClientTransactionId(),
            'items'   => $items,
            'shopper' => $shopper,
            'sROID' => $sROID
        );

        $response = $this->__call('OrderDomainTransfers', array($data));
        $xml      = new SimpleXMLElement($response);
        $path     = $xml->xpath('/response/resdata/orderid');

        if (empty($path)) {
            $this->_throw(
                __METHOD__, $response,
                $this->getLastRequest(),
                'expected xpath:/response/resdata/orderid'
            );
        }

        return array(
            'user' => (string)$xml['user'],
            'svTRID' => (string)$xml['svTRID'],
            'clTRID' => (string)$xml['clTRID'],
            'orderid' => (string)$xml->resdata->orderid
        );
    }

    /**
     * Returns any outstanding messages as a two dimensional array
     * Each array holds a series of arrays, each with the following keys:
     * 
     * ["orderid"]=> (string)
     * ["roid"]=> (string)
     * ["riid"]=> (string)
     * ["resourceid"] => (string)
     * ["status"] => (string)
     * ["timestamp"]=> (string) example format: 1/20/2011 1:21:37 PM
     *
     * @return array
     */
    public function Poll()
    {
        $data = array(
            'credential' => $this->_credential,
            'sOp' => 'req',
            'sCLTRID' => $this->getClientTransactionId()
        );

        $response = $this->__call('Poll', array($data));
        $xml = new SimpleXMLElement($response->PollResult);
        /* @var $xml SimpleXMLElement */

        $path = $xml->xpath('/response/resdata/REPORT/ITEM');
        $messages = array();
        foreach ($xml->xpath('/response/resdata/REPORT/ITEM') as $item) {
            /* @var $item SimpleXMLElement */
            $tmp = array();
            foreach ($item->attributes() as $k => $v) {
                $tmp[$k] = (string)$v;
            }

            $messages[] = $tmp;
        }

        return $messages;
    }

    /**
     * Gets information about items that have been previously ordered.
     *
     * @todo fix implementation
     * @return 
     */
    public function Info($resourceid, $domain, $orderid)
    {
        $data = array(
            'credential' => $this->_credential,
            'sCLTRID' => $this->getClientTransactionId(),
            'sResourceID' => $resourceid,
            'sDomain' => $domain,
            'sOrderID' => $orderid
        );

        $response = $this->__call('Info', array($data));

        $xml  = new SimpleXMLElement($response);
        $path = $xml->xpath('/response/resdata/info');

        if (empty($path)) {
            $this->_throw(
                __METHOD__, $response, $this->getLastRequest(),
                'Should have recieved xpath:/response/resdata/info'
            );
        }

        $info = array();
        foreach ($xml->resdata->info[0]->attributes() as $k => $v) {
            $info[$k] = (string)$v;
        }

        return $info;
    }



    /**
     * Returns a unique client transaction id
     * @return string
     */
    public function getClientTransactionId()
    {
        return sha1(uniqid(null, true));
    }

    /**
     * Removes the <?xml ?> encoding string, being this causes issues with SimpleXMLElement
     * @param string $result
     * @return string
     */
    protected function _preProcessResult($result)
    {
        return preg_replace('/<\?xml(.*)?\?>/', '', parent::_preProcessResult($result));
    }

    /**
     * Throws an exception that's nicely fomatted for debugging purposes
     * 
     * @param string $method Method name that was called
     * @param string $response Raw XML response string provided by GoDaddy
     * @param string $request Request string sent to GoDaddy
     * @param string $information Any additional information important to the exception
     * 
     * @return void
     */
    protected function _throw($method, $response, $request, $information = null)
    {
        $msg = "There was an error performing the operation: $method.\n" .
            "Request XML: $request \n" .
            "Server Response: $response \n" .
            "Server WSDL: " . $this->getWsdl() . "\n" .
            "Additional Information: " . (empty($information) ? 'None provided' : $information) . "\n";

        throw new WildWest_Reseller_Exception($msg);
    }
}

