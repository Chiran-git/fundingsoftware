<div class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-cm-lg">
        <div class="modal-content grayscale rounded-0 py-5 px-3">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            <h2 class="text-center">{{ __("Change Mailing Address") }}</h2>
            <h3 class="text-center aleo">{{ __("This is the address where your checks will be mailed to.") }}</h3>
            <div class='form_wrapper'>
                <div class='form_body'>
                    <ul class='form_fields'>
                        <li class='field size1 align-top'><label class='field_label assistant field_label--asterisk'>{{ __('Name')}}</label>
                            <div class='input_container input_container_text'>
                                <input type='text'
                                    tabindex='2'
                                    placeholder='{{ __("Name")}}'
                                    v-model="form.payout_name"
                                    :class="{'is-invalid': form.errors.has('payout_name')}">
                            </div>
                            <span class="invalid-feedback" v-show="form.errors.has('payout_name')">
                                @{{ form.errors.get('payout_name') }}
                            </span>
                        </li>
                        <li class='field size1 align-top'><label class='field_label assistant field_label--asterisk'>{{ __('Organization Name')}}</label>
                            <div class='input_container input_container_text'>
                                <input type='text'
                                    tabindex='3'
                                    placeholder='{{ __("Organization Name")}}'
                                    v-model="form.payout_organization_name"
                                    :class="{'is-invalid': form.errors.has('payout_organization_name')}">
                            </div>
                            <span class="invalid-feedback" v-show="form.errors.has('payout_organization_name')">
                                @{{ form.errors.get('payout_organization_name') }}
                            </span>
                        </li>


                        <li class='field size2 align-top pb-2'>
                            <label class='field_label field_label--asterisk'>{{ __('Address')}}</label>
                            <div class='input_container input_container_text'>
                                <input type='text'
                                    placeholder='{{ __("Address line 1")}}'
                                    v-model="form.payout_address1"
                                    :class="{'is-invalid': form.errors.has('payout_address1')}">
                            </div>
                            <span class="invalid-feedback" v-show="form.errors.has('payout_address1')">
                                @{{ form.errors.get('payout_address1') }}
                            </span>
                        </li>
                        <li class='field size2 align-top pb-2'>
                            <div class='input_container input_container_text'>
                                <input type='text'
                                    placeholder='{{ __("Address line 2")}}'
                                    v-model="form.payout_address2"
                                    :class="{'is-invalid': form.errors.has('payout_address2')}">
                            </div>
                            <span class="invalid-feedback" v-show="form.errors.has('payout_address2')">
                                @{{ form.errors.get('payout_address2') }}
                            </span>
                        </li>
                        <li class='field size1 align-top pb-2 pb-lg-6'>
                            <div class='input_container input_container_text'>
                                <input type='text'
                                    placeholder='{{ __("City")}}'
                                    v-model="form.payout_city"
                                    :class="{'is-invalid': form.errors.has('payout_city')}">
                            </div>
                            <span class="invalid-feedback" v-show="form.errors.has('payout_city')">
                                @{{ form.errors.get('payout_city') }}
                            </span>
                        </li>
                        <li class='field size4 align-top pb-2 pb-lg-6'>
                            <div class='input_container_select input_container_select--sec'>
                                <select
                                    :class="{'is-invalid': form.errors.has('payout_state')}"
                                    v-model="form.payout_state">
                                    <option selected disabled value="">{{ __("State")}}</option>
                                    <option v-for="(label, value) in states" :value="value">@{{ label }}</option>
                                </select>
                            </div>
                            <span class="invalid-feedback" v-show="form.errors.has('payout_state')">
                                @{{ form.errors.get('payout_state') }}
                            </span>
                        </li>
                        <li class='field size4 align-top pb-lg-6'>
                            <div class='input_container input_container_text'>
                                <input type='text'
                                    placeholder='{{ __("Zip")}}'
                                    v-model="form.payout_zipcode"
                                    :class="{'is-invalid': form.errors.has('payout_zipcode')}">
                            </div>
                            <span class="invalid-feedback" v-show="form.errors.has('payout_zipcode')">
                                @{{ form.errors.get('payout_zipcode') }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="d-flex justify-content-center" {{-- data-dismiss="modal" --}} aria-label="Close">
                <button class="btn btn--dark btn--size4 rounded-pill" @click.prevent="submit">
                    {{ __('OK') }}
                </button>
            </div>
        </div>
    </div>
</div>
