<?php

namespace App\Console\Commands;

use App\Jobs\PrintJob;
use App\Utils\LinePrint\LinePrint;
use App\Utils\LinePrint\Sections\ImageSection;
use App\Utils\LinePrint\Sections\LineSection;
use App\Utils\LinePrint\Sections\LineSectionSpan;
use App\Utils\LinePrint\Sections\SectionSettings;
use App\Utils\LinePrint\Sections\TextSectionSettings;
use Exception;
use Illuminate\Console\Command;
use Nette\Utils\Image;

class PrinterTest extends Command
{
    protected $signature = 'printer:test';
    protected $description = 'Test the Printer';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws Exception
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
            ->addSection(ImageSection::Url("https://static.wikia.nocookie.net/agk/images/1/18/21499dba0eec71730fdaa0534a82e163.jpg/revision/latest?cb=20210511185219"))
        );

        return 1;
    }
}
