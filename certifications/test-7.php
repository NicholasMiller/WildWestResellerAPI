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
 * Performs Cetification Test #7 For WildWest reseller
 *
 * Domain Name Transfer:
 * Instructions: Use the OrderDomainTransfer() method to transfer the domain "example.com"
 * to a new Wild West Domains shopper account.
 *
 * Result: The return XML should contain a message indicating the order
 * was successfully processed.
 * 
 * Additional Information:
 * Name Servers
 *   ns1.example.net, ns2.example.net
 *
 * Product ID
 *   + .COM Transfer - 350011
 * 
 * Registrant Data
 *   + First Name - Joe
 *   + Last Name - Smith
 *   + Email - joe@smith.us
 *   + Address - 1 S. Main St.
 *   + City - Oakland
 *   + State - California
 *   + ZIP - 97123
 *   + Country - United States
 *   + Phone - (777)555-1212
 *   + Password - "ghijk"
 *
 * Upon completion of the above steps, the reseller's status will be updated
 * in the WWD database and a certification confirmation email message will
 * be sent to the reseller. At this point, the reseller is granted permission
 * to the production reseller extranet and API environments.
 *
 * @author     Nicholas Miller <nicholas.j.miller@gmail.com>
 * @package    WildWest
 * @subpackage Cetification
 */

require_once dirname(__FILE__) . '/common.php';

$client = Factory::buildClient();
$session = SessionSingleton::getInstance();

if (empty($session->completed[6])) {
    echo "Complete Step #6 first";
    exit();
}

$shopper = new WildWest_Reseller_Shopper();
$shopper->firstname = 'Joe';
$shopper->lastname = 'Smith';
$shopper->email = 'joe@smith.us';
$shopper->phone = '+1.7775551212';
$shopper->pwd = 'ghijk';
$shopper->user = 'createNew';

$order = new WildWest_Reseller_OrderItem();
$order->productid = '350011';
$order->quantity  = '1';

$transfer = new WildWest_Reseller_DomainTransfer();
$transfer->sld = 'example';
$transfer->tld = 'com';
$transfer->order = $order;

$response = $client->OrderDomainTransfers($shopper, array($transfer));

echo "Step 7 & Certification Complete! (New Order ID: " . $response['orderid'] . ")";
$session->completed[7] = true;
