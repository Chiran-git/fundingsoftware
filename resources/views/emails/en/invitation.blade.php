@component('mail::message')

Hello {{ \Str::title($invitation->first_name) }} {{ \Str::title($invitation->last_name) }},

You have been invited by {{ \Str::title($invitee->first_name) }} {{ \Str::title($invitee->last_name) }} to join {{ $organization->name }}.

Please click the below link to accept invitation.

@component('mail::button', ['url' => route('accept-invitation', [
        'organization' => $organization->id,
        'code' => $invitation->code,
    ])])
Accept
@endcomponent

Thanks,<br>
The {{ config('app.name') }} Team
@endcomponent
