<x-layouts.admin title="Messages - Luma Lens Admin">
    <x-admin.page-header title="Messages" :subtitle="$messages->total().' messages from the chat widget.'" />

    <div class="mt-6 grid gap-4">
        @forelse ($messages as $chatMessage)
            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="font-black text-zinc-950">{{ $chatMessage->name }}</p>
                        <p class="text-sm text-zinc-500">{{ $chatMessage->email }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        @if ($chatMessage->topic)
                            <span class="rounded-full bg-[#eef1fb] px-3 py-1 text-xs font-black uppercase text-[#092b83]">{{ ucfirst($chatMessage->topic) }}</span>
                        @endif
                        <span class="text-xs text-zinc-400">{{ $chatMessage->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
                <p class="mt-4 text-sm leading-6 text-zinc-700">{{ $chatMessage->message }}</p>
                <form method="POST" action="{{ route('admin.chat-messages.destroy', $chatMessage) }}" class="mt-4" onsubmit="return confirm('Delete this message?');">
                    @csrf
                    @method('DELETE')
                    <button class="text-xs font-black uppercase text-red-700 hover:underline">Delete</button>
                </form>
            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-zinc-300 bg-white p-10 text-center text-zinc-500">No messages yet.</div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $messages->links() }}
    </div>
</x-layouts.admin>
