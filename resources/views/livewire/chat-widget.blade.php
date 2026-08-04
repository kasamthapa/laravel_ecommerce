<div class="fixed bottom-5 right-5 z-40 flex flex-col items-end gap-3">
    @if ($open)
        <div class="flex h-[28rem] w-[22rem] max-w-[calc(100vw-2.5rem)] flex-col overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-2xl">
            <div class="flex items-center justify-between gap-3 bg-[#092b83] px-5 py-4 text-white">
                <div>
                    <p class="font-black">Luma Lens Assistant</p>
                    <p class="text-xs text-white/70">Usually replies within a day</p>
                </div>
                <button type="button" wire:click="toggle" aria-label="Close chat" class="grid h-8 w-8 place-items-center rounded-full hover:bg-white/10">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" /></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-5 py-4">
                @if ($screen === 'menu')
                    <p class="text-sm text-zinc-600">Hi! What can we help you with?</p>
                    <div class="mt-4 grid gap-2">
                        @foreach ($faqs as $key => $faq)
                            <button type="button" wire:click="showTopic('{{ $key }}')" class="rounded-xl border border-zinc-200 px-4 py-3 text-left text-sm font-bold text-zinc-800 hover:border-[#092b83] hover:bg-[#eef1fb]">
                                {{ $faq['question'] }}
                            </button>
                        @endforeach
                        <button type="button" wire:click="showContactForm" class="rounded-xl border border-dashed border-zinc-300 px-4 py-3 text-left text-sm font-bold text-[#092b83] hover:border-[#092b83]">
                            Talk to a human &rarr;
                        </button>
                    </div>
                @elseif ($screen === 'answer' && $topic)
                    <button type="button" wire:click="backToMenu" class="text-xs font-black uppercase text-[#092b83] hover:underline">&larr; Back</button>
                    <p class="mt-3 font-black text-zinc-950">{{ $faqs[$topic]['question'] }}</p>
                    <p class="mt-2 text-sm leading-6 text-zinc-600">{{ $faqs[$topic]['answer'] }}</p>
                    @if ($topic === 'track')
                        <a href="{{ route('track.create') }}" class="motion-press mt-4 inline-flex rounded-full bg-[#092b83] px-4 py-2 text-xs font-black text-white hover:bg-zinc-950">Go to order tracking</a>
                    @endif
                    <button type="button" wire:click="showContactForm" class="mt-4 block text-xs font-black uppercase text-[#092b83] hover:underline">Still need help? Message us &rarr;</button>
                @elseif ($screen === 'contact')
                    <button type="button" wire:click="backToMenu" class="text-xs font-black uppercase text-[#092b83] hover:underline">&larr; Back</button>

                    @if ($sent)
                        <div class="mt-4 rounded-xl bg-emerald-50 p-4 text-sm text-emerald-800">
                            <p class="font-black">Message sent.</p>
                            <p class="mt-1">We will get back to you by email soon.</p>
                        </div>
                    @else
                        <form wire:submit="send" class="mt-3 grid gap-3">
                            <label class="grid gap-1 text-xs font-bold text-zinc-600">
                                Name
                                <input type="text" wire:model="name" class="rounded-lg border border-zinc-300 px-3 py-2 text-sm font-medium outline-none focus:border-[#092b83]">
                                @error('name') <span class="text-xs font-bold text-red-600">{{ $message }}</span> @enderror
                            </label>
                            <label class="grid gap-1 text-xs font-bold text-zinc-600">
                                Email
                                <input type="email" wire:model="email" class="rounded-lg border border-zinc-300 px-3 py-2 text-sm font-medium outline-none focus:border-[#092b83]">
                                @error('email') <span class="text-xs font-bold text-red-600">{{ $message }}</span> @enderror
                            </label>
                            <label class="grid gap-1 text-xs font-bold text-zinc-600">
                                Message
                                <textarea wire:model="message" rows="3" class="rounded-lg border border-zinc-300 px-3 py-2 text-sm font-medium outline-none focus:border-[#092b83]"></textarea>
                                @error('message') <span class="text-xs font-bold text-red-600">{{ $message }}</span> @enderror
                            </label>
                            <button type="submit" wire:loading.attr="disabled" class="motion-press rounded-full bg-[#092b83] px-4 py-2.5 text-sm font-black text-white hover:bg-zinc-950 disabled:opacity-60">Send message</button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    @endif

    <button type="button" wire:click="toggle" aria-label="{{ $open ? 'Close chat' : 'Open chat' }}" class="motion-press grid h-14 w-14 place-items-center rounded-full bg-[#092b83] text-white shadow-xl hover:bg-zinc-950">
        @if ($open)
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" /></svg>
        @else
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3h11A2.5 2.5 0 0 1 20 5.5v9a2.5 2.5 0 0 1-2.5 2.5H10l-4.5 4v-4H6.5A2.5 2.5 0 0 1 4 14.5v-9Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" /></svg>
        @endif
    </button>
</div>
