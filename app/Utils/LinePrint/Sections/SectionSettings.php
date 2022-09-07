<?php

namespace App\Utils\LinePrint\Sections;

use Mike42\Escpos\Printer;

class SectionSettings
{
    const ALIGN_LEFT = Printer::JUSTIFY_LEFT;
    const ALIGN_CENTER = Printer::JUSTIFY_CENTER;
    const ALIGN_RIGHT = Printer::JUSTIFY_RIGHT;
    protected int $alignment = self::ALIGN_LEFT;

    private array $textSizer = [1, 1];

    public static function make(): SectionSettings
    {
        return new SectionSettings();
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

    public function textSize(int $width = 1, int $height = 1): static
    {
        $this->textSizer = [$width, $height];
        return $this;
    }

    /**
     * @param Printer $printer
     * @param callable(Printer):void $fnRender
     * @return void
     */
    public function render(Printer $printer, callable $fnRender): void
    {
        if ($this->alignment !== self::ALIGN_LEFT) $printer->setJustification($this->alignment);
        if ($this->textSizer !== [1, 1]) $printer->setTextSize(...$this->textSizer);

        $fnRender($printer);

        if ($this->alignment !== self::ALIGN_LEFT) $printer->setJustification(static::ALIGN_LEFT);
        if ($this->textSizer !== [1, 1]) $printer->setTextSize(1, 1);

    }
}
