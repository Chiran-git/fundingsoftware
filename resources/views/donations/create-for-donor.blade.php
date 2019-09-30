@extends('layouts.app')
@section('title')
{{ __('Donate to :campaign', ['campaign' => $campaign->name]) }}
@endsection
@section('content')
@php
// Get the active donor questions for this org
$donorQuestions = $organization->donorQuestions()->enabled()->get();
@endphp
<make-donation inline-template
    :organization="{{ $organization }}"
    :campaign="{{ $campaign }}"
    :reward="{{ $reward ?: '{}' }}"
    :questions="{{ $donorQuestions ?: '{}' }}"
    :country="{{ $country }}">
    <div class="row">
        <div class="col-lg-7 m-lg-auto px-lg-0">
            <div class="my-5">
                <h2 class="aleo mb-0">{{ __('Your Donation') }}</h2>
            </div>

            <div class="donor-box mb-4">
                <div class="mb-3 d-flex">
                    <label class="aleo">{{ __('Campaign') }}</label>
                    <div class="d-flex justify-content-between w-100 break-word">
                        {{ $campaign->name }}<br>
                        {{ $organization->name }}
                        <a class="cta f-12" href="{{ route('campaign.donate', [
                            'orgSlug' => $organization->slug,
                            'campSlug' => $campaign->slug,
                            'amount' => empty($reward) ? request()->amount : null,
                        ]) }}">{{ __('Edit Donation') }}</a>
                    </div>
                </div>

                <div class="mb-3 d-flex">
                    <label class="aleo">{{ __('Amount') }}</label>
                    <div>{{ RJ::donationMoney($reward ? $reward->min_amount : request()->amount, $organization->currency->symbol) }}</div>
                </div>
                @if ($reward)
                <div class="d-flex">
                    <label class="aleo">{{ __('Reward') }}</label>
                    <div>{{ $reward->title }}</div>
                </div>
                @endif
            </div>

            <div class="donor-form mb-4">
                <form method="post" @submit.prevent="submit">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="asterisk">{{ __('Your First Name') }}</label>
                            <input type="text" class="form-control" placeholder="{{ __('Your First Name') }}"
                                v-model="form.first_name" :class="{'is-invalid': form.errors.has('first_name')}">
                            <span class="invalid-feedback" v-show="form.errors.has('first_name')">
                                @{{ form.errors.get('first_name') }}
                            </span>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="asterisk">{{ __('Your Last Name') }}</label>
                            <input type="text" class="form-control" placeholder="{{ __('Your Last Name') }}"
                                v-model="form.last_name" :class="{'is-invalid': form.errors.has('last_name')}">
                            <span class="invalid-feedback" v-show="form.errors.has('last_name')">
                                @{{ form.errors.get('last_name') }}
                            </span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label class="asterisk">{{ __('Your Email Address') }}</label>
                            <input type="email" class="form-control" placeholder="{{ __('Your Email Address') }}"
                                v-model="form.email" :class="{'is-invalid': form.errors.has('email')}">
                            <span class="invalid-feedback" v-show="form.errors.has('email')">
                                @{{ form.errors.get('email') }}
                            </span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label class="asterisk text-transform-none">{{ __('Name on Card') }}</label>
                            <input type="text" class="form-control" placeholder="{{ __('Name on Card') }}"
                                v-model="cardForm.name" :class="{'is-invalid': form.errors.has('card_name')}">
                            <span class="invalid-feedback" v-show="form.errors.has('card_name')">
                                @{{ form.errors.get('card_name') }}
                            </span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label class="asterisk">{{ __('Credit Card Number') }}</label>
                            <div id="card-element" class="cardinput form-control"
                                :class="{'is-invalid': form.errors.has('card')}"></div>
                            <span class="invalid-feedback" v-show="form.errors.has('card_element')">
                                @{{ form.errors.get('card') }}
                            </span>
                        </div>
                    </div>
                    {{-- <div class="form-row">
                        <div class="form-group col-md-8">
                            <label class="asterisk">{{ __('Credit Card Number') }}</label>
                            <input type="text" class="form-control" placeholder="**** **** ****">
                        </div>
                        <div class="form-group col-md-4 credit-cards">
                            <label class="w-100 d-none d-md-block">&nbsp;</label>
                            <img src="../images/visa.jpg" alt="Visa">
                            <img src="../images/american-express.jpg" alt="American Express">
                            <img src="../images/master-card.jpg" alt="Master Card">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label class="asterisk">{{ __('CVV') }}</label>
                            <input type="text" class="form-control" placeholder="***">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="asterisk">{{ __('Expiration Date') }}</label>
                            <input type="text" class="form-control" placeholder="MM/YY">
                        </div>
                    </div> --}}

                    {{-- Show the system donor questions --}}
                    @if (isset($organization->system_donor_questions->mailing_address)
                        && isset($organization->system_donor_questions->mailing_address->enabled)
                        && $organization->system_donor_questions->mailing_address->enabled)
                        <div class="form-row">
                            @php
                                $mailAddressClass = '';
                                if ($organization->system_donor_questions->mailing_address->required) {
                                    $mailAddressClass = 'asterisk';
                                }
                            @endphp
                            <label class="field_label {{ $mailAddressClass }}">{{ __('Mailing Address')}}</label>
                            <div class="form-group col-12">
                                <input type="text" class="form-control" placeholder="{{ __('Address line1') }}"
                                    v-model="form.mailing_address1" :class="{'is-invalid': form.errors.has('mailing_address1')}">
                                <span class="invalid-feedback" v-show="form.errors.has('mailing_address1')">
                                    @{{ form.errors.get('mailing_address1') }}
                                </span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12">
                                <input type="text" class="form-control" placeholder="{{ __('Address line2') }}"
                                    v-model="form.mailing_address2">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <input type="text" class="form-control" placeholder="{{ __('City') }}"
                                    v-model="form.mailing_city" :class="{'is-invalid': form.errors.has('mailing_city')}">
                                <span class="invalid-feedback" v-show="form.errors.has('mailing_city')">
                                    @{{ form.errors.get('mailing_city') }}
                                </span>
                            </div>
                            <div class="form-group col-4">
                                <v-select :options="stateOptions"
                                    placeholder="{{ __('State') }}"
                                    v-model="form.mailing_state" :class="{'is-invalid': form.errors.has('mailing_state')}">
                                    <template slot="option" slot-scope="option">
                                        @{{ getStateOptionLabel(option.label) }}
                                    </template>
                                    <template slot="selected-option" slot-scope="option">
                                        @{{ getStateOptionLabel(option.label) }}
                                    </template>
                                </v-select>
                                <input type="hidden" class="form-control is-invalid">
                                <span class="invalid-feedback" v-show="form.errors.has('mailing_state')">
                                    @{{ form.errors.get('mailing_state') }}
                                </span>
                            </div>
                            <div class="form-group col-2">
                                <input type="text" class="form-control" placeholder="{{ __('Zip') }}"
                                    v-model="form.mailing_zipcode" :class="{'is-invalid': form.errors.has('mailing_zipcode')}">
                                <span class="invalid-feedback" v-show="form.errors.has('mailing_zipcode')">
                                    @{{ form.errors.get('mailing_zipcode') }}
                                </span>
                            </div>
                        </div>
                    @endif

                    @if (isset($organization->system_donor_questions->comment)
                        && isset($organization->system_donor_questions->comment->enabled)
                        && $organization->system_donor_questions->comment->enabled)
                        <div class="form-row">
                            @php
                                $commentClass = '';
                                if ($organization->system_donor_questions->comment->required) {
                                    $commentClass = 'asterisk';
                                }
                            @endphp
                            <label class="field_label {{ $commentClass }}">{{ __('Comment')}}</label>
                            <div class="form-group col-12">
                                <textarea class='form-control' placeholder='{{ __("Comment") }}'
                                    v-model="form.comments"
                                    :class="{'is-invalid': form.errors.has('comments')}"></textarea>

                                <span class="invalid-feedback" v-show="form.errors.has('comments')">
                                    @{{ form.errors.get('comments') }}
                                </span>
                            </div>
                        </div>
                    @endif

                    {{-- Show the enabled Donor questions --}}
                    @foreach ($donorQuestions as $donorQuestion)
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label class="{{ $donorQuestion->is_required ? 'asterisk' : '' }}">{{ $donorQuestion->question }}
                                    @if (! $donorQuestion->is_required)
                                        <span class="optional">{{ __('(Optional)') }}</span>
                                    @endif
                                </label>
                                <input type="text" class="form-control" placeholder="{{ $donorQuestion->question }}"
                                    v-model="form.question_{{ $donorQuestion->id }}" :class="{'is-invalid': form.errors.has('question_{{$donorQuestion->id}}')}">
                                <span class="invalid-feedback" v-show="form.errors.has('question_{{$donorQuestion->id}}')">
                                    @php
                                        // Doing it like this because we want a variable field name in js
                                        echo "{{ form.errors.get('question_{$donorQuestion->id}') }}";
                                    @endphp
                                </span>
                            </div>
                        </div>
                    @endforeach
                    @include('partials.common.button-with-loading', [
                        'title' => __('Donate Now'),
                        'busyCondition' => 'form.busy',
                        'buttonClass' => 'w-100'
                    ])
                </form>
            </div>

            <div class="d-flex justify-content-between">
                <div class="f-12">
                    <span class="d-block">{!! __('By donating, you agree to RocketJar\'s <a href="#" class="text-link--red"><u>Terms and Conditions of Use</u></a>.') !!}</span>
                    <span class="d-block">{{ __('Payments are processed securely using Stripe.') }}</span>
                </div>
                <div>
                    <img src="{{ asset('/images/secure-payments.png') }}" alt="Secure Payments">
                </div>
            </div>

        </div>
    </div>
</make-donation>
@endsection
