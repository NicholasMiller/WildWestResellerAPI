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
 * Performs Cetification Test #6 For WildWest reseller
 * Domain Name Renewal
 * 
 * Instructions: Use the OrderPrivateDomainRenewals() method to renew the domain name
 * "example.biz" for one additional year. Because privacy has been added to "example.biz",
 * this resource will have to be renewed, also.
 *
 * Result: The return XML should return a message indicating that the two domains and one
 * privacy account were successfully renewed.
 *
 * Additional Information:
 * Product IDs
 *  + .BIZ 1-year domain name renewal - 350087
 *  + Privacy 1-year renewal - 387001
 *
 * User
 * A unique user identifier was created and returned during the certification task in section
 * 4.1.2. This identifier also appears in the return XML from the certification task
 * described in section 7.1.3
 *
 * Resource IDs
 * The resource IDs for both domains were retrieved using the previous call to the Poll()
 * method. The DBP resource ID must be retrieved via a new Poll() method call.
 * This will return information from the domain name privacy order from the task
 * described in section 4.1.3.
 *
 * @author     Nicholas Miller <nicholas.j.miller@gmail.com>
 * @package    WildWest
 * @subpackage Cetification
 */

require_once dirname(__FILE__) . '/common.php';

$client = Factory::buildClient();
$session = SessionSingleton::getInstance();

if (empty($session->completed[5])) {
    echo "Complete Step #5 first";
    exit();
}

$order = new WildWest_Reseller_OrderItem();
$order->duration = '1';
$order->productid = '350087';
$order->quantity = '1';
$order->riid = '1';

$order2 = clone $order;
$order2->productid = '350137';

$order3 = clone $order;
$order3->productid = '387001';

$item = new WildWest_Reseller_DomainRenewal();
$item->period = '1';
$item->resourceid = $session->resources['example.biz'];
$item->sld = 'example';
$item->tld = 'biz';
$item->order = $order;

$item2 = new WildWest_Reseller_DomainRenewal();
$item2->period = '1';
$item2->resourceid = $session->resources['example.biz'];
$item2->sld = 'example';
$item2->tld = 'us';
$item2->order = $order2;

$item3 = new WildWest_Reseller_DomainRenewal();
$item3->period = '1';
$item3->resourceid = $session->resources['example.biz-dbp'];
$item3->sld = 'example';
$item3->tld = 'biz';
$item3->order = $order3;

$shopper = new WildWest_Reseller_Shopper();
$shopper->user = $session->userid;
$shopper->pwd  = 'abcde';
$shopper->dbpuser = $session->dbpuser;
$shopper->dbppwd = 'defgh';

$result = $client->OrderPrivateDomainRenewals($shopper, array($item, $item2), array($item3));

echo "Step #6: Domain Renewal Complete\n\n";
$session->completed[6] = true;