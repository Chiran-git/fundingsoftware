<div  v-show="currentStep == 2">
<org-pagedesign :organization="organization ? organization : {}" inline-template>
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
            <h5 class="{{ (request()->is('organization/edit')) ? 'd-none' : '' }}">{{ __('Step 2 of 4')}}</h5>
            <h3>{{ __('Organization Page Design')}}</h3>
        </div>
        <form method='post' @submit.prevent="submit">
            <div class="section__content">
                <div class="form-container {{ (request()->is('organization/edit')) ? 'form-edit' : 'form-container--small'  }}">
                    <div class='form_wrapper'>
                        <ul class='form_fields'>
                            <li class='field size2 align-top'><label class='field_label mb-1'>{{ __('Cover image')}}</label>
                                <h6 class="small f-16">{{ __('This photo will appear at the top of your Organization’s Page. Try using a photo of your building, students, or staff.')}}</h6>
                                <div class='input_container input_container_text'>
                                    <div class="image-uploader btn-file btn-file--big-org btn-file--big-org--edt" :style="imageStyles.cover_image">
                                        <img src="{{ asset('images/icons/add-a-photo.png') }}" alt="" v-if="! organization.cover_image">
                                        <input type="file"
                                                name="cover_image"
                                                class="image-uploader-control"
                                                ref="cover_image"
                                                @change="changeImage('cover_image')">
                                        <template v-if="! organization.cover_image">
                                            <span class="mb-2">{{ __("Add a Photo")}}</span>
                                            <span class="small f-16">{{ __('Minimum size: ')}}@{{ originalImageFiles.cover_image.requiredWidth }} X @{{ originalImageFiles.cover_image.requiredHeight }}px</span>
                                        </template>
                                    </div>
                                </div>
                                <span class="invalid-feedback" v-show="form.errors.has('cover_image')">
                                    @{{ form.errors.get('cover_image') }}
                                </span>
                                <span class="d-block f-16" v-if="organization.cover_image">
                                    <a href="" class="text-link" @click.prevent="openFileDialog('cover_image')">{{ __("Change Image")}}</a>
                                </span>
                            </li>
                            <li class='full-fields align-top'><label class='field_label'>{{ __('Organization Logo')}}</label>
                                <div class='input_container input_container_text d-flex'>
                                    <div class="image-uploader btn-file btn-file--small float-left" :style="imageStyles.logo">
                                        <img src="{{ asset('images/icons/add-a-photo-small.png') }}" class="mb-2" alt="" v-if="! organization.logo">
                                        <input type="file"
                                                name="logo"
                                                class="image-uploader-control"
                                                ref="logo"
                                                @change="changeImage('logo')">
                                        <span class="mb-2" v-if="! organization.logo">{{ __("Add a Photo")}}</span>
                                    </div>
                                    <span class="small f-16 float-left pl-3">{{ __('Minimum size:')}}<br/>@{{ originalImageFiles.logo.requiredWidth }} X @{{ originalImageFiles.logo.requiredHeight }}px</span>
                                </div>
                                <span class="invalid-feedback" v-show="form.errors.has('logo')">
                                    @{{ form.errors.get('logo') }}
                                </span>
                                <span class="d-block f-16" v-if="organization.logo">
                                    <a href="" class="text-link" @click.prevent="openFileDialog('logo')">{{ __("Change Logo")}}</a>
                                </span>
                            </li>
                            <li class='field size2 align-top pb-0'>
                                <label class='field_label'>{{ __('Color Palette')}}</label>
                            </li>
                            <li class='field size3 align-top'>
                                <label class='f-14'>{{ __('Primary Color')}}</label>
                                <colorpicker :color.sync="form.primary_color" v-model="primary_color"  @change="setbackgroundColorStyle()"></colorpicker>
                                <span class="invalid-feedback" v-show="form.errors.has('primary_color')">
                                    @{{ form.errors.get('primary_color') }}
                                </span>
                            </li>
                            <li class='field size3 align-top'>
                                <label class='f-14'>{{ __('Secondary Color')}}</label>
                                <colorpicker :color.sync="form.secondary_color" v-model="secondary_color"  @change="setbackgroundColorStyle()"></colorpicker>
                                <span class="invalid-feedback" v-show="form.errors.has('secondary_color')">
                                    @{{ form.errors.get('secondary_color') }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="section__content {{ (request()->is('organization/edit')) ? '' : 'form-container--small'  }}">
                <div class="section__head">
                    <h3 class="mb-0">{{ __('Emotional Appeal')}}</h3>
                    <p class="f-16">{{ __('Add a personal message to your contributors to encourage their support.')}}</p>
                </div>
                <div class='form_wrapper px-x-2'>
                    <ul class='form_fields form_fields--def'>
                        <li class='field size2 align-top'><label class='field_label'>{{ __('Headline')}}</label>
                            <div class='input_container input_container_text'>
                                    <input type='text'
                                    placeholder='{{ __("Headline")}}'
                                    v-model="form.appeal_headline"
                                    :class="{'is-invalid': form.errors.has('appeal_headline')}">
                            </div>
                            <span class="invalid-feedback" v-show="form.errors.has('appeal_headline')">
                                @{{ form.errors.get('appeal_headline') }}
                            </span>
                        </li>
                        <li class='field size2 align-top'><label class='field_label'>{{ __('Message')}}</label>
                            <div class='input_container input_container_text'>
                                    <textarea name="message" id="message" class="field"
                                    placeholder="{{ __('Type message here')}}"
                                    v-model="form.appeal_message"
                                    :class="{'is-invalid': form.errors.has('appeal_message')}"></textarea>
                            </div>
                            <span class="invalid-feedback" v-show="form.errors.has('appeal_message')">
                                @{{ form.errors.get('appeal_message') }}
                            </span>
                        </li>
                        <li class='full-fields align-top'><label class='field_label'>{{ __('Photo')}}</label>
                            <div class='input_container input_container_text p-2 d-flex'>
                                <div class="image-uploader btn-file btn-file--small rounded-circle float-left" :style="imageStyles.appeal_photo">
                                    <img src="{{ asset('images/icons/add-a-photo-small.png') }}" alt="" v-if="! organization.appeal_photo">
                                    <input type="file"
                                            name="appeal_photo"
                                            class="image-uploader-control"
                                            ref="appeal_photo"
                                            @change="changeImage('appeal_photo')">
                                    <span class="mb-1 f-14"  v-if="! organization.appeal_photo">{{ __("Add a Photo")}}</span>
                                </div>
                                <span class="small f-16 float-left pl-3">{{ __('Minimum size:')}}<br/>@{{ originalImageFiles.appeal_photo.requiredWidth }} X @{{ originalImageFiles.appeal_photo.requiredHeight }}px</span>
                            </div>
                            <span class="invalid-feedback pl-3" v-show="form.errors.has('appeal_photo')">
                                @{{ form.errors.get('appeal_photo') }}
                            </span>
                            <span class="d-block f-16 pl-3" v-if="organization.appeal_photo">
                                <a href="" class="text-link" @click.prevent="openFileDialog('appeal_photo')">{{ __("Change Photo")}}</a>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class='form_footer d-flex flex-column flex-md-row justify-content-start align-items-start mt-5'>
                @include('partials.common.button-with-loading', [
                    'title' => $buttonTitle,
                    'buttonClass' => $buttonclass,
                    'busyCondition' => 'form.busy',
                    'submitMethod' => __('submit')
                ])
                @if (isset($action) && $action == 'edit')
                <button type='submit'
                    class='btn btn--outline rounded-pill mt-4 mt-md-0 ml-md-4'
                    @click.prevent="showPreview()">{{ __('Preview Changes')}}</button>
                @else
                <button type='submit'
                    class='btn btn--outline btn--size7 btn--py rounded-pill mt-4 mt-md-0 ml-md-4'
                    @click.prevent="showPreview()">{{ __('Preview Organization Page')}}</button>
                @endif

            </div>
        </form>
        @include('organization.preview.modal-preview', [
            'modalId' => 'modal-template-preview',
        ])
        @include('partials.modals.modal-crop-image', [
            'modalId' => 'cover_image-crop',
            'imageName' => 'cover_image',
            'modalTitle' => __('Edit Image'),
            'modalSubTitle' => __('Please use the editor below to select the image area you want.')
        ])
        @include('partials.modals.modal-crop-image', [
            'modalId' => 'logo-crop',
            'imageName' => 'logo',
            'modalTitle' => __('Edit Image'),
            'modalSubTitle' => __('Please use the editor below to select the image area you want.')
        ])
        @include('partials.modals.modal-crop-image', [
            'modalId' => 'appeal_photo-crop',
            'imageName' => 'appeal_photo',
            'modalTitle' => __('Edit Image'),
            'modalSubTitle' => __('Please use the editor below to select the image area you want.')
        ])
    </div>
</org-pagedesign>
</div>
