<?php

namespace App\Jobs;

use App\Utils\LinePrint\LinePrint;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;

class PrintJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected LinePrint $print;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(LinePrint $print)
    {
        $this->print = $print;
    }

    public function middleware()
    {
        return [new WithoutOverlapping()];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $printer = new Printer(new NetworkPrintConnector("192.168.20.10"));
        $this->print->render($printer);
        $printer->close();
    }
}
