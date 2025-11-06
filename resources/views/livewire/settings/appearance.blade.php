<?php

use Livewire\Volt\Component;

new class extends Component {
    protected static string $layout = 'layouts.user-settings-page';
    //
}; ?>

<section class="w-full">
    @include('partials.shared.settings-heading')

    <x-settings.layout :heading="__('Giao diện')" :subheading="__('Cập nhật giao diện cho tài khoản của bạn')">
        <flux:radio.group x-data x-init="$flux.appearance = 'dark'" variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">{{ __('Sáng') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('Tối') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ __('Theo hệ thống') }}</flux:radio>
        </flux:radio.group>
    </x-settings.layout>
</section>
