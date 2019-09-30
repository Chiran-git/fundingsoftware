@extends('layouts.app')

@section('title', __('Record Donation'))

@section('content')
<donation-create
    :organization="currentOrganization" inline-template
    v-if="currentOrganization"
    :mailing-address-enabled="((currentOrganization.system_donor_questions !== null) && ! _.isUndefined(currentOrganization.system_donor_questions.mailing_address) && ! _.isUndefined(currentOrganization.system_donor_questions.mailing_address.enabled) && currentOrganization.system_donor_questions.mailing_address.enabled) ? true : false"
    :mailing-address-required="((currentOrganization.system_donor_questions !== null) && ! _.isUndefined(currentOrganization.system_donor_questions.mailing_address) && ! _.isUndefined(currentOrganization.system_donor_questions.mailing_address.required) && currentOrganization.system_donor_questions.mailing_address.required) ? true : false"
    :comment-enabled="((currentOrganization.system_donor_questions !== null) && ! _.isUndefined(currentOrganization.system_donor_questions.comment) && ! _.isUndefined(currentOrganization.system_donor_questions.comment.enabled) && currentOrganization.system_donor_questions.comment.enabled) ? true : false"
    :comment-required="((currentOrganization.system_donor_questions !== null) && ! _.isUndefined(currentOrganization.system_donor_questions.comment) && ! _.isUndefined(currentOrganization.system_donor_questions.comment.required) && currentOrganization.system_donor_questions.comment.required) ? true : false">
    <section class="section--def">
        <div class="row mb-1">
            <div class="col-12 col-md-6" id="form-headers">
                <div class="px-x-2 mb-4">
                    <h5 class="aleo mb-1">{{ __('Donations')}}</h5>
                    <h2>{{ __('Record Donation')}}</h2>
                </div>
            </div>
        </div>
        <div class="section__content">
            <div class="form-container form-container--small">
                <div class='form_wrapper form_wrapper--primary-alt'>
                    <form method='post' @submit.prevent="submit">
                        <ul class='form_fields'>
                            <li class='field size1 align-top'><label class='field_label assistant'>{{ __("Donor's Name")}}</label>
                                    <div class='input_container input_container_text'>
                                        <input type='text'
                                            placeholder='{{ __("First name")}}'
                                            v-model="form.first_name"
                                            :class="{'is-invalid': form.errors.has('first_name')}"
                                            {{--  :disabled="userExists"  --}}
                                            >
                                    </div>
                                    <span class="invalid-feedback" v-show="form.errors.has('first_name')">
                                        @{{ form.errors.get('first_name') }}
                                    </span>
                                </li>
                                <li class='field size1 align-top'><label class='field_label assistant'>&nbsp; </label>
                                    <div class='input_container input_container_text'>
                                        <input type='text'
                                            placeholder='{{ __("Last name")}}'
                                            v-model="form.last_name"
                                            :class="{'is-invalid': form.errors.has('last_name')}"
                                            {{--  :disabled="userExists"  --}}
                                            >
                                    </div>
                                    <span class="invalid-feedback" v-show="form.errors.has('last_name')">
                                        @{{ form.errors.get('last_name') }}
                                    </span>
                                </li>

                            {{--  <li class='field size2 align-top'><label class='field_label'>{{ __("Donor's Name")}}</label>
                                <div class='input_container input_container_text'>
                                    <input type='text'
                                        placeholder='{{ __("Donor's Name")}}'
                                    >
                                </div>
                            </li>  --}}
                            <li class='field size2 align-top'><label class='field_label'>{{ __("Donor's Email")}}</label>
                                <div class='input_container input_container_text'>
                                    <input type='text'
                                        v-model="form.email"
                                        :class="{'is-invalid': form.errors.has('email')}"
                                        placeholder='{{ __("jone@demo.com")}}'
                                    >
                                </div>
                                <span class="invalid-feedback" v-show="form.errors.has('email')">
                                    @{{ form.errors.get('email') }}
                                </span>
                            </li>
                            <li class='field size1 align-top pr-lg-4'>
                                <label class='field_label'>{{ __('Donation method')}}</label>
                                <div class='input_container_select'>
                                    <v-select :options="paymentMethodOptions"
                                        placeholder="{{ __('Donation method')}}"
                                        v-model="form.donation_method" :class="{'is-invalid': form.errors.has('donation_method')}">
                                        <template slot="option" slot-scope="option">
                                            @{{ getPaymentMethodOptionLabel(option.label) }}
                                        </template>
                                        <template slot="selected-option" slot-scope="option">
                                            @{{ getPaymentMethodOptionLabel(option.label) }}
                                        </template>
                                    </v-select>
                                </div>
                                <span class="invalid-feedback" v-show="form.errors.has('donation_method')">
                                    @{{ form.errors.get('donation_method') }}
                                </span>
                            </li>
                            <li class='field size1 align-top' v-show="form.donation_method == 'check'">
                                <label class='field_label'>{{ __('Check Number')}}</label>
                                <div class='input_container input_container_text'>
                                    <input type='text'
                                    v-model="form.check_number"
                                        placeholder='{{ __("Check Number")}}'
                                    >
                                </div>
                                <span class="invalid-feedback" v-show="form.errors.has('check_number')">
                                    @{{ form.errors.get('check_number') }}
                                </span>
                            </li>
                            <li class='field size2 align-top'><label class='field_label'>{{ __("Donation Amount")}}</label>
                                <div class='input_container input_container_text'>
                                    <input type='text'
                                        v-model="form.gross_amount"
                                        :class="{'is-invalid': form.errors.has('gross_amount')}"
                                        placeholder='{{ __("Amount")}}'
                                    >
                                </div>
                                <span class="invalid-feedback" v-show="form.errors.has('gross_amount')">
                                    @{{ form.errors.get('gross_amount') }}
                                </span>
                            </li>
                            <li class='field size2 align-top mb-3'>
                                <label class='field_label'>{{ __('Campaign')}}</label>
                                <div class='input_container_select'>
                                    <v-select :options="campaignOptions"
                                        placeholder="{{ __('Choose a Campaign...')}}"
                                        v-model="form.campaign_id" :class="{'is-invalid': form.errors.has('campaign_id')}">
                                        <template slot="option" slot-scope="option">
                                            @{{ getCampaignOptionLabel(option.label) }}
                                        </template>
                                        <template slot="selected-option" slot-scope="option">
                                            @{{ getCampaignOptionLabel(option.label) }}
                                        </template>
                                    </v-select>
                                </div>
                                <span class="invalid-feedback" v-show="form.errors.has('campaign_id')">
                                    @{{ form.errors.get('campaign_id') }}
                                </span>
                            </li>
                            <li class='field size2 align-top pb-2'>
                                <label class='field_label'>{{ __('Billing Address')}} <span>{{ __("(Optional)") }}</span></label>
                                <div class='input_container input_container_text'>
                                    <input type='text'
                                        v-model="form.billing_address1"
                                        placeholder='{{ __("Address line1")}}'
                                    >
                                </div>
                            </li>
                            <li class='field size2 align-top pb-2'>
                                <div class='input_container input_container_text'>
                                    <input type='text'
                                        v-model="form.billing_address2"
                                        placeholder='{{ __("Address line2")}}'>
                                </div>
                            </li>
                            <li class='field size1 align-top pb-2 pb-lg-6 mb-3'>
                                <div class='input_container input_container_text'>
                                    <input type='text'
                                        v-model="form.billing_city"
                                        placeholder='{{ __("City")}}'
                                    >
                                </div>
                            </li>
                            <li class='field size4 align-top pb-2 pb-lg-6 mb-3'>
                                <div class='input_container_select'>
                                    <v-select :options="stateOptions"
                                        placeholder="{{ __('State') }}"
                                        v-model="form.billing_state" :class="{'is-invalid': form.errors.has('billing_state')}">
                                        <template slot="option" slot-scope="option">
                                            @{{ getStateOptionLabel(option.label) }}
                                        </template>
                                        <template slot="selected-option" slot-scope="option">
                                            @{{ getStateOptionLabel(option.label) }}
                                        </template>
                                    </v-select>
                                </div>
                            </li>
                            <li class='field size4 align-top pb-lg-6 mb-3'>
                                <div class='input_container input_container_text'>
                                    <input type='text'
                                        v-model="form.billing_zipcode"
                                        placeholder='{{ __("Zip")}}'
                                    >
                                </div>
                            </li>
                            <li class='field size2 align-top' v-if="mailingAddressEnabled">
                                <label class='field_label'>{{ __('Mailing Address')}}
                                    <span v-if="mailingAddressRequired">{{ __("(Required)") }}</span>
                                    <span v-else>{{ __("(Optional)") }}</span>
                                </label>
                                <div class="field_checkbox">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" name="same_as_billing" id="remember"
                                            v-model="sameAsBilling">

                                        <label class="form-check-label" for="remember">
                                            {{ __('Same as Billing Address') }}
                                        </label>
                                    </div>
                                </div>
                            </li>
                            <li class='field size2 align-top pb-2' v-if="mailingAddressEnabled">
                                <div class='input_container input_container_text'>
                                    <input type='text'
                                        v-model="form.mailing_address1"
                                        placeholder='{{ __("Address line1")}}'
                                    >
                                    <span class="invalid-feedback" v-show="form.errors.has('mailing_address1')">
                                        @{{ form.errors.get('mailing_address1') }}
                                    </span>
                                </div>
                            </li>
                            <li class='field size2 align-top pb-2' v-if="mailingAddressEnabled">
                                <div class='input_container input_container_text'>
                                    <input type='text'
                                        v-model="form.mailing_address2"
                                        placeholder='{{ __("Address line2")}}'>
                                </div>
                            </li>
                            <li class='field size1 align-top pb-2 pb-lg-6 mb-3' v-if="mailingAddressEnabled">
                                <div class='input_container input_container_text'>
                                    <input type='text'
                                        v-model="form.mailing_city"
                                        placeholder='{{ __("City")}}'
                                    >
                                    <span class="invalid-feedback" v-show="form.errors.has('mailing_city')">
                                        @{{ form.errors.get('mailing_city') }}
                                    </span>
                                </div>
                            </li>
                            <li class='field size4 align-top pb-2 pb-lg-6 mb-3' v-if="mailingAddressEnabled">
                                <div class='input_container_select'>
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
                                    <span class="invalid-feedback" v-show="form.errors.has('mailing_state')">
                                        @{{ form.errors.get('mailing_state') }}
                                    </span>
                                </div>
                            </li>
                            <li class='field size4 align-top pb-lg-6 mb-3' v-if="mailingAddressEnabled">
                                <div class='input_container input_container_text'>
                                    <input type='text'
                                        v-model="form.mailing_zipcode"
                                        placeholder='{{ __("Zip")}}'
                                    >
                                    <span class="invalid-feedback" v-show="form.errors.has('mailing_zipcode')">
                                        @{{ form.errors.get('mailing_zipcode') }}
                                    </span>
                                </div>
                            </li>

                            <li class='field size2 align-top pb-lg-6 mb-3' v-if="commentEnabled">
                                <label class='field_label'>{{ __("Comment")}}
                                    <span v-if="commentRequired">{{ __("(Required)") }}</span>
                                    <span v-else>{{ __("(Optional)") }}</span>
                                </label>
                                <div class='input_container input_container_text'>
                                    <textarea class='form-control' placeholder='{{ __("Comment") }}'
                                        v-model="form.comments"
                                        :class="{'is-invalid': form.errors.has('comments')}"></textarea>

                                    <span class="invalid-feedback" v-show="form.errors.has('comments')">
                                        @{{ form.errors.get('comments') }}
                                    </span>
                                </div>
                            </li>

                            <li class='field size2 align-top' v-if="donorQuestions" v-for="(donorQuestion, index) in donorQuestions">
                                <label class='field_label'>@{{ donorQuestion.question }} <span v-if="! donorQuestion.is_required">{{ __("(Optional)") }}</span></label>
                                <div class='input_container input_container_text'>
                                    <input type='text'
                                        v-model="form.donor_answers[donorQuestion.id].answer"
                                        :class="{'is-invalid': form.errors.has('donor_answers.' + donorQuestion.id + '.answer')}">
                                </div>
                                <span class="invalid-feedback" v-show="form.errors.has('donor_answers.' + donorQuestion.id + '.answer')">
                                    @{{ form.errors.get('donor_answers.' + donorQuestion.id + '.answer') }}
                                </span>
                            </li>
                        </ul>
                        <div class='form_footer d-flex flex-column flex-md-row justify-content-between align-items-start px-0'>
                            @include('partials.common.button-with-loading', [
                                'title' => __('Record Donation'),
                                'buttonClass' => 'btn--size16',
                                'busyCondition' => 'form.busy',
                            ])
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</donation-create>
@endsection
