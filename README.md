Wild West (GoDaddy Reseller) PHP API 
=============

WildWestResellerAPI is a SOAP connector for GoDaddy's Wild West Reseller API. 
It relies only on PHP's built-in SOAP functionality. If you previously looked at this
library, you may have noticed it required Zend Framework. That is no longer the case.


Certification Scripts
-------

The main thrust for writing this was to complete Wild West's 
ridiculously complicated OTE certification process. Thus, along with the SOAP API, I 
have included a wizard to complete each part of the certification process. To use it, simply 
place the entire project directory on a PHP-enabled web server, 
open "certifications/index.html" in your web browser, enter your OTE 
credentials, and voila you're done. 

### OTE Certification vs Production WSDL files.

The WildWest_Reseller_Client class defines two URL constants for the WSDL files 
used during OTE Certification and Production API interaction. 
These constants are: WildWest_Reseller_Client::WSDL_OTE_TESTING and 
WildWest_Reseller_Client::WSDL_PRODUCTION


API Examples
-------

The easiest way to understand how to use this library is to 
examine the PHP files under certifications/tests/. While 
these are for the OTE certification, together they outline 
the basic operations available in the API. 






