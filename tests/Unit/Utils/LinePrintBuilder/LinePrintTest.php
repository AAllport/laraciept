<?php

namespace Tests\Unit\Utils\LinePrintBuilder;


use App\Utils\LinePrint\LinePrint;
use App\Utils\LinePrint\Sections\LineSection;
use App\Utils\LinePrint\Sections\LineSectionSpan;
use App\Utils\LinePrint\Sections\TextSectionSettings;
use Mike42\Escpos\PrintConnectors\DummyPrintConnector;
use Mike42\Escpos\Printer;
use Mockery;
use PHPUnit\Framework\TestCase;

class LinePrintTest extends TestCase
{
    public function testBasicBuilder()
    {
        $printer = Mockery::spy(new Printer(new DummyPrintConnector()));

        LinePrint::make()
            ->line("Left")
            ->line("Center", fn($s) => $s->align(TextSectionSettings::ALIGN_CENTER))
            ->line("Right", fn($s) => $s->align(TextSectionSettings::ALIGN_RIGHT))
            ->line(fn(LineSection $line) => $line
                ->text("None")
                ->text("Single", fn(LineSectionSpan $s) => $s->underline())
                ->text("Double", fn(LineSectionSpan $s) => $s->underline(LineSectionSpan::UNDERLINE_DOUBLE))
            )->render($printer);

        $printer->close();
        $this->assertTrue(true, "Did not crash");
    }

    public function testAlignRight()
    {
        $printer = Mockery::spy(new Printer(new DummyPrintConnector()));

        LinePrint::make()
            ->line("Right Aligned", fn(TextSectionSettings $s) => $s->align(TextSectionSettings::ALIGN_RIGHT))
            ->render($printer);

        $printer->shouldHaveReceived("setJustification")->with(2);
        $printer->shouldHaveReceived('text')->with("Right Aligned");
        $printer->shouldHaveReceived('setJustification')->with(0);

        $printer->close();
        $this->assertTrue(true, "Did not crash");

    }
}
