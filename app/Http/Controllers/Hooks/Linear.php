<?php

namespace App\Http\Controllers\Hooks;

use App\Jobs\PrintJob;
use App\Utils\LinePrint\LinePrint;
use App\Utils\LinePrint\Sections\LineSection;
use App\Utils\LinePrint\Sections\LineSectionSpan;
use App\Utils\LinePrint\Sections\SectionSettings;
use App\Utils\LinePrint\Sections\TextSectionSettings;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Linear
{
    public function __invoke(\Illuminate\Http\Request $request): void
    {
        $data = $request->input('data');
        PrintJob::dispatch(
            LinePrint::make()
                ->line(Carbon::now("Europe/London")->toIso8601String())
                ->line("Hooks\Linear - ")
                ->line("")
                ->line($data['team']['key'].'-'.$data['number'], fn(TextSectionSettings $s) => $s
                    ->textSize(3, 3)
                    ->align(TextSectionSettings::ALIGN_CENTER))
                ->line($data['title'], fn($s)=>$s->textSize(2,2))
                ->line("")
                ->line($data['description'])
            ->qrCode($request->input('url'),fn(SectionSettings $s)=>$s->align(SectionSettings::ALIGN_CENTER))
        );

    }
}
