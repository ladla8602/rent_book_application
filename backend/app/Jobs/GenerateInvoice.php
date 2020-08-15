<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PDF;

class GenerateInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data, $filePath;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $filePath)
    {
        $this->data = $data;
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->data;
        $pdf = PDF::loadView('invoice', compact('data'));
        $pdf->setPaper('a4', 'landscape')->setWarnings(false)->save($this->filePath);
    }
}
