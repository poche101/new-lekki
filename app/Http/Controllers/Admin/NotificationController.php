<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationTopic;
use App\Models\PushNotification;
use App\Models\PushSubscription;
use Illuminate\Http\Request;

/**
 * Composing and "sending" here creates real rows (push_notifications +
 * notification_deliveries) and is real once subscribers exist. Actually
 * delivering to a browser needs VAPID keys and a web-push library — this
 * queues deliveries as 'queued' rather than calling a push service. See
 * README for the two lines that turn this into real delivery.
 */
class NotificationController extends Controller
{
    public function index()
    {
        return view('admin.notifications.index', [
            'topics' => NotificationTopic::orderBy('name')->get(),
            'history' => PushNotification::with('topic')->withCount('deliveries')->latest()->limit(15)->get(),
            'subscriberCount' => PushSubscription::count(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:80'], 'message' => ['required', 'string', 'max:160'],
            'topic_id' => ['nullable', 'exists:notification_topics,id'], 'audience' => ['required', 'in:all,topic,members'],
            'url' => ['nullable', 'string'], 'send_now' => ['nullable', 'boolean'],
        ]);

        $notification = PushNotification::create($data + ['created_by' => $request->user()->id, 'sent_at' => $request->boolean('send_now') ? now() : null]);

        if ($request->boolean('send_now')) {
            PushSubscription::query()->pluck('id')->each(fn ($id) => $notification->deliveries()->create(['push_subscription_id' => $id, 'status' => 'queued']));
        }

        return back()->with('status', $request->boolean('send_now') ? 'Sent to ' . PushSubscription::count() . ' subscribers.' : 'Notification scheduled.');
    }

    public function storeTopic(Request $request)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:60', 'unique:notification_topics,name']]);
        NotificationTopic::create($data);

        return back()->with('status', 'Topic added.');
    }
}
