<x-layouts.admin title="Messages - Luma Lens Admin">
    <x-admin.page-header title="Messages" :subtitle="$messages->total().' messages from the chat widget.'" />

    <div class="mt-6 grid gap-4">
        @forelse ($messages as $chatMessage)
            <div class="rounded-2xl border border-line bg-cream p-6 shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="font-black text-ink">{{ $chatMessage->name }}</p>
                        <p class="text-sm text-stone">{{ $chatMessage->email }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        @if ($chatMessage->topic)
                            <span class="rounded-full bg-accent-tint px-3 py-1 text-xs font-black uppercase text-accent">{{ ucfirst($chatMessage->topic) }}</span>
                        @endif
                        <span class="text-xs text-stone-light">{{ $chatMessage->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
                <p class="mt-4 text-sm leading-6 text-ink-soft">{{ $chatMessage->message }}</p>
                <form method="POST" action="{{ route('admin.chat-messages.destroy', $chatMessage) }}" class="mt-4" onsubmit="return confirm('Delete this message?');">
                    @csrf
                    @method('DELETE')
                    <button class="text-xs font-black uppercase text-error hover:underline">Delete</button>
                </form>
            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-line bg-cream p-10 text-center text-stone">No messages yet.</div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $messages->links() }}
    </div>
</x-layouts.admin>
