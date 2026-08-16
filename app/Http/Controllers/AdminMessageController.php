<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $conversations = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver', 'job'])
            ->latest()
            ->get()
            ->groupBy(function ($message) use ($user) {
                return $message->sender_id === $user->id
                    ? $message->receiver_id
                    : $message->sender_id;
            })
            ->map(function ($messages) {
                return $messages->first();
            })
            ->sortByDesc('created_at');

        return view('admin.messages.index', compact('conversations'));
    }

    public function create(Request $request)
    {
        $recipientId = $request->query('to');
        $recipient = $recipientId ? User::find($recipientId) : null;
        $job = $request->query('job') ? \App\Models\Job::find($request->query('job')) : null;

        return view('admin.messages.create', compact('recipient', 'job'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:5000',
            'job_id' => 'nullable|exists:jobs,id',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $validated['receiver_id'],
            'job_id' => $validated['job_id'] ?? null,
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        Notification::create([
            'user_id' => $validated['receiver_id'],
            'title' => 'New Message',
            'message' => Auth::user()->name . ' sent you a message.',
            'type' => 'message',
            'is_read' => false,
            'link' => route('messages.show', $message),
        ]);

        return redirect()->route('admin.messages.index')
            ->with('success', 'Message sent successfully!');
    }

    public function show(Message $message)
    {
        $user = Auth::user();

        if ($message->sender_id !== $user->id && $message->receiver_id !== $user->id) {
            abort(403);
        }

        $conversation = Message::where(function ($query) use ($message, $user) {
                $query->where('sender_id', $user->id)->where('receiver_id', $message->sender_id === $user->id ? $message->receiver_id : $message->sender_id);
            })
            ->orWhere(function ($query) use ($message, $user) {
                $query->where('sender_id', $message->sender_id === $user->id ? $message->receiver_id : $message->sender_id)->where('receiver_id', $user->id);
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at')
            ->get();

        $otherUserId = $message->sender_id === $user->id ? $message->receiver_id : $message->sender_id;
        $otherUser = User::find($otherUserId);

        return view('admin.messages.show', compact('conversation', 'otherUser'));
    }
}