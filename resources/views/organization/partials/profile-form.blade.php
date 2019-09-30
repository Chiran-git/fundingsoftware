<div v-show="currentStep == 1">
<org-profile :organization="organization ? organization : {}" inline-template>
<div class="section__inner {{ (request()->is('organization/edit')) ? 'pt-0' : '' }}">
    <div class="section__head">
        @if (isset($action) && $action == 'edit')
            @php
                $buttonTitle = __('Save Changes');
                $buttonclass = 'btn--size6';
            @endphp
        @else
            @php
                $buttonTitle = __('Next') . ' <i class="fas fa-chevron-right f-8"></i>';
                $buttonclass = 'btn--size16';
            @endphp
        @endif
        <h5 class="{{ (request()->is('organization/edit')) ? 'd-none' : '' }}">{{ __('Step 1 of 4')}}</h5>
        <h3>{{ __('Organization Profile')}}</h3>
    </div>
    <div class="section__content">
        <div class="form-container {{ (request()->is('organization/edit')) ? '' : 'form-container--small'  }}">
            <div class='form_wrapper'>
                <form method='post' @submit.prevent="submit">
                    <ul class='form_fields'>
                        @if ( auth()->user()->isSuperadmin() )
                        <li class='field size1 align-top'>
                            <label class='field_label field_label--asterisk'>{{ __('Account Owner First Name')}}</label>
                            <div class='input_container input_container_text'>
                                <input type='text'
                                    placeholder='{{ __("Account Owner First Name")}}'
                                    v-model="form.first_name"
                                    :class="{'is-invalid': form.errors.has('first_name')}">
                            </div>
                            <span class="invalid-feedback" v-show="form.errors.has('first_name')">
                                @{{ form.errors.get('first_name') }}
                            </span>
                        </li>
                        <li class='field size1 align-top'>
                            <label class='field_label field_label--asterisk'>{{ __('Account Owner Last Name')}}</label>
                            <div class='input_container input_container_text'>
                                <input type='text'
                                    placeholder='{{ __("Account Owner Last Name")}}'
                                    v-model="form.last_name"
                                    :class="{'is-invalid': form.errors.has('last_name')}">
                            </div>
                            <span class="invalid-feedback" v-show="form.errors.has('last_name')">
                                @{{ form.errors.get('last_name') }}
                            </span>
                        </li>
                        <li class='field size2 align-top'>
                            <label class='field_label field_label--asterisk'>{{ __('Account Owner Email Address')}}</label>
                            <div class='input_container input_container_text'>
                                <input type='text'
                                    placeholder='{{ __("donald@test.org")}}'
                                    v-model="form.email"
                                    :class="{'is-invalid': form.errors.has('email')}">
                            </div>
                            <span class="font-italic">{{ __('A link to create a password will be emailed to the Account Owner.') }}</span>
                            <span class="invalid-feedback" v-show="form.errors.has('email')">
                                @{{ form.errors.get('email') }}
                            </span>
                        </li>
                        @endif

                        <li class='field size2 align-top'><label class='field_label field_label--asterisk'>{{ __('Organization Name')}}</label>
                            <div class='input_container input_container_text'>
                                <input type='text'
                                    placeholder='{{ __("Organization Name")}}'
                                    v-model="form.name"
                                    :class="{'is-invalid': form.errors.has('name')}">
                            </div>
                            <span class="invalid-feedback" v-show="form.errors.has('name')">
                                @{{ form.errors.get('name') }}
                            </span>
                        </li>
                        <li class='field size2 align-top pb-2'>
                            <label class='field_label field_label--asterisk'>{{ __('Organization Address')}}</label>
                            <div class='input_container input_container_text'>
                                <input type='text'
                                    placeholder='{{ __("Organization Address line 1")}}'
                                    v-model="form.address1"
                                    :class="{'is-invalid': form.errors.has('address1')}">
                            </div>
                            <span class="invalid-feedback" v-show="form.errors.has('address1')">
                                @{{ form.errors.get('address1') }}
                            </span>
                        </li>
                        <li class='field size2 align-top pb-2'>
                            <div class='input_container input_container_text'>
                                <input type='text'
                                    placeholder='{{ __("Organization Address line 2")}}'
                                    v-model="form.address2"
                                    :class="{'is-invalid': form.errors.has('address2')}">
                            </div>
                            <span class="invalid-feedback" v-show="form.errors.has('address2')">
                                @{{ form.errors.get('address2') }}
                            </span>
                        </li>
                        <li class='field size1 align-top pb-2 pb-lg-6'>
                            <div class='input_container input_container_text'>
                                <input type='text'
                                    placeholder='{{ __("City")}}'
                                    v-model="form.city"
                                    :class="{'is-invalid': form.errors.has('city')}">
                            </div>
                            <span class="invalid-feedback" v-show="form.errors.has('city')">
                                @{{ form.errors.get('city') }}
                            </span>
                        </li>
                        <li class='field size4 align-top pb-2 pb-lg-6'>
                            <div class='input_container_select'>
                                <v-select :options="stateOptions"
                                    placeholder="{{ __('State') }}"
                                    v-model="form.state" :class="{'is-invalid': form.errors.has('state')}">
                                    <template slot="option" slot-scope="option">
                                        @{{ getStateOptionLabel(option.label) }}
                                    </template>
                                    <template slot="selected-option" slot-scope="option">
                                        @{{ getStateOptionLabel(option.label) }}
                                    </template>
                                </v-select>
                                {{-- <select
                                    v-model="form.state"
                                    :class="{'is-invalid': form.errors.has('state')}">
                                    <option selected disabled value="">{{ __("State")}}</option>
                                    <option v-for="(label, value) in states" :value="value">@{{ label }}</option>
                                </select> --}}
                            </div>
                            <span class="invalid-feedback" v-show="form.errors.has('state')">
                                @{{ form.errors.get('state') }}
                            </span>
                        </li>
                        <li class='field size4 align-top pb-lg-6'>
                            <div class='input_container input_container_text'>
                                <input type='text'
                                    placeholder='{{ __("Zip")}}'
                                    v-model="form.zipcode"
                                    :class="{'is-invalid': form.errors.has('zipcode')}">
                            </div>
                            <span class="invalid-feedback" v-show="form.errors.has('zipcode')">
                                @{{ form.errors.get('zipcode') }}
                            </span>
                        </li>
                        <li class='full-fields align-top'><label class='field_label field_label--asterisk'>{{ __('Organization Phone')}}</label>
                            <div class='input_container input_container_text size2'>
                                <input type='text'
                                    placeholder='{{ __("(XXX) XXX-XXXX")}}'
                                    v-model="form.phone"
                                    :class="{'is-invalid': form.errors.has('phone')}">
                            </div>
                            <span class="invalid-feedback" v-show="form.errors.has('phone')">
                                @{{ form.errors.get('phone') }}
                            </span>
                        </li>
                        <li class='field size3 align-top'><label class='field_label field_label--asterisk'>{{ __('Currency')}}</label>
                            <div class='input_container_select'>
                                <v-select :options="currencyOptions"
                                    placeholder="{{ __('Select Currency') }}"
                                    v-model="form.currency" :class="{'is-invalid': form.errors.has('currency')}">
                                </v-select>
                                {{-- <select v-model="form.currency"
                                :class="{'is-invalid': form.errors.has('currency')}">
                                    <option v-for="(currency) in currencies" :value="currency.iso_code">@{{ currency.iso_code }}</option>
                                </select> --}}
                            </div>
                            <span class="invalid-feedback" v-show="form.errors.has('currency')">
                                @{{ form.errors.get('currency') }}
                            </span>
                        </li>
                        <li class='field size2 align-top'><label class='field_label field_label--asterisk'>{{ __('Organization Page Web Address')}}</label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">{{ url('/') . '/' }}</div>
                                </div>
                                <input type="text" class="form-control"
                                    placeholder="{{ __("Organization Page Web Address")}}"
                                    v-model="form.slug"
                                    :class="{'is-invalid': form.errors.has('slug')}">
                            </div>
                            <span class="invalid-feedback" v-show="form.errors.has('slug')">
                                @{{ form.errors.get('slug') }}
                            </span>
                        </li>
                    </ul>
                    <div class='form_footer d-flex flex-column flex-md-row align-items-start px-0 {{(request()->is('organization/edit')) ? 'justify-content-start' : 'justify-content-between'  }}'>
                        @include('partials.common.button-with-loading', [
                            'title' => $buttonTitle,
                            'buttonClass' => $buttonclass,
                            'busyCondition' => 'form.busy',
                            'submitMethod' => __('submit')
                        ])
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</org-profile>
</div>
