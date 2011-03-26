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
 * Represents the Nexus SOAP Element for GoDaddy
 * 
 * @author     Nicholas Miller <nicholas.j.miller@gmail.com>
 * @package    WildWest
 * @subpackage Reseller
 */
class WildWest_Reseller_Nexus extends WildWest_Reseller_Element
{
    /**
     * Select the value that best describes the prospective owner of the domain.
     * 
     * Valid Values:
     * -----------------
     * citizen of US
     * permanent resident of US
     * primary domicile in US
     * incorporated or organized in US
     * foreign entity doing business in US
     * foreign entity with office or property in US
     *
     * @var string
     */
    public $category;

    /**
     * Select the value that best describes the use for this domain.
     * Valid Values:
     * ------------------
     * personal
     * business use for profit
     * non-profit business or organization
     * educational purposes
     * government purposes
     * 
     * @var string
     */
    public $use;

    /**
     * If category is one of the foreign entity values, then the
     * two-letter country code for the owner's home country must be provided.
     * 
     * @var string
     */
    public $County;
}