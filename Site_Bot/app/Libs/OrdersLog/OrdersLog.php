<?php 

namespace App\Libs\OrdersLog;

use Illuminate\Support\Facades\Storage;
use Exception;
use React\Http\Message\ResponseException;

class OrdersLog {

    public function __construct($key, $secret, $base_url)
    {
        $this->api_key = $key;
        $this->api_secret = $secret;
        $this->base_url = $base_url;
    }

    public static function log_exception(Exception $e, $symbol, $order_type){
        if ($e instanceof ResponseException) {
            // any HTTP response error message will now end up here
            $response = $e->getResponse();
            Storage::disk('local')->append('log-ERROR-' . $symbol . '.txt', $response->getBody() . ' time - ' . microtime() . ' Order - ' . $order_type);
            //var_dump($response->getStatusCode(), $response->getReasonPhrase());
        } else {
            Storage::disk('local')->append('log-ERROR-' . $symbol . '.txt', $e->getMessage() . ' time - ' . microtime() . ' Order - ' . $order_type);
        }
    }
}