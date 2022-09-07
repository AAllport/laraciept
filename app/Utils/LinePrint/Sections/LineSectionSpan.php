<?php

namespace App\Utils\LinePrint\Sections;

use Mike42\Escpos\EscposImage;
use Mike42\Escpos\Printer;

class LineSectionSpan
{
    const UNDERLINE_NONE = Printer::UNDERLINE_NONE;
    const UNDERLINE_SINGLE = Printer::UNDERLINE_SINGLE;
    const UNDERLINE_DOUBLE = Printer::UNDERLINE_DOUBLE;
    protected int $underline = self::UNDERLINE_NONE;

    public function __construct(protected string $content)
    {
    }

    public static function make(string $text): self
    {
        return new LineSectionSpan($text);
    }


    /**
     * @phpstan-param static::UNDERLINE_* $underline
     * @return $this
     */
    public function underline(int $underline = self::UNDERLINE_SINGLE): static
    {
        $this->underline = $underline;
        return $this;
    }

    public function render(Printer $printer): void
    {
        if ($this->underline !== self::UNDERLINE_NONE) $printer->setUnderline($this->underline);

        $printer->text($this->content);

        if ($this->underline !== self::UNDERLINE_NONE) $printer->setUnderline(static::UNDERLINE_NONE);

    }
}
