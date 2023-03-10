@component('mail::message')
# {{ $details['title'] }}

{{$details['body']}}

Date: {{ $details['date'] }}
From: {{ $details['start_time'] }}
To:   {{ $details['end_time'] }}
Duration(mins): {{ $details['duration'] }}

@component('mail::button', ['url' => $details['link']])
Zoom link
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
