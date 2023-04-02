<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use PO;
use React\EventLoop\Loop;
use React\EventLoop\LoopInterface;
use function \Ratchet\Client\connect as socket_connect;
use App\Libs\BinanceConnector\BinanceConnector;

class test_scheldue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test_s';

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
        /*
        socket_connect('wss://stream.binance.com:9443/ws/btcusdt@kline_1s')->then(function($conn) {
            $conn->on('message', function($msg) use ($conn) {
                //echo "Received: {$msg}\n";
                //$msg_f = $msg;
                Storage::disk('local')->append('example.txt', $msg . PHP_EOL . time());
                //$conn->close();
            });

            //$conn->send('Hello World!');
        }, function ($e) {
           echo "Could not connect: {$e->getMessage()}\n";
        });*/

        $loop = Loop::get();
        $loop->stop();
        //info('run');
        //$this->info('The command was successful!');
        //file_put_contents();
        
        //$loop = Loop::get();
        $react = new \React\Socket\Connector($loop);
        $connector = new \Ratchet\Client\Connector($loop, $react);

        $symbols = ['bnbbtc', 'btcusdt', 'ethbtc', 'ltcbtc'];

        foreach($symbols as $symbol){
            $connector('wss://stream.binance.com:9443/ws/' . $symbol . '@kline_1s')->then(function ($ws) use($loop, $symbol) {
                $ws->on('message', function ($msg) use ($ws, $loop, $symbol) {
                    Storage::disk('local')->append('example.txt', $msg . PHP_EOL . time());
                    //$ws->close();
                    //$loop->stop();
                });
            });
        }
        
        return Command::SUCCESS;
    }
}
