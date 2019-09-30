<div class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-cm-lg">
            <div class="modal-content grayscale rounded-0 py-5 px-3">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                <h2 class="text-center">{{ __("Check Payable To") }}</h2>
                <h3 class="text-center aleo">{{ __("Your check will be payable to this name.") }}</h3>
                <div class='form_wrapper'>
                    <div class='form_body'>
                        <ul class='form_fields'>
                            <li class='field size2 align-top'><label class='field_label assistant field_label--asterisk'>{{ __('Payable To Name')}}</label>
                                <div class='input_container input_container_text'>
                                    <input type='text'
                                        tabindex='1'
                                        placeholder='{{ __("Payable To Name")}}'
                                        v-model="form.payout_payable_to"
                                        :class="{'is-invalid': form.errors.has('payout_payable_to')}">
                                </div>
                                <span class="invalid-feedback" v-show="form.errors.has('payout_payable_to')">
                                    @{{ form.errors.get('payout_payable_to') }}
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
