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
 * Represents the nameserver SOAP Element for GoDaddy
 * 
 * @author     Nicholas Miller <nicholas.j.miller@gmail.com>
 * @package    WildWest
 * @subpackage Reseller
 */
class WildWest_Reseller_DomainRegistration extends WildWest_Reseller_Element
{
    /**
     * Contains the order information.
     * @var WildWest_Reseller_OrderItem
     */
    public $order;

    /**
     * Maximum 63 characters. Second level domain name (abc of abc.com).
     * @var string
     */
    public $sld;

    /**
     * (.com, .net, .org, .us, .ws, or .info) Top level domain (com of abc.com)
     * @var string
     */
    public $tld;

    /**
     * Length of the registration, in years. Valid
     * values for most are 1-10.
     * 
     * @var integer
     */
    public $period;

    /**
     * The registrant contact. Refer to the ContactInfo type.
     * @var WildWest_Reseller_ContactInfo
     */
    public $registrant;

    /**
     * (Optional) The admin contact. Refer to the ContactInfo type.
     * @var WildWest_Reseller_ContactInfo
     */
    public $admin;

    /**
     * (Optional) The billing contact. Refer to the ContactInfo type.
     * @var GoDaddy_Resller_ContactInfo
     */
    public $billing;

    /**
     * (Optional) The tech contact. Refer to the ContactInfo type.
     * @var WildWest_Reseller_ContactInfo
     */
    public $tech;

    /**
     * (Optional) Default value is 1.
     * Supply 1 to auto-renew 0 for manual renew
     * @var integer
     */
    public $autorenewflag = 1;

    /**
     * Array of nameservers to be set.
     * @var array of WildWest_Reseller_NS
     */
    public $nsArray = array();

    /**
     * 
     * @var WildWest_Reseller_Nexus
     */
    public $nexus;
}
