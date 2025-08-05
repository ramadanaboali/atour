@component('mail::message')

# Hello
{{-- contact us email template --}}
@endcomponent

@component('mail::panel')
**Title:**
 {{ $contactData['title'] }}

**Description:**  
{{ $contactData['description'] }}

**User ID:** {{ $contactData['user_id'] }}
@endcomponent