<div class="fixed bottom-5 right-5 z-40 flex flex-col items-end gap-3">
    @if ($open)
        <div class="flex h-[28rem] w-[22rem] max-w-[calc(100vw-2.5rem)] flex-col overflow-hidden border border-line bg-cream shadow-lg">
            <div class="flex items-center justify-between gap-3 bg-ink px-5 py-4 text-cream">
                <div>
                    <p class="font-serif text-lg">Luma Lens Assistant</p>
                    <p class="text-xs text-cream/70">Usually replies within a day</p>
                </div>
                <button type="button" wire:click="toggle" aria-label="Close chat" class="grid h-8 w-8 place-items-center hover:opacity-70">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" /></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-5 py-4">
                @if ($screen === 'menu')
                    <p class="text-sm text-stone">Hi! What can we help you with?</p>
                    <div class="mt-4 grid gap-2">
                        @foreach ($faqs as $key => $faq)
                            <button type="button" wire:click="showTopic('{{ $key }}')" class="motion-press border border-line px-4 py-3 text-left text-sm text-ink hover:border-ink">
                                {{ $faq['question'] }}
                            </button>
                        @endforeach
                        <button type="button" wire:click="showContactForm" class="motion-press border border-dashed border-stone-light px-4 py-3 text-left text-sm text-accent hover:border-accent">
                            Talk to a human &rarr;
                        </button>
                    </div>
                @elseif ($screen === 'answer' && $topic)
                    <button type="button" wire:click="backToMenu" class="motion-press text-xs font-medium uppercase tracking-wide text-accent hover:underline">&larr; Back</button>
                    <p class="mt-3 font-serif text-lg text-ink">{{ $faqs[$topic]['question'] }}</p>
                    <p class="mt-2 text-sm leading-relaxed text-stone">{{ $faqs[$topic]['answer'] }}</p>
                    @if ($topic === 'track')
                        <x-ui.button :href="route('track.create')" size="sm" class="mt-4">Go to order tracking</x-ui.button>
                    @endif
                    <button type="button" wire:click="showContactForm" class="motion-press mt-4 block text-xs font-medium uppercase tracking-wide text-accent hover:underline">Still need help? Message us &rarr;</button>
                @elseif ($screen === 'contact')
                    <button type="button" wire:click="backToMenu" class="motion-press text-xs font-medium uppercase tracking-wide text-accent hover:underline">&larr; Back</button>

                    @if ($sent)
                        <div class="mt-4 border border-line bg-success-tint p-4 text-sm text-success">
                            <p class="font-medium">Message sent.</p>
                            <p class="mt-1">We will get back to you by email soon.</p>
                        </div>
                    @else
                        <form wire:submit="send" class="mt-3 grid gap-3">
                            <label class="grid gap-1 text-xs font-medium text-stone">
                                Name
                                <input type="text" wire:model="name" class="border-0 border-b border-line bg-transparent px-0 py-2 text-sm text-ink outline-none focus:border-accent">
                                @error('name') <span class="text-xs text-error">{{ $message }}</span> @enderror
                            </label>
                            <label class="grid gap-1 text-xs font-medium text-stone">
                                Email
                                <input type="email" wire:model="email" class="border-0 border-b border-line bg-transparent px-0 py-2 text-sm text-ink outline-none focus:border-accent">
                                @error('email') <span class="text-xs text-error">{{ $message }}</span> @enderror
                            </label>
                            <label class="grid gap-1 text-xs font-medium text-stone">
                                Message
                                <textarea wire:model="message" rows="3" class="border border-line bg-transparent px-3 py-2 text-sm text-ink outline-none focus:border-accent"></textarea>
                                @error('message') <span class="text-xs text-error">{{ $message }}</span> @enderror
                            </label>
                            <x-ui.button type="submit" size="sm" wire:loading.attr="disabled">Send message</x-ui.button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    @endif

    <button type="button" wire:click="toggle" aria-label="{{ $open ? 'Close chat' : 'Open chat' }}" class="motion-press grid h-14 w-14 place-items-center rounded-full bg-ink text-cream shadow-lg">
        @if ($open)
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" /></svg>
        @else
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3h11A2.5 2.5 0 0 1 20 5.5v9a2.5 2.5 0 0 1-2.5 2.5H10l-4.5 4v-4H6.5A2.5 2.5 0 0 1 4 14.5v-9Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" /></svg>
        @endif
    </button>
</div>
