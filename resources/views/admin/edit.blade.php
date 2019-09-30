@extends('layouts.app')

@section('title', __('Edit Admin User'))

@section('content')
<admin-edit :user="{{ isset($user) ? $user : '{}' }}" inline-template>
    <section class="section--def">
        <div class="row mb-1">
            <div class="col-12 col-md-6">
                <div class="px-x-2 mb-5 mt-3">
                    <h2 v-if="form.id">{{ __('Edit Admin User')}}</h2>
                    <h2 v-else="form.id">{{ __('Create Admin User')}}</h2>
                </div>
            </div>
        </div>
        <div class="section__content">
            <form method='post' @submit.prevent="">
                <div class="row">
                    <div class="col-md-6">
                        <div class='form_wrapper'>
                            <ul class='form_fields'>
                                <li class='field size1 align-top'>
                                    <label class='field_label assistant f-16 mb-1 mb-1'>{{ __('first name')}}</label>
                                    <div class='input_container input_container_text'>
                                        <input type='text'
                                            placeholder='{{ __("First name")}}'
                                            v-model="form.first_name">
                                    </div>
                                    <span class="invalid-feedback" v-show="form.errors.has('first_name')">
                                        @{{ form.errors.get('first_name') }}
                                    </span>
                                </li>
                                <li class='field size1 align-top'>
                                    <label class='field_label assistant f-16 mb-1'>{{ __('last name')}}</label>
                                    <div class='input_container input_container_text'>
                                        <input type='text'
                                            placeholder='{{ __("Last name")}}'
                                            v-model="form.last_name">
                                    </div>
                                    <span class="invalid-feedback" v-show="form.errors.has('last_name')">
                                        @{{ form.errors.get('last_name') }}
                                    </span>
                                </li>
                                <li class='field size2 align-top'>
                                    <label class='field_label assistant f-16 mb-1'>{{ __('Email Address')}}</label>
                                    <div class='input_container input_container_text'>
                                        <input type='text'
                                            placeholder='{{ __("Emma@xyz.com")}}'
                                            v-model="form.email">
                                    </div>
                                    <span class="invalid-feedback" v-show="form.errors.has('email')">
                                        @{{ form.errors.get('email') }}
                                    </span>
                                </li>
                                <li class='field size2 align-top' v-if="!form.id">
                                    <label class='field_label assistant f-16 mb-1'>{{ __('Password')}}</label>
                                    <div class='input_container input_container_text'>
                                        <input type='password'
                                            id="password"
                                            class="password-text"
                                            placeholder='{{ __("Password")}}'
                                            v-model="form.password">
                                    </div>
                                    <span class="invalid-feedback" v-show="form.errors.has('password')">
                                        @{{ form.errors.get('password') }}
                                    </span>
                                </li>
                                <li class="field size2 align-top password-messages p-0"></li>
                                <li class='field size2 align-top' v-if="!form.id">
                                    <label class='field_label assistant f-16 mb-1'>{{ __('Confirm Password')}}</label>
                                    <div class='input_container input_container_text'>
                                        <input type='password'
                                            placeholder='{{ __("Confirm Password")}}'
                                            v-model="form.password_confirmation">
                                    </div>
                                    <span class="invalid-feedback" v-show="form.errors.has('password_confirmation')">
                                        @{{ form.errors.get('password_confirmation') }}
                                    </span>
                                </li>
                            </ul>
                            <div class='form_footer d-flex flex-column flex-md-row justify-content-between align-items-start'>
                                {{-- @include('partials.common.button-with-loading', [
                                    'title' => __('Add User to Campaign'),
                                    'busyCondition' => '',
                                    'disabledCondition' => ''
                                ]) --}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class='form_wrapper'>
                                <ul class='form_fields'>
                                    <li class='full-fields align-top ml-md-3 pl-md-5'><label class='field_label assistant f-16 mb-1'>{{ __('Profile Photo')}}</label>
                                        <div class='input_container input_container_text d-flex'>
                                            <div class="image-uploader btn-file btn-file--medium float-left" :style="imageStyles.image">
                                                <img src="{{ asset('images/icons/add-a-photo-small.png') }}" class="mb-2" alt="" v-if="! form.image">
                                                <input type="file"
                                                    name="image"
                                                    class="image-uploader-control"
                                                    ref="image"
                                                    @change="changeImage('image')">
                                                <span class="mb-2" v-if="! form.image">{{ __("Add a Photo")}}</span>
                                            </div>
                                        </div>
                                        <span class="invalid-feedback" v-show="form.errors.has('image')">
                                            @{{ form.errors.get('image') }}
                                        </span>
                                        <a href="#"
                                            class="text-link text-link--red font-weight-bold"
                                            @click.prevent="confirmDeleteUploadedFile('image')">
                                            {{ __('Delete Photo') }}</a>
                                        <div class="small f-16">{{ __('Minimum size: ')}}@{{ originalImageFiles.image.requiredWidth }} X @{{ originalImageFiles.image.requiredHeight }}px</div>
                                    </li>
                                </ul>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class='form_footer d-flex flex-column flex-md-row justify-content-between align-items-start'>
                            @include('partials.common.button-with-loading', [
                                'title' => __('Save Changes'),
                                'buttonClass' => 'btn--size4',
                                'busyCondition' => 'form.busy',
                                'submitMethod' => 'submit'
                            ])
                        </div>
                    </div>
                    @include('partials.modals.modal-crop-image', [
                        'modalId' => 'image-crop',
                        'imageName' => 'image',
                        'modalTitle' => __('Edit Image'),
                        'modalSubTitle' => __('Please use the editor below to select the image area you want.')
                    ])
                </div>
            </form>
        </div>
    </section>
</admin-edit>
@endsection
@section('head-tags')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.5/cropper.min.css" rel="stylesheet">
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.5/cropper.min.js"></script>
