<?php

namespace App\Http\Controllers\Hooks;

use App\Utils\LinePrint\LinePrint;
use App\Utils\LinePrint\Sections\SectionSettings;
use App\Utils\LinePrint\Sections\TextSectionSettings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Linear
{
    public function __invoke(Request $request): void
    {
        $data = $request->input('data');

        Log::info("Linear hook", $data);

        if (!in_array("Print", $request->input('data.labels.*.name'))) {
            Log::debug("No print label found");
            abort(403, "No print label found");
        }

        LinePrint::make()
            ->line(Carbon::now("Europe/London")->toIso8601String())
            ->line("")
            ->line($data['team']['key'] . '-' . $data['number'], fn(TextSectionSettings $s) => $s
                ->textSize(3, 3)
                ->align(TextSectionSettings::ALIGN_CENTER))
            ->line($data['title'], fn($s) => $s->textSize(2, 2))
            ->line("")
            ->line($data['description'] ?? "")
            ->qrCode($request->input('url'), fn(SectionSettings $s) => $s->align(SectionSettings::ALIGN_CENTER))
            ->dispatch();
    }
}
