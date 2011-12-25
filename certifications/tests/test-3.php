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
 * Performs Cetification Test #3 For WildWest reseller
 *
 * @author     Nicholas Miller <nicholas.j.miller@gmail.com>
 * @package    WildWest
 * @subpackage Cetification
 */

require_once dirname(__FILE__) . '/common.php';

$client = $_SESSION['client'];
$session = SessionSingleton::getInstance();

if (empty($_SESSION['complete'][2])) {
    echo json_encode(array('success' => false, 'message' => 'Complete Step #2 first'));
    exit();
}

$shopper = new WildWest_Reseller_Shopper();
$shopper->firstname = 'Artemus';
$shopper->lastname  = 'Gordon';
$shopper->phone     = '+18885551212';
$shopper->user      = $_SESSION['userid'];
$shopper->pwd       = 'abcde';
$shopper->email     = 'agordon@wildwestdomains.com';
$shopper->dbpuser   = 'createNew';
$shopper->dbppwd    = 'defgh';
$shopper->dbpemail  = 'info@example.biz';

$order = new WildWest_Reseller_OrderItem();
$order->productid = '377001';
$order->quantity  = '1';
$order->riid      = '1';
$order->duration  = '1';

$dbp = new WildWest_Reseller_DomainByProxy();
$dbp->sld        = 'example';
$dbp->tld        = 'biz';
$dbp->order      = $order;
$dbp->resourceid = $_SESSION['resources']['example.biz'];

$response = $client->OrderDomainPrivacy($shopper, array($dbp));

$messages = $client->Poll();
$_SESSION['resources']['example.biz-dbp'] = $messages[0]['resourceid'];
$_SESSION['dbporderid']                   = $messages[0]['orderid'];
$_SESSION['dbpuser']                      = $response['dbpuser'];

// echo "Step 3: Complete";
$_SESSION['complete'][3] = true;
echo json_encode(array('success' => true));
