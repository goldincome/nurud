<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Subscriber;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewsletterEmail;

class AdminSubscriberController extends Controller
{
    public function index()
    {
        $subscribers = Subscriber::orderBy('created_at', 'desc')->paginate(10);

        $totalSubscribers = Subscriber::where('is_subscribed', true)->count();
        $totalUnsubscribers = Subscriber::where('is_subscribed', false)->count();
        $thisWeek = Subscriber::where('created_at', '>=', Carbon::now()->startOfWeek())->count();
        $today = Subscriber::whereDate('created_at', Carbon::today())->count();
        $monthly = Subscriber::whereMonth('created_at', Carbon::now()->month)->count();

        return view('admin.subscribers.index', compact(
            'subscribers',
            'totalSubscribers',
            'totalUnsubscribers',
            'thisWeek',
            'today',
            'monthly'
        ));
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'recipients' => 'nullable|array',
            'send_to_all' => 'boolean'
        ]);

        $query = Subscriber::where('is_subscribed', true);

        if (!$request->send_to_all && !empty($request->recipients)) {
            $query->whereIn('id', $request->recipients);
        }

        $subscribers = $query->get();

        if ($subscribers->isEmpty()) {
            return back()->with('error', 'No valid subscribers selected.');
        }

        $successCount = 0;
        $failedSubscribers = [];

        foreach ($subscribers as $subscriber) {
            try {
                Mail::to($subscriber->email)->send(new NewsletterEmail($request->subject, $request->message));
                $successCount++;
            } catch (\Exception $e) {
                $failedSubscribers[] = $subscriber->id;
            }
        }

        $failedCount = count($failedSubscribers);

        if ($failedCount > 0) {
            if ($successCount === 0) {
                return back()->with([
                    'error' => "Failed to send emails to all {$failedCount} targeted subscribers. Please verify your mail settings.",
                    'failed_retry' => [
                        'ids' => $failedSubscribers,
                        'subject' => $request->subject,
                        'message' => $request->message,
                    ]
                ]);
            } else {
                return back()->with([
                    'warning' => "Email sent successfully to {$successCount} subscribers, but failed for {$failedCount} subscribers.",
                    'failed_retry' => [
                        'ids' => $failedSubscribers,
                        'subject' => $request->subject,
                        'message' => $request->message,
                    ]
                ]);
            }
        }

        return back()->with('success', "Email sent to all {$successCount} targeted subscribers successfully.");
    }

    public function destroy($id)
    {
        $subscriber = Subscriber::findOrFail($id);
        $subscriber->delete();

        return back()->with('success', 'Subscriber deleted successfully.');
    }
}
