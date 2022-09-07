<?php

namespace Tests\Unit\Utils\LinePrintHelpers;

use App\Utils\LinePrintHelpers;
use PHPUnit\Framework\TestCase;

class SpaceAwareBreaksTest extends TestCase
{
    public function testItCanBreakALine()
    {
        $input = "The quick brown fox jumps over the lazy dawg. It really was a lazy thing";
        $expected = "The quick brown fox jumps over the lazy dawg.\nIt really was a lazy thing";

        $this->assertSame($expected, LinePrintHelpers::SpaceAwareBreaks($input));
    }

    public function testItCanBreakALineWithPrefix()
    {
        $input = "The quick brown fox jumps over the lazy dawg. It really was a lazy thing";
        $expected = "[ ] - The quick brown fox jumps over the lazy\n      dawg. It really was a lazy thing";

        $this->assertSame($expected, LinePrintHelpers::SpaceAwareBreaksPrefix($input, "[ ] - "));
    }

    public function testItRespectsExistingBreaks(){
        $input = "* [X] Setup Printer\n* [X] Connect printer to the network\n* [ ] Webhooks";
        $expected = "* [X] Setup Printer\n* [X] Connect printer to the network\n* [ ] Webhooks";

        $this->assertSame($expected,LinePrintHelpers::SpaceAwareBreaks($input));
    }
}
