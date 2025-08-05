@component('Illuminate\Mail\Markdown::message')

# Hello
{{-- contact us email template --}}
@endcomponent

@component('Illuminate\Mail\Markdown::panel')
**Title:**
 {{ $contactData['title'] }}

**Description:**  
{{ $contactData['description'] }}

**User ID:** {{ $contactData['user_id'] }}
@endcomponent