<?php 

namespace App\Libs\BinanceConnector;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use React\Promise\Deferred;
use React\Http\Browser;
class BinanceConnector
{
    protected $api_key;
    protected $api_secret;
    protected $base_url;

    public $wallet_endpoint = '/sapi/v1/capital/config/getall';
    public $order_creation = '/api/v3/order';
    public $order_creation_test = '/api/v3/order/test';
    public $order_book = '/api/v3/depth';
  
    public function __construct($key, $secret, $base_url)
    {
        $this->api_key = $key;
        $this->api_secret = $secret;
        $this->base_url = $base_url;
    }

    public function get_time()
    {
        return intval(microtime(true) * 1000);
    }

    protected function get_signature($total_string)
    {
        return hash_hmac('sha256', $this->get_total_params($total_string), $this->api_secret);
    }

    public function get_total_params($params)
    {
        $first_key = array_keys($params)[0];
        $total_string = $first_key . '=' . $params[$first_key];
        array_shift($params);

        foreach($params as $name => $value){

            $total_string .= '&' . $name . '=' . $value;
        }

        return $total_string;
    }

    protected function request_get($endpoint, $request)
    {
        return json_decode(Http::withHeaders([
            'X-MBX-APIKEY' => $this->api_key
        ])->get($this->base_url . $endpoint, $request)->body(), JSON_OBJECT_AS_ARRAY);
    }

    protected function request_post($endpoint, $request)
    {
/*
        return Http::withHeaders([
            'X-MBX-APIKEY' => $this->api_key
        ])->withBody($request, 'text/plain')->post($this->base_url . $endpoint)->body();*/
        $browser = new Browser();
        $browser = $browser->withRejectErrorResponse(true);
        Storage::disk('local')->append('example1.txt', $this->base_url . $endpoint . ' - ' . $request);
        //return $browser->post('http://localhost:3000/test', [
        return $browser->post($this->base_url . $endpoint, [
            'X-MBX-APIKEY' => $this->api_key,
        ], $request);
        
    }

    /**
     * Get balance
     *
     * @param string $coin
     * @return array
     */
    public function get_ballance($coin = 'all')
    {

        $request = array(
            'timestamp' => $this->get_time()
        );

        $request['signature'] = $this->get_signature($request);

        $result = $this->request_get($this->wallet_endpoint, $request);

        if($coin != 'all') {
            $result = array_filter($result, function($el) use ($coin) { return $el['coin'] == $coin; });
        }
        
        return $result;
    }

    public function get_order_book($symbol = 'BTCUSDT')
    {
        $request = array(
            'symbol' => $symbol
        );

        return $this->request_get($this->order_book, $request);
    }
    
    /**
     * Create market order
     *
     * @return array
     */
    public function create_new_market_order($symbol, $side, $quantity)
    {
        $request = array(
            'symbol'    => $symbol,
            'side'      => $side,
            'quantity'  => $quantity,
            'type'      => 'MARKET',
            'timestamp' => $this->get_time()
        );

        $test = $this->get_total_params($request);
        $test .= $this->get_signature($request);
        $request['signature'] = $this->get_signature($request);

        return $this->request_post($this->order_creation, $request);
    }
    /**
     * Create test market order
     *
     * @return array
     */
    public function create_new_market_order_test($args, $order_type = 'MARKET')
    {

        $request = array(
            'symbol'    => $args['symbol'],
            'side'      => $args['operation'],
            'type'      => $order_type,
            'quantity'  => $args['quantity'],
            'timestamp' => $this->get_time(),
        );

        switch($order_type){
            case 'MARKET':
                break;
            case "STOP_LOSS":
                break;
            case "TAKE_PROFIT":
                break;
            case "LIMIT":
                $request['timeInForce'] = 'GTC';
                if($args['fix_operation'] == 'TP'){
                    $request['price'] = (float) ($args['trigger_price'] + $args['trigger_price'] * ( $args['take_profit'] / 100.0 ));
                }
                if($args['fix_operation'] == 'SL'){
                    $request['price'] = (float) ($args['trigger_price'] - $args['trigger_price'] * ( $args['stop_loss'] / 100.0 ));
                }
                
                break;
        }

        $request_body = $this->get_total_params($request);
        $request_body .= '&signature=' . $this->get_signature($request);

        return $this->request_post($this->order_creation_test, $request_body);
    }
}