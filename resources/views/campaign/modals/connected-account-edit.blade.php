<div class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-cm-lg">
        <div class="modal-content grayscale rounded-0 px-3 py-5 p-md-5">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" v-if="! showNewAccountModal">
                <span aria-hidden="true">×</span>
            </button>
            <h1 class="text-center aleo mb-1" v-if="account_exist">{{ __("Please note") }}</h1>
            <h1 class="text-center aleo mb-1" v-else-if="showNewAccountModal">{{ __("Success!") }}</h1>
            <h1 class="text-center aleo mb-1" v-else>{{ __("Account Update") }}</h1>
            
            <h3 class="text-center aleo" v-if="account_exist">{{ __("This account has already been linked to your RocketJar Organization.") }}</h3>
            <h3 class="text-center aleo" v-else-if="showNewAccountModal">{{ __("Your banking account has been linked to RocketJar.") }}</h3>
            <h3 class="text-center aleo" v-else>{{ __("Update your banking account information.") }}</h3>
            <form method='post' v-show="account_exist === false" @submit.prevent="submitAccount">
                <div class="rounded p-3 p-md-4 bg-grey mx-md-4 mt-md-4">
                    <h6 class="aleo mb-0 px-x-2">{{ __('Stripe Account') }}</h6>
                    <p class="f-16 px-x-2" v-if="getAccountFromId(accountForm.id) && getAccountFromId(accountForm.id).external_account_name">
                        @{{ getAccountFromId(accountForm.id).external_account_name }} {{ __('Account') }} #xxxx@{{ getAccountFromId(accountForm.id).external_account_last4 }}
                    </p>
                    <div class='form_wrapper form_wrapper--def'>
                        <div class='form_body px-0'>
                            <ul class='form_fields pr-3'>
                                <li class='field size2 align-top'><label class='field_label'>{{ __('Account Nickname')}}</label>
                                    <div class='input_container input_container_text'>
                                        <input type='text'
                                            tabindex='1'
                                            placeholder='{{ __("Enter a custom nikname for this account")}}'
                                            v-model="accountForm.nickname"
                                            :class="{'is-invalid': accountForm.errors.has('nickname')}">
                                        <span class="invalid-feedback" v-show="accountForm.errors.has('nickname')">
                                            @{{ accountForm.errors.get('nickname') }}
                                        </span>
                                    </div>
                                </li>
                                <li class='field size2 align-top'>
                                    <div class="field_checkbox">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} v-model="accountForm.is_default">

                                            <label class="form-check-label text-dark text-tranform-none" for="remember">
                                                {{ __('Make this the default deposit account for my organization') }}
                                            </label>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <div class="w-md-50 mt-5">
                            @include('partials.common.button-with-loading', [
                                'title' => '<span v-if="showNewAccountModal" class="text-tranform-none">' . __('Use this Account') . '</span>' .
                                    '<span v-else>' . __('Save Changes') . '</span>',
                                'busyCondition' => 'accountForm.busy',
                                'buttonClass' => 'btn--size3 w-100',
                                'submitMethod' => 'submitAccount',
                            ])
                    </div>
                </div>
            </form>
            <div v-if="account_exist === true" class="d-flex justify-content-center">
                <div class="w-md-50 mt-5">
                        @include('partials.common.button-with-loading', [
                            'title' => '<span>' . __('OK') . '</span>',
                            'busyCondition' => 'accountForm.busy',
                            'buttonClass' => 'btn--size3 w-100',
                            'submitMethod' => 'closeModal',
                        ])
                </div>
            </div>
        </div>
    </div>
</div>
