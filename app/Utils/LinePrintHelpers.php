<?php

namespace App\Utils;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Support\Arr;
use Mike42\Escpos\GdEscposImage;
use Termwind\Components\Li;
use function Termwind\render;

class LinePrintHelpers
{
    const LINE_LENGTH = 42;

    public static function SpaceAwareBreaks(string $input, int $maxLength = self::LINE_LENGTH): string
    {
        return self::SpaceAwareBreaksPrefix($input, maxLength: $maxLength);
    }

    public static function SpaceAwareBreaksPrefix(string $input, string $prefix = "", int $maxLength = self::LINE_LENGTH): string
    {
        $output = [];
        $line = $prefix;
        $whiteSpace = str_repeat(" ", strlen($prefix));

        while (strlen($input) > 0) {
            $nextSpace = strpos($input, " ");
            $staging = $nextSpace === false ? $input : substr($input, 0, $nextSpace);
            $input = $nextSpace === false ? "" : substr($input, $nextSpace + 1);

            if (strlen($line . ' ' . $staging) > $maxLength) {
                $output[] = $line;
                $line = $whiteSpace;
            }
            if ($line !== $whiteSpace && $line !== $prefix) $line .= ' ';
            $line .= $staging;
        }

        return Arr::join([...$output, $line], "\n");
    }

    public static function QrCodeToEscpos(string $content, QROptions|array $options = new QROptions()): GdEscposImage
    {
        $options = $options instanceof QROptions ? $options : new QROptions($options);
        $options->returnResource = true;

        $output = (new QRCode($options))->render($content);

        $img = (new GdEscposImage());
        $img->readImageFromGdResource($output);

        return $img;
    }
}
