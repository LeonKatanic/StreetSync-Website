<?php

// Product Details 
$itemNumber = "DP12345";
$itemName = "Demo Product";
$itemPrice = 75;
$currency = "USD";

/* PayPal REST API configuration 
 * You can generate API credentials from the PayPal developer panel. 
 * See your keys here: https://developer.paypal.com/dashboard/ 
 */
define('PAYPAL_SANDBOX', TRUE); //TRUE=Sandbox | FALSE=Production 
define('PAYPAL_SANDBOX_CLIENT_ID', 'ATinuRlAzzkvz5m3dw5yh1Nz9Ox2-VhnZXn04NTdmK60mdevzfI-Jc6SAeIosMXf-AS6eBRp5dnYBdmd');
define('PAYPAL_SANDBOX_CLIENT_SECRET', 'EMdulNtzGd66W8DOG-IKm8QbOl_x-g6UHcthYkFVI2_mlZhBTGD5O0b09qQ26IpzXPnlQx4PJIhUJ5-M');
define('PAYPAL_PROD_CLIENT_ID', 'Insert_Live_PayPal_Client_ID_Here');
define('PAYPAL_PROD_CLIENT_SECRET', 'Insert_Live_PayPal_Secret_Key_Here');
