<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <flux:navlist>
            <flux:navlist.item :href="route('profile.edit')" wire:navigate>{{ __('Thông tin cá nhân') }}</flux:navlist.item>
            <flux:navlist.item :href="route('user-password.edit')" wire:navigate>{{ __('Mật khẩu') }}</flux:navlist.item>
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <flux:navlist.item :href="route('two-factor.show')" wire:navigate>{{ __('Bảo mật hai lớp') }}</flux:navlist.item>
            @endif
            <flux:navlist.item :href="route('appearance.edit')" wire:navigate>{{ __('Giao diện') }}</flux:navlist.item>
        </flux:navlist>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <div class="flex items-center gap-4 mb-4">
            <button onclick="window.history.back()" class="inline-flex items-center justify-center p-2 rounded-md text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-800">
                <span class="material-symbols-outlined">arrow_back</span>
            </button>
            <div>
                <flux:heading>{{ $heading ?? '' }}</flux:heading>
                <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>
            </div>
        </div>

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>

