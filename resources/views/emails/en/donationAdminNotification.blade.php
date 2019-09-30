@component('mail::organization-message')
<!-- Email Body -->
        <table class="inner-body" align="center" width="700" cellpadding="0" cellspacing="0" role="presentation">
            <!-- Body content -->
            <tr>
                <td bgcolor="#f3f3f3" style="padding: 20px 25px;">
                    <table bgcolor="#ffffff" width="660" style="padding: 30px 60px; border-radius:6px;">
                        <tr>
                            <td style="border-bottom:1px solid #e7e7e7; padding: 10px 0;">
                                <img src="{{ RJ::assetCdn($organization->logo) }}" width="35" height="35" alt="" style="border-radius:100%;">
                                <strong style="vertical-align: top; margin-left: 10px;line-height: 35px; color:#000;">
                                    {{$organization->name}}
                                </strong>
                            </td>
                        </tr>

                        <tr>
                            <td width="100%">
                                <p>{{ __('Congratulations!') }}</p>

                                <p>{{ __('You just received a donation of') }} <span class="amount"> {{ RJ::donationMoney($donation->gross_amount, $donation->currency->symbol) }} </span> {{ __('to') }} <a href="{{ url($organization->slug."/".$campaign->slug) }}">{{ $campaign->name }}</a> {{ __('from') }} {{ $donor->first_name .' '.$donor->last_name }} ({{ $donor->email }}).</p> <br/>                       
		                        <p>{{ __('Your payment will settle within 2 days.') }}</p>
                            </td>
                        </tr>

                        <tr>
                            <td width="100%">
                                <table width="100%" align="center" style="margin:30px 0;">
                                    <tr>
                                        <td>
                                            <h2 style="text-align:center; font-size:24px; margin-bottom:0; color:#000;">{{ __('Tell your friends:') }}</h2>
                                        </td>
                                    </tr>
                                    @php
                                        $shareUrl = route('campaign.show', ['orgSlug' => $organization->slug, 'campSlug' => $campaign->slug]);
                                        $shareText = __('Fund projects that matter :url', ['url' => $shareUrl]);
                                    @endphp
                                    <tr>
                                        <td align="center">
                                            <a href="{{ RJ::fbShareUrl($shareUrl) }}" target="_blank"><img src="{{ asset('images/facebook-share.png') }}" alt="Facebook" style="margin-right:10px; width:106px;"></a>
                                            <a href="{{ RJ::tweetUrl($shareText) }}" target="_blank"><img src="{{ asset('images/twitter-share.png') }}" alt="Twitter" style="margin-right:10px; width:106px;"></a>
                                            <a href="{{ $shareUrl }}" target="_blank"><img src="{{ asset('images/link-share.png') }}" alt="Share" style="margin-right:10px; width:106px;"></a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td bgcolor="#f3f3f3" style="padding: 20px 0 50px; text-align:center;">
                    <span style="color:#989898;">&copy; {{ date('Y') }} {!! __('RocketJar. All rights reserved.') !!}</span>
                </td>
            </tr>
        </table>
@endcomponent
