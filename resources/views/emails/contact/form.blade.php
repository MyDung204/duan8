<x-mail::message>
# New Contact Form Submission

You have received a new message from your website's contact form.

**Name:** {{ $formData['name'] }}
**Email:** {{ $formData['email'] }}
**Subject:** {{ $formData['subject'] }}

---

**Message:**

{{ $formData['message'] }}

</x-mail::message>
