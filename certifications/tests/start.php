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
 * Resets The Certification Process incase anything went awry
 *
 * @author     Nicholas Miller <nicholas.j.miller@gmail.com>
 * @package    WildWest
 * @subpackage Cetification
 */

require_once realpath(dirname(__FILE__) . '/common.php');

session_destroy();
session_start();

if (empty($_REQUEST['account']) || empty($_REQUEST['pass'])) {
    echo json_encode(array('success' => false, 'message' => 'missing credentials.'));
    exit();
}

$client = new WildWest_Reseller_Client(
    WildWest_Reseller_Client::WSDL_OTE_TESTING, 
    $_REQUEST['account'], $_REQUEST['pass']
);

$_SESSION['account'] = $_REQUEST['account'];
$_SESSION['pass']    = $_REQUEST['pass'];

try {
    $client->RestartCertification();
    echo json_encode(array('success' => true));
} catch (Exception $ex) {
    echo json_encode(array('success' => false, "message" => $ex->getMessage()));
}

unset($_SESSION['complete']);




