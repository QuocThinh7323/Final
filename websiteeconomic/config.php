<?php
use Omnipay\Omnipay;
define('CLIENT_ID','AQm5rmmdUu5B9BKMKkzdxbjeiJeWFvW0vy3zspXX4hCChOrcsylkjReWKIqYV2WuH1M0OzHE28c6Nhg_');
define('CLIENT_SECRET','EOb6du6NipQqbwB7K9hjGfn-VvfWsTdHY58LyW35OxReX_YMVLCFsiSkWYBb32g6OPcAraXFeNUQOq1J');

define('PAYPAL_RETURN_URL','http://localhost:81/websiteeconomic/paypal/success.php');
define('PAYPAL_CANCEL_URL','http://localhost:81/websiteeconomic/paypal/checkout.php');
define('PAYPAL_CURRENCY', 'USD');


define('DB_HOST','localhost');
define('DB_USENAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'ecomerwebsite');




//Start session 
if(!session_id()){
    session_start();
}
?>