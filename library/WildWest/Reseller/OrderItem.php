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
 * Represents an OrderItem SOAP Element for GoDaddy
 *
 * @author     Nicholas Miller <nicholas.j.miller@gmail.com>
 * @package    GoDaddy
 * @subpackage Reseller
 */
class WildWest_Reseller_OrderItem extends WildWest_Reseller_Element
{

    /**
     * The WWD product ID from the catalog of  the item being purchased.
     * @var integer
     */
    public $productid;

    /**
     * (Optional) The quantity of the item being purchased  (defaults to 1).
     * Must be a positive integer.
     *
     * @var integer
     */
    public $quantity;

    /**
     * (Optional) Maximum length 50. May contain any characters.
     * Optional reseller-supplied item identifier.
     *
     * If given, this value will be returned in all notification messages sent
     * to the reseller
     *
     * @var string
     */
    public $riid;

    /**
     * (Optional) Floating point value; default = 1.0
     * 
     * The duration of the purchase. This attribute is used
     * only on domainByProxy items.
     *
     * If privacy is being purchased at the same time that the domain name is
     * being registered, the duration attribute in the domainByProxy item
     * must match the period attribute in the domainRegistration node.
     * 
     * If privacy is being purchased for an already-registered domain name,
     * then use the info request to retrieve the proper value for this attribute.
     * 
     * @var float
     */
    public $duration = 1.0;

}