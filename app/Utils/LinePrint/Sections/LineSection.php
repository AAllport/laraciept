<?php

namespace App\Utils\LinePrint\Sections;

use App\Utils\LinePrint\LinePrintSectionInterface;
use Mike42\Escpos\Printer;

class LineSection implements LinePrintSectionInterface
{
    /** @var LineSectionSpan[] */
    protected array $spans = [];

    public function render(Printer $printer): void
    {
        foreach ($this->spans as $span) {
            $span->render($printer);
            $printer->text(" ");
        }
    }

    /**
     * @param string $text
     * @param callable(LineSectionSpan):LineSectionSpan|null $fnSettings
     * @return $this
     */
    public function text(string $text, callable $fnSettings = null)
    {
        $text = LineSectionSpan::make($text);

        if ($fnSettings) {
            $fnSettings($text);
        }

        $this->spans[] = $text;

        return $this;
    }
}
