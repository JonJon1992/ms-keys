<?php

namespace MSKeys\Application\Jobs\SendEmail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private array $data
    ) {}

    public function handle()
    {
        logger()->info('Processing job', $this->data);
    }
}
