<div v-show="currentStep == 3 || campaignId">
    <campaign-donor-message inline-template :organization="organization" :campaign="campaign" v-if="showChildComponents">
        <div class="section__inner">
            <div class="section__head">
                @if (isset($action) && $action == 'edit')
                    @php $buttonTitle = __('Save Changes'); @endphp
                    <h3 class="pt-2 border-top-2">
                        <span class="edit-serial-no">{{ __('3')}}</span>
                        {{ __('Donor Message')}}
                    </h3>
                @else
                    @php $buttonTitle = __('Next') . ' <i class="fas fa-chevron-right f-8"></i>'; @endphp
                    <h5>{{ __('Step 3 of 6')}}</h5>
                    <h3 class="mb-2">{{ __('Donor Message')}}</h3>
                @endif
                <p class="f-18">{{ __("Enter a custom thank you message. It will appear in email receipts sent to this campaign’s donors.") }}</p>
            </div>
            <div class="section__content mt-lg-5">
                <div class="form-container form-container--small">
                    <div class='form_wrapper'>
                        <form method='post' @submit.prevent="submit">
                            <ul class='form_fields w-100 mb-0'>
                                <li class='field size2 align-top pb-0'>
                                    <div class='input_container input_container_text'>
                                        {{-- <textarea class='textarea' placeholder='{{ __("Thank you for your donation!") }}'
                                            v-model="form.donor_message"
                                            :class="{'is-invalid': form.errors.has('donor_message')}"></textarea> --}}
                                            <vue-simplemde v-model="form.donor_message"
                                            ref="descriptionEditor"
                                            :configs="descriptionMdeConfig">
                                            </vue-simplemde>
                                    </div>
                                    <h6 class="small f-14 mt-2">{!! __('Please provide content in <a class="text-link text-link--red" href="https://www.markdownguide.org/basic-syntax/" target="_blank">Markdown</a> syntax. Click the <a class="fa fa-eye no-disable"></a> icon in the toolbar to preview.') !!}</h6>
                                    <span class="invalid-feedback" v-show="form.errors.has('donor_message')">
                                        @{{ form.errors.get('donor_message') }}
                                    </span>
                                </li>
                            </ul>

                            <div class="d-flex">
                                <div class="pb-5 mb-4 px-x-2"><a href="#" class="text-link text-link--red aleo font-weight-bold" @click.prevent="$parent.showPreview('donor-email-preview')">Preview Donor Email</a></div>
                            </div>

                            <div class='form_footer d-flex flex-column flex-md-row justify-content-between align-items-start px-x-2'>
                                @include('partials.common.button-with-loading', [
                                    'title' => $buttonTitle,
                                    'busyCondition' => 'form.busy',
                                    'buttonClass' => 'btn--size6 btn--size15'
                                ])
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @include('campaign.preview.modal-donor-email', [
                'modalId' => 'donor-email-preview',
            ])
        </div>
    </campaign-donor-message>
</div>
