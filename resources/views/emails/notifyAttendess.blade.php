@component('mail::message')
# {{ $details['title'] }}

Meeting is about to start
see link below


@component('mail::button', ['url' => $details['link']])
Zoom link
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
