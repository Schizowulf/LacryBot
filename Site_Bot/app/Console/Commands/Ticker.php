<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use React\EventLoop\Loop;
use React\EventLoop\LoopInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use function \Ratchet\Client\connect as socket_connect;


class Ticker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ticker:start';

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
        $loop = Loop::get();
        $loop->stop();
        $react = new \React\Socket\Connector($loop);
        $connector = new \Ratchet\Client\Connector($loop, $react);

        $symbols = ['BTCUSDT'];

        foreach($symbols as $symbol){
            $connector('wss://stream.binance.com:9443/ws/' . strtolower($symbol) . '@trade')->then(function ($ws) use($loop, $symbol) {
                $ws->on('message', function ($msg) use ($ws, $loop, $symbol) {
                    $msg_obj = json_decode($msg, JSON_OBJECT_AS_ARRAY);
                    $res = Cache::put($symbol, $msg_obj['p']);
                    $this->info('PRICE ' . $msg_obj['p']);
                });
                $ws->on('close', function ($code = null, $reason = null) use ($symbol, $loop) {
                    $this->error("$symbol - WebSocket Connection closed! ($code - $reason)");
                    $loop->stop();
                });
            }, function ($e) use ($loop, $symbol) {
                $this->error("$symbol - Could not connect: {$e->getMessage()}");
                $loop->stop();
            });
        }

        return Command::SUCCESS;
    }
}
