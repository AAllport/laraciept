<?php

namespace App\Http\Controllers\Hooks;

use App\Jobs\LinearRemovePrintLabel;
use App\Utils\LinePrint\LinePrint;
use App\Utils\LinePrint\Sections\SectionSettings;
use App\Utils\LinePrint\Sections\TextSectionSettings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use LinearApi\Client as LinearClient;

class Linear
{
    const ASSIGNEE_AALLPORT = "4363bcf6-4f59-495f-82f3-caa220f8f3f6";
    const LABEL_PRINT = "f1102872-bec7-47f4-8bff-3f26444f395a";
    const STATE_BACKLOG = "c2fe827d-52cb-4a4e-9178-4c1e0065e106";

    public function __construct(
    )
    {
    }

    public function __invoke(Request $request): void
    {
        $data = $request->input('data');

        Log::info("Linear hook", $data);

        if (!$this->shouldPrint($request)) {
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
            ->dispatch()
            ->chain([
                new LinearRemovePrintLabel($data['id']),
            ]);
    }

    public function shouldPrint(Request $request): bool
    {
        if (in_array(self::LABEL_PRINT, $request->input('data.labels.*.id'))) return true;

        if (
            $request->input('updatedFrom.stateId') === self::STATE_BACKLOG
            && $request->input('data.assigneeId') === self::ASSIGNEE_AALLPORT
        ) return true;

        return false;
    }
}
