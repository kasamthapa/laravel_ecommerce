<div class="fixed bottom-5 right-5 z-40 flex flex-col items-end gap-3">
    @if ($open)
        <div class="flex h-[28rem] w-[22rem] max-w-[calc(100vw-2.5rem)] flex-col overflow-hidden border border-hairline bg-charcoal shadow-lg">
            <div class="flex items-center justify-between gap-3 bg-black px-5 py-4 text-bone">
                <div>
                    <p class="font-display text-lg font-semibold">Luma Lens Assistant</p>
                    <p class="text-xs text-bone/70">Usually replies within a day</p>
                </div>
                <button type="button" wire:click="toggle" aria-label="Close chat" class="grid h-8 w-8 place-items-center hover:text-volt">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" /></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-5 py-4">
                @if ($screen === 'menu')
                    <p class="text-sm text-smoke">Hi! What can we help you with?</p>
                    <div class="mt-4 grid gap-2">
                        @foreach ($faqs as $key => $faq)
                            <button type="button" wire:click="showTopic('{{ $key }}')" class="motion-invert border border-hairline px-4 py-3 text-left text-sm text-bone hover:border-volt">
                                {{ $faq['question'] }}
                            </button>
                        @endforeach
                        <button type="button" wire:click="showContactForm" class="motion-invert border border-dashed border-smoke-dim px-4 py-3 text-left text-sm text-volt hover:border-volt">
                            Talk to a human &rarr;
                        </button>
                    </div>
                @elseif ($screen === 'answer' && $topic)
                    <button type="button" wire:click="backToMenu" class="motion-invert text-xs font-medium uppercase tracking-wide text-volt hover:underline">&larr; Back</button>
                    <p class="mt-3 font-display text-lg font-semibold text-bone">{{ $faqs[$topic]['question'] }}</p>
                    <p class="mt-2 text-sm leading-relaxed text-smoke">{{ $faqs[$topic]['answer'] }}</p>
                    @if ($topic === 'track')
                        <x-ui.button :href="route('track.create')" size="sm" class="mt-4">Go to order tracking</x-ui.button>
                    @endif
                    <button type="button" wire:click="showContactForm" class="motion-invert mt-4 block text-xs font-medium uppercase tracking-wide text-volt hover:underline">Still need help? Message us &rarr;</button>
                @elseif ($screen === 'contact')
                    <button type="button" wire:click="backToMenu" class="motion-invert text-xs font-medium uppercase tracking-wide text-volt hover:underline">&larr; Back</button>

                    @if ($sent)
                        <div class="mt-4 border border-hairline bg-black p-4 text-sm text-signal-good">
                            <p class="font-medium">Message sent.</p>
                            <p class="mt-1">We will get back to you by email soon.</p>
                        </div>
                    @else
                        <form wire:submit="send" class="mt-3 grid gap-3">
                            <label class="grid gap-1 text-xs font-medium text-smoke">
                                Name
                                <input type="text" wire:model="name" class="border-0 border-b border-hairline bg-transparent px-0 py-2 text-sm text-bone outline-none focus:border-volt">
                                @error('name') <span class="text-xs text-signal-bad">{{ $message }}</span> @enderror
                            </label>
                            <label class="grid gap-1 text-xs font-medium text-smoke">
                                Email
                                <input type="email" wire:model="email" class="border-0 border-b border-hairline bg-transparent px-0 py-2 text-sm text-bone outline-none focus:border-volt">
                                @error('email') <span class="text-xs text-signal-bad">{{ $message }}</span> @enderror
                            </label>
                            <label class="grid gap-1 text-xs font-medium text-smoke">
                                Message
                                <textarea wire:model="message" rows="3" class="border border-hairline bg-transparent px-3 py-2 text-sm text-bone outline-none focus:border-volt"></textarea>
                                @error('message') <span class="text-xs text-signal-bad">{{ $message }}</span> @enderror
                            </label>
                            <x-ui.button type="submit" size="sm" wire:loading.attr="disabled">Send message</x-ui.button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    @endif

    <button type="button" wire:click="toggle" aria-label="{{ $open ? 'Close chat' : 'Open chat' }}" class="motion-invert grid h-14 w-14 place-items-center rounded-full bg-volt text-bone shadow-lg hover:bg-bone hover:text-black">
        @if ($open)
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" /></svg>
        @else
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3h11A2.5 2.5 0 0 1 20 5.5v9a2.5 2.5 0 0 1-2.5 2.5H10l-4.5 4v-4H6.5A2.5 2.5 0 0 1 4 14.5v-9Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" /></svg>
        @endif
    </button>
</div>
