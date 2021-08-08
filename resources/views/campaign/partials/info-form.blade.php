<div v-show="currentStep == 1 || campaignId">
<campaign-info  inline-template :organization="organization" :campaign="campaign" v-if="showChildComponents">
    <div class="section__inner">
        <div class="section__head">
            @if (isset($action) && $action == 'edit')
                @php $buttonTitle = __('Save Changes'); @endphp
                <h3 class="pt-2 border-top-2">
                    <span class="edit-serial-no">{{ __('1')}}</span>
                    {{ __('Campaign Information')}}
                </h3>
                <p class="f-18">{{ __('Tell people what you’re raising money for and why they should support your cause. This information will be visible to the public.') }}</p>
            @else
                @php $buttonTitle = __('Next') . ' <i class="fas fa-chevron-right f-8"></i>'; @endphp
                <h5>{{ __('Step 1 of 6')}}</h5>
                <h3 class="mb-2">{{ __('Campaign Information')}}</h3>
                <p class="f-18">{{ __('Explain what you’re raising money for and why people should support your cause.') }}</p>
            @endif

        </div>
        <div class="section__content mt-lg-5" v-if="campaign">
            <div class="form-container form-container--small">
                <div class='form_wrapper'>
                    <form method='post' @submit.prevent="submit">
                        <ul class='form_fields w-100'>
                            <li class='field size2 align-top'><label class='field_label'>{{ __('Campaign Name')}}</label>
                                <div class='input_container input_container_text'>
                                    <input type='text'
                                        placeholder='{{ __("Campaign Name")}}'
                                        v-model="form.name"
                                        :class="{'is-invalid': form.errors.has('name')}">
                                </div>
                                <span class="invalid-feedback" v-show="form.errors.has('name')">
                                    @{{ form.errors.get('name') }}
                                </span>
                            </li>
                            <li class='field size2 align-top'><label class='field_label'>{{ __('Campaign Category')}}</label>
                                <div class='input_container_select'>
                                    <v-select :options="categoryOptions"
                                            placeholder="{{ __('Select Category') }}"
                                            v-model="form.campaign_category_id">
                                        <template slot="option" slot-scope="option">
                                            @{{ getCategoryOptionLabel(option.label) }}
                                        </template>
                                        <template slot="selected-option" slot-scope="option" v-bind="(typeof option === 'object')?option:{[label]: option}">
                                            @{{ getCategoryOptionLabel(option.label) }}
                                        </template>
                                    </v-select>
                                </div>
                                <span class="invalid-feedback" v-show="form.errors.has('campaign_category_id')">
                                    @{{ form.errors.get('campaign_category_id') }}
                                </span>
                            </li>
                            <li class='field size1 align-top pb-2 pb-lg-6 pr-lg-5'>
                                <label class='field_label'>{{ __('Fundraising Goal')}}</label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text px-3">@{{ this.organization.currency.symbol }}</div>
                                    </div>
                                    <input type="text" class="form-control"
                                        placeholder="{{ __("1000.00")}}"
                                        v-model="form.fundraising_goal"
                                        :class="{'is-invalid': form.errors.has('fundraising_goal')}">
                                </div>
                                <span class="invalid-feedback" v-show="form.errors.has('fundraising_goal')">
                                    @{{ form.errors.get('fundraising_goal') }}
                                </span>
                            </li>
                            <li class='field size1 align-top pb-lg-6'>
                                <label class='field_label'>{{ __('Campaign End Date')}} <span>{{ __('Optional') }}</span></label>
                                <div class='input_container input_container_text position-relative'>
                                    {{-- <datepicker v-model="form.end_date"></datepicker> --}}
                                    <flat-pickr
                                        v-model="form.end_date"
                                        :config="flatPickrConfig"
                                        class="form-control"
                                        placeholder="{{ __("MM/DD/YYYY HH:MM AM") }}"
                                        name="end_date">
                                    </flat-pickr>
                                    <span class="text-muted">(In @{{ $root.getBrowserTzName() }} timezone)</span>
                                </div>
                            </li>
                            <li class='field size2 align-top my-4'><label class='field_label mb-0'>{{ __('Campaign Image')}} <span>{{ __('Optional')}}</span></label>
                                <div class='input_container input_container_text'>
                                    <div class="image-uploader btn-file btn-file--big" :style="imageStyles.image">
                                        <img src="{{ asset('images/icons/add-a-photo.png') }}" class="mb-3" alt=""  v-if="! campaign.image">
                                        <input type="file"
                                            name="image"
                                            class="image-uploader-control"
                                            ref="image"
                                            @change="changeImage('image')">
                                        <template v-if="! campaign.image">
                                            <span class="mb-2">{{ __("Add a Photo")}}</span>
                                            <span class="small f-16">{{ __('Minimum size: ')}}@{{ originalImageFiles.image.requiredWidth }} X @{{ originalImageFiles.image.requiredHeight }}px</span>
                                        </template>
                                    </div>
                                </div>
                                <span class="invalid-feedback" v-show="form.errors.has('image')">
                                    @{{ form.errors.get('image') }}
                                </span>
                                <span class="d-block f-16" v-if="campaign.image">
                                    <a href="" class="text-link" @click.prevent="openFileDialog('image')">{{ __("Change")}}</a>
                                    | <a href="" class="text-link text-link--red" @click.prevent="confirmDeleteUploadedFile('image')">{{ __("Delete")}}</a>
                                </span>
                                <h6 class="small f-14 mt-2">{{ __('This image will appear at the top of your campaign page, and on social media when your campaign is shared. If no image is added, a placeholder image will be displayed.')}}</h6>
                            </li>
                            <li class='field size2 align-top'><label class='field_label'>{{ __('Campaign Video URL')}} <span>{{ __('Optional')}}</span></label>
                                <div class='input_container input_container_text size2'>
                                    <input type='text'
                                        placeholder='{{ __("http://youtube.com/sample-video")}}'
                                        v-model="form.video_url"
                                        :class="{'is-invalid': form.errors.has('video_url')}">
                                </div>
                                <h6 class="small f-14 mt-2">{{ __('Add a video to your campaign page. Enter the YouTube or Vimeo sharing URL for your video above. Your video will be displayed at the top of your campaign page.')}}</h6>
                                <span class="invalid-feedback" v-show="form.errors.has('video_url')">
                                    @{{ form.errors.get('video_url') }}
                                </span>
                            </li>
                            <li class='field size2 align-top my-4'><label class='field_label'>{{ __('Campaign Description')}}</label>
                                <div class='input_container_textarea'>
                                    <vue-simplemde v-model="form.description"
                                        ref="descriptionEditor"
                                        :configs="descriptionMdeConfig">
                                    </vue-simplemde>
                                </div>
                                <h6 class="small f-14 mt-2">{!! __('Please provide content in <a class="text-link text-link--red" href="https://www.markdownguide.org/basic-syntax/" target="_blank">Markdown</a> syntax. Click the <a class="fa fa-eye no-disable"></a> icon in the toolbar to preview.') !!}</h6>
                                <span class="invalid-feedback" v-show="form.errors.has('description')">
                                    @{{ form.errors.get('description') }}
                                </span>
                            </li>
                        </ul>
                        <div class='form_footer d-flex flex-column flex-md-row align-items-start px-x-2'>
                            @include('partials.common.button-with-loading', [
                                'title' => $buttonTitle,
                                'busyCondition' => 'form.busy',
                                'buttonClass' => 'btn--size6 btn--size15'
                            ])
                                <button type='submit' class='btn btn--outline btn--size6 btn--size15 rounded-pill mt-4 mt-md-0 ml-md-4'
                                @click.prevent="$parent.showPreview('campaign-info-preview')">{{ __('Preview Campaign')}}</button>
                        </div>
                    </form>
                </div>
            </div>
            @include('campaign.preview.modal-info-preview', [
                'modalId' => 'campaign-info-preview',
            ])
            @include('partials.modals.modal-crop-image', [
                'modalId' => 'image-crop',
                'imageName' => 'image',
                'modalTitle' => __('Edit Image'),
                'modalSubTitle' => __('Please use the editor below to select the image area you want.')
            ])
        </div>
    </div>
</campaign-info>
</div>
