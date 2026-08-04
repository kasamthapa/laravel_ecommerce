<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ChatMessageController extends Controller
{
    public function index(): View
    {
        $messages = ChatMessage::latest()->paginate(20);

        ChatMessage::whereNull('read_at')->update(['read_at' => now()]);

        return view('admin.chat-messages.index', [
            'messages' => $messages,
        ]);
    }

    public function destroy(ChatMessage $chatMessage): RedirectResponse
    {
        $chatMessage->delete();

        return redirect()->route('admin.chat-messages.index')->with('status', 'Message deleted.');
    }
}
