<x-mail::message>
# Thông báo liên hệ mới

Kính gửi quản trị viên,

Bạn vừa nhận được một thông báo liên hệ mới từ trang web của bạn. Dưới đây là chi tiết:

**Tên:** {{ $contact->name }}
**Email:** {{ $contact->email }}
**Chủ đề:** {{ $contact->subject }}
**Nội dung tin nhắn:**
{{ $contact->message }}

Trân trọng,
{{ config('app.name') }}
</x-mail::message>
