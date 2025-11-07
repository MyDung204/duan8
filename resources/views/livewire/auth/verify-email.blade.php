<x-layouts.auth>
    <div class="mt-4 flex flex-col gap-6">
        <flux:text class="text-center">
            {{ __('Vui lòng xác minh địa chỉ email của bạn bằng cách nhấp vào liên kết chúng tôi vừa gửi đến email của bạn.') }}
        </flux:text>

        @if (session('status') == 'verification-link-sent')
            <flux:text class="text-center font-medium !dark:text-green-400 !text-green-600">
                {{ __('Một liên kết xác minh mới đã được gửi đến địa chỉ email bạn đã cung cấp khi đăng ký.') }}
            </flux:text>
        @endif

        <div class="flex flex-col items-center justify-between space-y-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <flux:button type="submit" variant="primary" class="w-full">
                    {{ __('Gửi lại email xác minh') }}
                </flux:button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <flux:button variant="ghost" type="submit" class="text-sm cursor-pointer" data-test="logout-button">
                    {{ __('Đăng xuất') }}
                </flux:button>
            </form>
        </div>
    </div>
</x-layouts.auth>
