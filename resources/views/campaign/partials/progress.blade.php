<div class="w-85">
    <div class="d-flex justify-content-between mb-1">
        <div class="aleo f-20 amt"><span class="font-weight-bold">
            {{ RJ::donationMoney($campaign->funds_raised, $organization->currency->symbol) }}
        </span> {{ __('of') }} {{ RJ::donationMoney($campaign->fundraising_goal, $organization->currency->symbol) }}</div>
        @if ($campaign->end_date)
            @if ($campaign->end_date < now() )
                <div class="f-14 mt-2 aleo days">{{ __('Ended ').$campaign->end_date->diffForHumans(now(), \Carbon\CarbonInterface::DIFF_ABSOLUTE) . ' ' . __('ago') }}</div>
            @else
                <div class="f-14 mt-2 aleo days">{{ $campaign->end_date->diffForHumans(now(), \Carbon\CarbonInterface::DIFF_ABSOLUTE) . ' ' . __('left') }}</div>
            @endif
        @endif
    </div>
    <div class="position-relative">
        <div class="progress rounded-pill">
            @php
                $progress = round(($campaign->funds_raised / $campaign->fundraising_goal) * 100);
            @endphp
            <div role="progressbar" aria-valuemin="0" aria-valuemax="100" class="progress-bar" style="width: {{ $progress }}%"></div>
        </div>
        <div class="ref aleo f-34">{{ $progress }}<div class="d-inline percent">%</div></div>
    </div>
    @if ($campaign->total_donations)
    <div class="d-flex">
        <div class="f-14 font-weight-bold sub__text mt-4 mt-sm-1">{{ __('Raised by :count donors', ['count' => $campaign->total_donations]) }} @if ($campaign->published_at)<div class="d-inline font-weight-normal">{{ __('since :date', ['date' => $campaign->published_at->format('m/d/y')]) }}</div>@endif</div>
    </div>
    @endif
</div>
