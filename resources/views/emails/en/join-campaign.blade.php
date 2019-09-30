@component('mail::message')

Hello {{ \Str::title($invitation->first_name) }} {{ \Str::title($invitation->last_name) }},

@php
    $count = count($invitation->campaign_ids);
    $campaignNames = $campaigns->implode('name', ', ');
@endphp
@if ($invitation->role == 'campaign-admin')
You have been assigned as {{ $invitation->role }} of {{ $organization->name }} for {{ str_plural('campaign', $count) }} {{ $campaignNames }} by {{ \Str::title($invitee->first_name) }} {{ \Str::title($invitee->last_name) }}
@else
You have been assigned as {{ $invitation->role }} of {{ $organization->name }} by {{ \Str::title($invitee->first_name) }} {{ \Str::title($invitee->last_name) }}
@endif

Please click the below link to accept invitation and manage {{ str_plural('campaign', $count) }}.

@component('mail::button', ['url' => route('accept-invitation', [
        'organization' => $organization->id,
        'code' => $invitation->code,
    ])])
Accept
@endcomponent

Thanks,<br>
The {{ config('app.name') }} Team
@endcomponent
