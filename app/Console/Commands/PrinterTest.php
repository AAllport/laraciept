<?php

namespace App\Console\Commands;

use App\Utils\LinePrintHelpers;
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Dflydev\DotAccessData\Data;
use Dotenv\Util\Str;
use Faker\Core\File;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\GdEscposImage;
use Mike42\Escpos\ImagickEscposImage;
use Mike42\Escpos\NativeEscposImage;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\GfxPhp\Image;

class PrinterTest extends Command
{
    protected $signature = 'printer:test';
    protected $description = 'Test the Printer';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Exception
     */
    public function handle()
    {
        $printer = new Printer(new NetworkPrintConnector("192.168.20.10"));

        $printer->feed(2);
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(2, 2);
        $printer->text("PER-001");
        $printer->feed(2);
        $printer->setTextSize(1, 1);
        $printer->setJustification(Printer::JUSTIFY_LEFT);

        $printer->text(LinePrintHelpers::SpaceAwareBreaks("The quick brown fox jumps over the lazy dawg. It really was a lazy shit\n"));
        $printer->text("[ ] Setup the printer\n");
        $printer->text(LinePrintHelpers::SpaceAwareBreaksPrefix(
            "Connect Printer to the network. This involves making sure truenas will automatically start\n",
            "[ ] "
        ));

        $printer->text("[ ] Webhooks\n");

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->graphics(LinePrintHelpers::QrCodeToEscpos("https://youtu.be/dQw4w9WgXcQ"));
//        $printer->graphics(LinePrintHelpers::QrCodeToEscpos("https://linear.app/allport-it/issue/PER-1"));
        $printer->setJustification(Printer::JUSTIFY_LEFT);

        $printer->cut();

        $printer->close();

    }
}
