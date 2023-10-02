<?php

namespace App\Utils\LinePrint;

use App\Jobs\PrintJob;
use App\Utils\LinePrint\Sections\ImageSection;
use App\Utils\LinePrint\Sections\LineSection;
use App\Utils\LinePrint\Sections\LineSectionSpan;
use App\Utils\LinePrint\Sections\SectionSettings;
use App\Utils\LinePrint\Sections\TextSectionSettings;
use App\Utils\LinePrintHelpers;
use Illuminate\Foundation\Bus\PendingDispatch;
use Mike42\Escpos\Printer;

class LinePrint
{
    /** @var array<string|LinePrintSectionInterface> */
    protected array $sections = [];

    /**
     * @param string|callable(LineSection):LineSection $line
     * @param callable(LineSectionSpan):LineSectionSpan|null $fnSettings
     * @return $this
     */
    public function line(string|callable $line ="", callable $fnSettings = null): static
    {
        $effectiveLine = is_callable($line) ? $line(new LineSection()) : LinePrintHelpers::SpaceAwareBreaks($line);
        if ($fnSettings) {
            $effectiveLine = $fnSettings(TextSectionSettings::make($effectiveLine));
        }

        $this->sections[] = $effectiveLine;
        return $this;
    }

    public static function make(): LinePrint
    {
        return new LinePrint();
    }

    public function qrCode(string $content, callable|SectionSettings $fnSettings = new SectionSettings()): static
    {
        $this->sections[] = ImageSection::QrCode($content, $fnSettings);
        return $this;
    }

    public function addSection(LinePrintSectionInterface $section): static
    {
        $this->sections[] = $section;
        return $this;
    }

    public function render(Printer $printer): void
    {
        foreach ($this->sections as $section) {
            if (is_string($section)) {
                $printer->text(LinePrintHelpers::SpaceAwareBreaks($section));
                $printer->feed();
            } else {
                $section->render($printer);
            }
        }

        $printer->cut();
    }

    public function dispatch(): PendingDispatch
    {
        return PrintJob::dispatch($this);
    }
}
