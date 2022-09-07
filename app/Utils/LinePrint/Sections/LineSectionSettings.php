<?php

namespace App\Utils\LinePrint\Sections;

use Mike42\Escpos\EscposImage;
use Mike42\Escpos\Printer;

class LineSectionSettings extends LineSectionSpan
{

    const ALIGN_LEFT = Printer::JUSTIFY_LEFT;
    const ALIGN_CENTER = Printer::JUSTIFY_CENTER;
    const ALIGN_RIGHT = Printer::JUSTIFY_RIGHT;
    protected int $alignment = self::ALIGN_LEFT;

    private array $textSizer = [1, 1];

    public static function make(string $text): LineSectionSpan
    {
        return new LineSectionSettings($text);
    }


    /**
     * @phpstan-param self::ALIGN_* $alignment
     * @return $this
     */
    public function align(int $alignment = self::ALIGN_LEFT): static
    {
        $this->alignment = $alignment;
        return $this;
    }

    public function textSize(int $width = 1, int $height = 1):static
    {
        $this->textSizer = [$width, $height];
        return $this;
    }

    public function render(Printer $printer): void
    {
        if ($this->alignment !== self::ALIGN_LEFT) $printer->setJustification($this->alignment);
        if ($this->textSizer !== [1, 1]) $printer->setTextSize(...$this->textSizer);


        parent::render($printer);
        $printer->feed();

        if ($this->alignment !== self::ALIGN_LEFT) $printer->setJustification(static::ALIGN_LEFT);
        if ($this->textSizer !== [1, 1]) $printer->setTextSize(1,1);

    }
}
