<?php

namespace App\Console\Commands;

use App\Jobs\PrintJob;
use App\Utils\LinePrint\LinePrint;
use App\Utils\LinePrint\Sections\ImageSection;
use Exception;
use Illuminate\Console\Command;

class PrinterImage extends Command
{
    protected $signature = 'printer:img {url}';
    protected $description = 'Test the Printer';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws Exception
     */
    public function handle()
    {
        $url = $this->argument('url');
        $this->line("Printing " . $url);

        $section = ImageSection::Url($url,true);

        if (!$section) {
            $this->error("Could not generate image");
        }

        PrintJob::dispatch(LinePrint::make()->addSection($section));

        return 1;
    }
}
