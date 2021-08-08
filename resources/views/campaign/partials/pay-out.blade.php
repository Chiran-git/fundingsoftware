<div v-show="currentStep == 5 || campaignId">
    <campaign-pay-out inline-template :organization="organization" :campaign="campaign" :accounts="accounts" v-if="showChildComponents">
        <div class="section__inner">
            <div class="section__head">
                @if (isset($action) && $action == 'edit')
                    @php
                        $buttonTitle = __('Save Changes');
                        $submitMethod = 'update';
                    @endphp
                    <h3 class="pt-2 border-top-2">
                        <span class="edit-serial-no">{{ __('5')}}</span>
                        {{ __('Pay-Outs')}}
                    </h3>
                @else
                    @php
                        $buttonTitle = __('Next') . ' <i class="fas fa-chevron-right f-8"></i>';
                        $submitMethod = 'submit';
                    @endphp
                    <h5>{{ __('Step 5 of 6')}}</h5>
                    <h3 class="mb-2">{{ __('Pay-Outs')}}</h3>
                @endif
                <p class="f-18">{{ __('When and where should we send your donations?') }}</p>
            </div>
            <div class="section__content px-x-2 mt-lg-5">
                <div class="form-container form-container--small">
                    <h6 class="aleo">{{ __('Payment Method') }}</h6>
                    <div class='form_wrapper'>
                        <form method='post' @submit.prevent="submit">
                            <ul class='form_fields align-top'>
                                <li class='field pl-0 size2 pb-2 field_radio field_radio--tab'>
                                    <input name='payout_method' value="check" id="f-option1" type='radio' v-model="form.payout_method" @change="updateSchedule"/>
                                    <label for="f-option1">{{ __("Mail a check") }}</label>
                                    <div class="check"></div>
                                    <div class="tab__content">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-12 col-md-6">
                                                    <div class="body__content vertical-line py-md-0">
                                                        <p class="font-italic mb-2">{{ __('Your check will be mailed to:') }}</p>
                                                        <p>
                                                            @{{ payoutInfo.payout_name }} <br/>
                                                            @{{ payoutInfo.payout_organization_name }} <br/>
                                                            @{{ payoutInfo.payout_address1 }}<br/>
                                                            <span v-if="payoutInfo.payout_address2">@{{ payoutInfo.payout_address2 }}<br/></span>
                                                            @{{ payoutInfo.payout_city + ', ' + payoutInfo.payout_state + ' ' + payoutInfo.payout_zipcode }}
                                                            <a href="#" class="text-link text-link--red d-block font-weight-bold"
                                                                @click.prevent="showMailAddressPreview('add-check-mail-address')">{{  __('Use a Different Address') }}</a>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="body__content py-md-0">
                                                        <p class="font-italic mb-2">{{ __('Your check will be payable to:') }}</p>
                                                        <p>@{{ payoutInfo.payout_payable_to }}
                                                            <a href="#" class="text-link text-link--red d-block font-weight-bold"
                                                            @click.prevent="showChequePayablePreview('add-check-mail-payable-to')">{{ __('Use a Different Name') }}</a>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class='field pl-0 size2 pb-2 field_radio field_radio--tab'>
                                        @php
                                            $returnUrl = urlencode(route('campaign.create', ['step' => '5', 'id' => '']));
                                            if (isset($action) && isset($campaignId) && ($action == 'edit' && $campaignId )) {
                                                $returnUrl = urlencode(route('campaign.edit', ['id' => $campaignId]));
                                            }
                                            $createAccountUrl = route('connected-account.create');
                                        @endphp
                                        <input name='payout_method' value="bank" id="f-option2" type='radio' v-model="form.payout_method" @change="updateSchedule"/>
                                        <label for="f-option2">{{ __("Direct Deposit to Bank Account") }}</label>
                                        <div class="check"></div>
                                        <div class="tab__content" v-if="accounts.length <= 0">
                                            <h5>{{ __('No Bank Account added yet') }}</h5>
                                            @if (in_array(auth()->user()->currentRole(), ['owner', 'admin']))
                                                @if (isset($action) && isset($campaignId) && ($action == 'edit' && $campaignId ))
                                                    <a class="btn btn--transparent btn--size4 rounded-pill text-tranform-none btn--lightborder px-4"
                                                        :href="'{{ $createAccountUrl }}?return={{ $returnUrl }}'">
                                                        {{ __('Add Bank Account') }}
                                                    </a>
                                                @else
                                                    <a class="btn btn--transparent btn--size4 rounded-pill text-tranform-none btn--lightborder px-4"
                                                        :href="'{{ $createAccountUrl }}?return={{ $returnUrl }}' + campaign.id">
                                                        {{ __('Add Bank Account') }}
                                                    </a>
                                                @endif
                                            @endif
                                            <span class="invalid-feedback" v-show="form.errors.has('payout_connected_account_id')">
                                                    @{{ form.errors.get('payout_connected_account_id') }}
                                                </span>
                                        </div>
                                        <div class="tab__content field w-75" v-if="accounts.length > 0">
                                            <div class='input_container_select'>

                                                <v-select :options="accountOptions"
                                                    placeholder="{{ __('Select Account') }}"
                                                    v-model="form.payout_connected_account_id">
                                                    <template slot="option" slot-scope="option">
                                                        @{{ getPayoutAccountOptionLabel(option.label) }}
                                                    </template>
                                                    <template slot="selected-option" slot-scope="option">
                                                        @{{ getPayoutAccountOptionLabel(option.label) }}
                                                    </template>
                                                </v-select>
                                                <span class="invalid-feedback" v-show="form.errors.has('payout_connected_account_id')">
                                                    @{{ form.errors.get('payout_connected_account_id') }}
                                                </span>
                                            </div>
                                            @if (in_array(auth()->user()->currentRole(), ['owner', 'admin']))
                                                <div class="d-flex justify-content-between mt-3">
                                                    @if (isset($action) && isset($campaignId) && ($action == 'edit' && $campaignId ))
                                                        <a :href="'{{ $createAccountUrl }}?return={{ $returnUrl }}'"
                                                            class="text-link text-link--red d-block font-weight-bold">
                                                            <i class="fas fa-plus f-8 mr-1"></i>{{ __("New Account") }}
                                                        </a>
                                                    @else
                                                        <a :href="'{{ $createAccountUrl }}?return={{ $returnUrl }}' + campaign.id"
                                                            class="text-link text-link--red d-block font-weight-bold">
                                                            <i class="fas fa-plus f-8 mr-1"></i>{{ __("New Account") }}
                                                        </a>
                                                    @endif
                                                    <a href="" class="text-link text-link--red d-block font-weight-bold"
                                                        @click.prevent="showPayoutAccountsList">
                                                        <i class="fas fa-pencil-alt f-8 mr-1"></i>{{ __('Manage Accounts') }}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </li>
                                    <li class='field pl-0 size2 pb-2 field_radio field_radio--tab'>
                                        <input name='payout_method' id="f-option3" type='radio' v-model="form.payout_method" @change="updateSchedule"/>
                                        <label for="f-option3">{{ __("I'll set this up later") }}</label>
                                        <div class="check"></div>

                                        <span class="invalid-feedback" v-show="form.errors.has('payout_method')">
                                            @{{ form.errors.get('payout_method') }}
                                        </span>
                                    </li>

                            </ul>

                        </form>
                    </div>
                    <div class="mt-5">
                        <h6 class="aleo">Payment Schedule</h6>
                        <p class="f-18">Your funds will be held until your scheduled pay-out date.</p>
                        <div class='form_wrapper'>
                            <form method='post' @submit.prevent="submit">
                                <ul class='form_fields align-top'>
                                    <li class='field pl-0 size2 pb-2 field_radio field_radio--tab' :class="form.payout_method == 'bank' ? 'field_radio--disabled' : ''">
                                        <input name="payout_schedule" value="monthly" id="f-option4" type='radio' v-model="form.payout_schedule" :disabled="form.payout_method == 'bank'"/>
                                        <label for="f-option4">{{ __("1st of Each Month") }}</label>
                                        <div class="check"></div>
                                    </li>
                                    <li class='field pl-0 size2 pb-2 field_radio field_radio--tab' :class="form.payout_method != 'bank' ? 'field_radio--disabled' : ''">
                                        <input name="payout_schedule" value="daily" id="f-option5" type='radio' v-model="form.payout_schedule" :disabled="form.payout_method != 'bank'"/>
                                        <label for="f-option5">{{ __("Every 24 Hours") }}
                                        <i class="d-block f-14">{{ __('You must choose Direct Deposit as your Payment Method to use this schedule.') }}</i>
                                        </label>
                                        <div class="check"></div>
                                    </li>
                                    <li class='field pl-0 size2 pb-2 field_radio field_radio--tab' :class="(! campaign.end_date || form.payout_method == 'bank') ? 'field_radio--disabled' : '' ">
                                        <input name="payout_schedule" value="completion" id="f-option6" type='radio' :disabled="! campaign.end_date || form.payout_method == 'bank'"
                                            v-model="form.payout_schedule"/>
                                        <label for="f-option6">{{ __("When the Campaign is Complete") }}
                                        <i class="d-block f-14">{{ __('You must enter a Campaign End Date to select this option.') }}
                                            </i>
                                        </label>
                                        <div class="check"></div>
                                        <span class="invalid-feedback" v-show="form.errors.has('payout_schedule')">
                                            @{{ form.errors.get('payout_schedule') }}
                                        </span>
                                    </li>
                                </ul>

                                <div class='form_footer d-flex flex-column flex-md-row justify-content-between align-items-start px-x-2'>
                                    @include('partials.common.button-with-loading', [
                                        'title' => $buttonTitle,
                                        'busyCondition' => 'form.busy',
                                        'buttonClass' => 'btn--size6 btn--size15',
                                        'submit' => $submitMethod
                                    ])
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @include('campaign.modals.add-check-mail-address', [
                    'modalId' => 'add-check-mail-address',
                ])
                @include('campaign.modals.add-check-mail-payable-to', [
                    'modalId' => 'add-check-mail-payable-to',
                ])
                @include('campaign.modals.connected-account-edit', [
                    'modalId' => 'connected-account-edit',
                ])
                @include('campaign.modals.connected-account-list', [
                    'modalId' => 'connected-account-list',
                ])
            </div>
        </div>
    </campaign-pay-out>
</div>
