<?php 
require 'vendor/autoload.php';
use Scheb\YahooFinanceApi\ApiClient;
use Scheb\YahooFinanceApi\ApiClientFactory;
// Create a new client from the factory as suggested by the YahooFinance API
$client = ApiClientFactory::createApiClient();

return function($req, $res) use ($client) {
    // symbols maps types to symbols used in the YahooFinance API
    $symbols = array();
    $symbols['bitcoin'] = "BTC-USD";
    $symbols['ethereum'] = "ETH-USD";
    $symbols['google'] = "GOOG";
    $symbols['amazon'] = "AMZN";
    $symbols['gold'] = "GC=F";
    $symbols['silver'] = "SI=F";

    $payload = \json_decode($req['payload'], true);
    $type = $payload['type'];
    $symbol = $symbols[$type];
    // When inputted type is not found in $symbols, return an error message.
    if ($symbol == null) {
        $res->json([
          'successful' => false,
          'message' => "Type is not supported."
        ]);
        return;
    }
    // Else, return the corresponding response from the API.
    $price = $client->getQuote($symbol)->getRegularMarketPrice();
    $res->json([
        'successful' => true,
        'price' => $price
    ]);
};