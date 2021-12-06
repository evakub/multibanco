<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use App\Services\NuvemService;

class setTransactionPendingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $nuvemService;
    private $request; 
    public function __construct($request)
    {
        //
        $this->request = $request;
        $this->nuvemService = new NuvemService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //        
        $response_nuvem = json_decode($this->nuvemService->setOrderPending($this->request["id"], $this->request["amount"], $this->request["redirect_url"])["response"]);
        print_r($response_nuvem);
        Log::info($response_nuvem);
        return $response_nuvem;
    
    }
}
