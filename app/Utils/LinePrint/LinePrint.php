<?php

namespace App\Utils\LinePrint;

use App\Utils\LinePrint\Sections\LineSection;
use App\Utils\LinePrint\Sections\LineSectionSettings;
use App\Utils\LinePrint\Sections\LineSectionSpan;
use App\Utils\LinePrintHelpers;
use InvalidArgumentException;
use Mike42\Escpos\Printer;
use Spatie\LaravelIgnition\Exceptions\InvalidConfig;
use Termwind\Components\Span;

class LinePrint
{
    /** @var array<string|LinePrintSectionInterface> */
    protected array $sections = [];

    public static function make(): LinePrint
    {
        return new LinePrint();
    }

    /**
     * @param string|callable(LineSection):LineSection $line
     * @param callable(LineSectionSpan):LineSectionSpan|null $fnSettings
     * @return $this
     */
    public function line(string|callable $line, callable $fnSettings = null): static
    {
        $effectiveLine = is_callable($line) ? $line(new LineSection()) : LinePrintHelpers::SpaceAwareBreaks($line);
        if ($fnSettings){
            $effectiveLine = $fnSettings(LineSectionSettings::make($effectiveLine));
        }

        $this->sections[] = $effectiveLine;
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
}
