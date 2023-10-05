<?php

namespace App\Jobs;

use App\Http\Controllers\Hooks\Linear;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class LinearRemovePrintLabel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public string $issueId
    )
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Http::withHeaders([
            'Content-Type' => 'application/json',
            "Authorization" => "Bearer " . config('services.linear.key'),
        ])->post(
            "https://api.linear.app/graphql",
            [
                "query" => sprintf(
                    "mutation IssueRemoveLabel{ issueRemoveLabel(id: %s, labelId: %s) { success } }",
                    $this->issueId, Linear::LABEL_PRINT
                ),
            ]);
    }
}
