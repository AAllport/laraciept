<?php

namespace App\Console\Commands;

use App\Jobs\PrintJob;
use App\Utils\LinePrint\LinePrint;
use App\Utils\LinePrint\Sections\LineSection;
use App\Utils\LinePrint\Sections\LineSectionSpan;
use App\Utils\LinePrint\Sections\SectionSettings;
use App\Utils\LinePrint\Sections\TextSectionSettings;
use Illuminate\Console\Command;

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
        PrintJob::dispatchSync(
            LinePrint::make()
                ->line("PER-001", fn(TextSectionSettings $s) => $s
                    ->textSize(2, 2)
                    ->align(TextSectionSettings::ALIGN_CENTER))
                ->line("Left")
                ->line("Center", fn(TextSectionSettings $s) => $s->align(TextSectionSettings::ALIGN_CENTER))
                ->line("Right", fn(TextSectionSettings $s) => $s->align(TextSectionSettings::ALIGN_RIGHT))
                ->line(fn(LineSection $line) => $line
                    ->text("None")
                    ->text("Single", fn(LineSectionSpan $s) => $s->underline())
                    ->text("Double", fn(LineSectionSpan $s) => $s->underline(LineSectionSpan::UNDERLINE_DOUBLE))
                )->qrCode("https://youtu.be/dQw4w9WgXcQ", fn(SectionSettings $s) => $s->align(TextSectionSettings::ALIGN_CENTER))
        );

        return 1;
    }
}
