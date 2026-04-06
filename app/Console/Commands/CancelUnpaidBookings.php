<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Illuminate\Support\Carbon;
use App\Enums\BookingStatus;

class CancelUnpaidBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:cancel-unpaid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically cancel unpaid bookings after 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cutoffTime = Carbon::now()->subHours(24);

        $count = Booking::where('status', BookingStatus::PENDING_PAYMENT)
            ->where('created_at', '<', $cutoffTime)
            ->update([
                'status' => BookingStatus::CANCELLED,
                'cancelled_at' => now(),
            ]);

        $this->info("Cancelled {$count} unpaid bookings created before {$cutoffTime}");
    }
}
