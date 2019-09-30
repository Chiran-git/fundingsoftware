@component('mail::organization-message')
<!-- Email Body -->
        <table class="inner-body" align="center" width="700" cellpadding="0" cellspacing="0" role="presentation">
            <!-- Body content -->
            <tr>
                <td class="bg-light-gray p-t-b-20 p-r-l-25">
                    <table class="bg-white border-rounded-6 p-t-b-30 p-r-l-60" width="660">
                        <tr>
                            <td class="p-t-b-10 border-bottom color-black">
                                <img src="{{ RJ::assetCdn($organization->logo) }}" alt="" class="border-rounded-100 w-h-35">
                                <strong class="m-l-10 l-h-35 v-a-top">
                                    {{$organization->name}}
                                </strong>
                            </td>
                        </tr>

                        <tr>
                            <td class="p-t-b-20">
                                <h2 class="color-black m-b-0 assistant f-24">{{ __('Thank you, :name!', ['name' => $donor->first_name]) }}</h2>
                                <p class="color-black">{{ \Illuminate\Mail\Markdown::parse($campaign->donor_message) }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="w-100">
                                <table class="w-100 border-all-around border-rounded-6">
                                    <tr>
                                        <td class="p-30">
                                            <h2 class="f-24 color-black m-b-0">{{ $campaign->name }}</h2>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="bg-light-gray border-rounded-6 p-30 w-100">
                                            <table class="w-100">
                                                <tr>
                                                    <td class="p-b-10">
                                                        <strong class="color-black">
                                                            {{ __('Your :amount contribution brings our total raised to :total_donation!', [
                                                                'amount' => RJ::donationMoney($donation->gross_amount, $donation->currency->symbol),
                                                                'total_donation' => RJ::donationMoney($campaign->funds_raised, $donation->currency->symbol)
                                                            ]) }}
                                                        </strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <span class="color-red">{{ RJ::donationMoney($campaign->funds_raised, $donation->currency->symbol) }}</span> <span class="color-black">of {{ RJ::donationMoney($campaign->fundraising_goal,$donation->currency->symbol) }}</span>
                                                    </td>
                                                </tr>
                                                @php
                                                $progress = round(($campaign->funds_raised / $campaign->fundraising_goal) * 100);
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <span class="bg-white w-85 h-20 border-rounded-30 float-left m-r-20">
                                                            <span class="bg-red h-20 border-rounded-30 d-i-block" style="width:{{ $progress > 100 ? 100 : $progress }}%;"></span>
                                                        </span>
                                                        <strong class="color-black">{{ $progress }}%</strong>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-100 color-black p-t-b-20 p-r-l-30">
                                            <table class="w-100">
                                                <tr>
                                                    <td class="w-50 v-a-top">
                                                        <p class="m-b-10 color-black f-14">
                                                            <strong>{{ __('DATE') }}</strong>
                                                            <span class="d-block">{{ $donation->created_at->format('m/d/Y \a\t h:i a') }} UTC</span>
                                                        </p>
                                                        <p class="m-b-10 color-black f-14">
                                                            <strong>{{ __('FROM') }}</strong>
                                                            <span class="d-block">{{ $donor->name}}</span>
                                                        </p>
                                                        <p class="m-b-10 color-black f-14">
                                                            <strong>{{ __('DONATION AMOUNT') }}</strong>
                                                            <span class="d-block">{{ RJ::donationMoney($donation->gross_amount, $donation->currency->symbol) }}</span>
                                                        </p>
                                                        <p class="m-b-10 color-black f-14">
                                                            <strong>{{ __('METHOD') }}</strong>
                                                            <span class="d-block">{{ $donation->entry_type }}
                                                                @if ($donation->entry_type == 'online')({{ $donation->card_brand}} {{ $donation->card_last_four}})@endif</span>
                                                        </p>
                                                    </td>
                                                    <td class="w-50 v-a-top">
                                                        <p class="m-b-10 color-black f-14">
                                                            <strong>{{ __('RECEIPT NO.') }}</strong>
                                                            <span class="d-block">{{ $donation->receipt_number }}</span>
                                                        </p>
                                                        <p class="m-b-10 color-black f-14">
                                                            <strong>{{ __('TO') }}</strong>
                                                            <span class="d-block">
                                                                {{ $campaign->name }}<br>
                                                                {{ $organization->name }}<br>
                                                                {{ $organization->address1 }}<br>
                                                                @if ($organization->address2)
                                                                {{ $organization->address2 }}<br>
                                                                @endif
                                                                {{ $organization->city }}, {{ $organization->state }} {{ $organization->zipcode }}</span>
                                                        </p>
                                                        @if ($donation->reward)
                                                        <p class="m-b-10 color-black f-14">
                                                            <strong>{{ __('REWARD') }}</strong>
                                                            <span class="d-block">{{ $donation->reward->campaign_reward->title }}</span>
                                                        </p>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td class="w-100">
                                <table class="w-100 m-t-b-30">
                                    <tr>
                                        <td>
                                            <h2 class="text-center f-24 m-b-0 color-black">{{ __('Tell your friends:') }}</h2>
                                        </td>
                                    </tr>
                                    @php
                                        $shareUrl = route('campaign.show', ['orgSlug' => $organization->slug, 'campSlug' => $campaign->slug]);
                                        $shareText = __('Fund projects that matter :url', ['url' => $shareUrl]);
                                    @endphp
                                    <tr>
                                        <td class="text-center">
                                            <a href="{{ RJ::fbShareUrl($shareUrl) }}" target="_blank"><img src="{{ asset('images/facebook-share.png') }}" alt="Facebook" class="m-r-5"></a>
                                            <a href="{{ RJ::tweetUrl($shareText) }}" target="_blank"><img src="{{ asset('images/twitter-share.png') }}" alt="Twitter" class="m-r-5"></a>
                                            <a href="{{ $shareUrl }}" target="_blank"><img src="{{ asset('images/link-share.png') }}" alt="Share" class="m-r-5"></a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td class="bg-light-gray text-center p-t-20 p-b-50">
                    <span class="f-14 color-gray">&copy; {{ date('Y') }} {!! __('RocketJar. All rights reserved.') !!}</span>
                </td>
            </tr>
        </table>
@endcomponent
