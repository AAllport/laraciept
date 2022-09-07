<?php

namespace App\Utils\LinePrint;

use Mike42\Escpos\Printer;

interface LinePrintSectionInterface
{
    public function render(Printer $printer): void;
}
