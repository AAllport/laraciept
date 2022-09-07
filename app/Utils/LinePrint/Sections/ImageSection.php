<?php

namespace App\Utils\LinePrint\Sections;

use App\Utils\LinePrint\LinePrintSectionInterface;
use App\Utils\LinePrintHelpers;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\Printer;

class ImageSection implements LinePrintSectionInterface
{

    protected SectionSettings $settings;

    public function __construct(protected EscposImage $image, SectionSettings|callable $fnSettings = new SectionSettings())
    {
        $this->settings = is_callable($fnSettings) ? $fnSettings(new SectionSettings()) : $fnSettings;
    }

    public static function QrCode(string $content, SectionSettings|callable $fnSettings = new SectionSettings()): ImageSection
    {
        $escPosImage = LinePrintHelpers::QrCodeToEscpos($content);

        return new ImageSection($escPosImage, $fnSettings);
    }

    public function render(Printer $printer): void
    {
        $this->settings->render(
            $printer,
            fn(Printer $printer) => $printer->graphics($this->image)
        );
    }
}
