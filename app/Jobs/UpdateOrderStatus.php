<?php

namespace App\Jobs;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateOrderStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle()
    {
        $nextStatus = $this->getNextStatus($this->order->status);

        if ($nextStatus) {
            $this->order->update(['status' => $nextStatus]);
            // Dispatch the job again for the next status update
            UpdateOrderStatus::dispatch($this->order)->delay(now()->addMinute());
        }
    }

    private function getNextStatus($currentStatus)
    {
        $statuses = ['pending', 'prepring', 'completed','On Way!','On Door']; 
        $currentIndex = array_search($currentStatus, $statuses);

        return $currentIndex !== false && isset($statuses[$currentIndex + 1])
            ? $statuses[$currentIndex + 1]
            : null;
    }
}
