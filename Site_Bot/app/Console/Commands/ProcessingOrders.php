<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Libs\BinanceConnector\BinanceConnector;
use App\Libs\OrdersLog\OrdersLog;
use App\Models\Order;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Psr\Http\Message\ResponseInterface;
use React\Http\Message\ResponseException;
use React\Promise\Deferred;

class ProcessingOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $orders = Order::all();

        foreach($orders as $order){

            //$this->info('work is going');

            if($order->order_status != "pending") continue;

            $symbol = $order->symbol;
            $operation = $order->operation;
            $trigger_price = $order->trigger_price;
            $stop_loss = $order->stop_loss;
            $take_profit = $order->take_profit;
            $quantity = $order->quantity;
            $user_id = $order->user_id;
            $fix_type = $order->fix_type;

            $current_price = (float) Cache::get($symbol);
            //$this->info(Cache::get('BTCUSDT'));
            //$current_price = 19000;

            switch($operation){

                case "BUY":

                    if($trigger_price < $current_price){

                        $api = User::where('id', $user_id)->first();

                        $binance = new BinanceConnector($api->api_key, 
                        $api->api_secret, 
                        'https://api1.binance.com');


                        $args = [
                            'symbol' => $symbol,
                            'operation' => $operation,
                            'quantity' => $quantity,
                            'trigger_price' => $trigger_price,
                            'stop_loss' => $stop_loss,
                            'take_profit' => $take_profit,
                            'user_id' => $user_id,
                        ];
                
                        $binance->create_new_market_order_test($args, 'MARKET')->then(function(ResponseInterface $response) use ($symbol, $current_price, $args, $order) {

                            $this->info('market buy done');

                            $order_fields = $args;

                            unset($order_fields['stop_loss']);
                            unset($order_fields['take_profit']);

                            $order_fields['order_status'] = 'pending';
                            $order_fields['operation'] = 'SELL';
                            $order_fields['parent_order'] = $order->id;
                            $order_fields['fix_type'] = 'SL';
                            $order_fields['trigger_price'] = (float) ($args['trigger_price'] - $args['trigger_price'] * ( $args['stop_loss'] / 100.0 ));

                            Order::create($order_fields);

                            $this->info('SL done');

                            $order_fields['fix_type'] = 'TP';
                            $order_fields['trigger_price'] = (float) ($args['trigger_price'] + $args['trigger_price'] * ( $args['take_profit'] / 100.0 ));
                            
                            Order::create($order_fields);
                            
                            $order->order_status = "done";
                            $order->done_at_time = time();
                            $order->done_at_price = $current_price;
                            $order->save();

                            $this->info('TP done');

                            Storage::disk('local')->append('log-' . $symbol . '.txt', $response->getBody());
                        }, function (Exception $e) use ($symbol, $args) {$this->error('marker buy err'); OrdersLog::log_exception($e, $symbol, 'MARKET');});
                    }
                    break;

                case "SELL":

                    if($fix_type == 'SL' || $fix_type == 'TP'){

                        if(($trigger_price > $current_price && $fix_type == 'SL') || ($trigger_price < $current_price && $fix_type == 'TP')){
                            
                            $api = User::where('id', $user_id)->first();

                            $binance = new BinanceConnector($api->api_key, 
                            $api->api_secret, 
                            'https://api1.binance.com');

                            $args = [
                                'symbol' => $symbol,
                                'operation' => $operation,
                                'quantity' => $quantity,
                                'trigger_price' => $trigger_price,
                            ];

                            $binance->create_new_market_order_test($args, 'MARKET')->then(function(ResponseInterface $response) use ($current_price, $order, $fix_type) {

                                $this->info('market sell done');

                                $order->order_status = "done";
                                $order->done_at_time = time();
                                $order->done_at_price = $current_price;
                                $order->save();

                                if($fix_type == 'SL'){
                                    $take_profit_order = Order::where('parent_order', $order->parent_order)->first();
                                    $take_profit_order->order_status = "cancelled";
                                    $take_profit_order->save();
                                }else{
                                    $stop_loss_order = Order::where('parent_order', $order->parent_order)->first();
                                    $stop_loss_order->order_status = "cancelled";
                                    $stop_loss_order->save();
                                }

                                
                            }, function (Exception $e) use ($symbol, $args) {$this->error('marker sell err'); OrdersLog::log_exception($e, $symbol, 'MARKET');});
                        }

                    }
    
            }
        }
        
        //$this->info('end');
    }
}
