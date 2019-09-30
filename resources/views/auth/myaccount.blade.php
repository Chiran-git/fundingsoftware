@extends('layouts.app')

@section('title', __('My Account'))

@section('content')
<my-account inline-template :current-user="user" v-if="user">
    <section class="section--def">
        <div class="row mb-1">
            <div class="col-12 col-md-6">
                <div class="px-x-2 mb-5 mt-3">
                    <h2>{{ __('My Account')}}</h2>
                </div>
            </div>
        </div>
        <div class="section__content">
            <form method='post' @submit.prevent="">
                <div class="row">
                    <div class="col-md-6">
                        <div class='form_wrapper'>
                            <ul class='form_fields'>
                                <li class='field size1 align-top'><label class='field_label f-16 mb-1 mb-1 {{ auth()->user()->isSuperadmin() ? '' : 'assistant' }}'>{{ __('first name')}}</label>
                                    <div class='input_container input_container_text'>
                                        <input type='text'
                                            placeholder='{{ __("Your first name")}}'
                                            v-model="user.first_name">
                                    </div>
                                    <span class="invalid-feedback" v-show="user.errors.has('first_name')">
                                        @{{ user.errors.get('first_name') }}
                                    </span>
                                </li>
                                <li class='field size1 align-top'><label class='field_label f-16 mb-1 {{ auth()->user()->isSuperadmin() ? '' : 'assistant' }}'>{{ __('last name')}}</label>
                                    <div class='input_container input_container_text'>
                                        <input type='text'
                                            placeholder='{{ __("Your last name")}}'
                                            v-model="user.last_name">
                                    </div>
                                    <span class="invalid-feedback" v-show="user.errors.has('last_name')">
                                        @{{ user.errors.get('last_name') }}
                                    </span>
                                </li>
                                <li class='field size2 align-top'><label class='field_label f-16 mb-1 {{ auth()->user()->isSuperadmin() ? '' : 'assistant' }}'>{{ __('Email Address')}}</label>
                                    <div class='input_container input_container_text'>
                                        <input type='text'
                                            placeholder='{{ __("Emma@xyz.com")}}'
                                            v-model="user.email">
                                    </div>
                                    <span class="invalid-feedback" v-show="user.errors.has('email')">
                                        @{{ user.errors.get('email') }}
                                    </span>
                                </li>
                                @if (! auth()->user()->isSuperAdmin())
                                    <li class='field size2 align-top'><label class='field_label assistant f-16 mb-1'>{{ __('Job Title')}}</label>
                                        <div class='input_container input_container_text'>
                                            <input type='text'
                                                placeholder='{{ __("Principal")}}'
                                                v-model="user.job_title">
                                        </div>
                                        <span class="invalid-feedback" v-show="user.errors.has('job_title')">
                                            @{{ user.errors.get('job_title') }}
                                        </span>
                                    </li>
                                @else
                                    <li class='field size2 align-top'><label class='field_label assistant f-16 mb-1'>{{ __('Role')}}</label>
                                        <div class='input_container_select'>
                                            <v-select :options="roleOptions"
                                                v-model="user.role" :class="{'is-invalid': user.errors.has('role')}"
                                                placeholder="RocketJar Account Owner Executive"
                                                disabled="">
                                            </v-select>
                                        </div>
                                        <span class="invalid-feedback" v-show="user.errors.has('role')">
                                            @{{ user.errors.get('role') }}
                                        </span>
                                    </li>
                                @endif
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
                                    <li class='full-fields align-top ml-md-3 pl-md-5'><label class='field_label f-16 mb-1 {{ auth()->user()->isSuperadmin() ? '' : 'assistant' }}'>{{ __('Profile Photo')}}</label>
                                        <div class='input_container input_container_text d-flex'>
                                            <div class="image-uploader btn-file btn-file--medium float-left" :style="imageStyles.image">
                                                <img src="{{ asset('images/icons/add-a-photo-small.png') }}" class="mb-2" alt="" v-if="! user.image">
                                                <input type="file"
                                                    name="image"
                                                    class="image-uploader-control"
                                                    ref="image"
                                                    @change="changeImage('image')">
                                                <span class="mb-2" v-if="! user.image">{{ __("Add a Photo")}}</span>
                                            </div>
                                        </div>
                                        <span class="invalid-feedback" v-show="user.errors.has('image')">
                                            @{{ user.errors.get('image') }}
                                        </span>
                                        <template v-if="user.image">
                                        <a href="#"
                                            class="text-link text-link--red font-weight-bold"
                                            @click.prevent="confirmDeleteUploadedFile('image')">
                                            {{ __('Delete') }}</a> |
                                            <a href="" class="text-link" @click.prevent="openFileDialog('image')">{{ __("Change")}}</a>
                                        </template>
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
                                'busyCondition' => 'user.busy',
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
</my-account>
@endsection
@section('head-tags')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.5/cropper.min.css" rel="stylesheet">
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.5/cropper.min.js"></script>
