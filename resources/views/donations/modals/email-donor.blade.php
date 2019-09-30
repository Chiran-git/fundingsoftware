<div class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" ref="emailDonorModal">
    <div class="modal-dialog modal-dialog-centered modal-cm-lg">
        <div class="modal-content grayscale rounded-0 py-5 px-3">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
            <h2 class="text-center">{{ __("Email Donor") }}</h2>
            <div class='form_wrapper'>
                        <form method='post' @submit.prevent="submit">
                            <div class='form_body'>
                                <ul class='form_fields' style="width:100%;">
                                    <li class='field size2 align-top'><label class='field_label assistant'>{{ __('Subject')}}</label>
                                        <div class='input_container input_container_text'>
                                            <input type='text'
                                                tabindex='1'
                                                placeholder='{{ __("Subject")}}'
                                                v-model="form.subject"
                                                :class="{'is-invalid': form.errors.has('subject')}">
                                        </div>
                                        <span class="invalid-feedback" v-show="form.errors.has('subject')">
                                            @{{ form.errors.get('subject') }}
                                        </span>
                                    </li>
                                    <li class='field size2 align-top'><label class='field_label assistant'>{{ __('Message')}}</label>
                                        <div class='input_container input_container_text'>
                                            {{-- <textarea class='form-control' placeholder='{{ __("Message") }}'
                                            v-model="form.message"
                                            :class="{'is-invalid': form.errors.has('message')}"></textarea> --}}
                                            <vue-simplemde v-model="form.message"
                                                ref="descriptionEditor"
                                                :configs="descriptionMdeConfig">
                                            </vue-simplemde>
                                        </div>
                                        <span class="invalid-feedback" v-show="form.errors.has('message')">
                                            @{{ form.errors.get('message') }}
                                        </span>
                                    </li>
                                </ul>
                            </div>
                            <div class='form_footer d-flex flex-column flex-md-row justify-content-between align-items-start'>
                            <button type='submit'
                                class='btn btn--dark btn--size6 rounded-pill'
                                @click.prevent="submit"
                                :disabled="form.busy">
                                <span v-if="! form.busy">{{ __('Send Email')}}</span>
                                    <span v-else>
                                        @include('partials.common.loading')
                                    </span>
                            </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
