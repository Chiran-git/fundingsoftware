@extends('layouts.app')

@section('title')
{{ __('Change Password') }}
@endsection

@section('content')
<change-password inline-template>
<section class="section--def">
    <div class="row mb-1">
        <div class="col-12 col-md-6">
            <div class="px-x-2 mb-5 mt-3">
                <h2>{{ __('Change Password')}}</h2>
            </div>
        </div>
    </div>
    <div class="section__content">
        <form method='post'>
            <div class="row">
                <div class="col-md-6">
                    <div class='form_wrapper'>
                        <ul class='form_fields'>
                            <li class='field size2 align-top'><label class='field_label'>{{ __('Old password')}}</label>
                                <div class='input_container input_container_text'>
                                    <input type='password'
                                        placeholder='{{ __("Password")}}'
                                        v-model="form.old_password">
                                </div>
                                <span class="invalid-feedback" v-show="form.errors.has('old_password')">
                                    @{{ form.errors.get('old_password') }}
                                </span>
                            </li>
                            <li class='field size2 align-top'><label class='field_label'>{{ __('New Password')}}</label>
                                <div class='input_container input_container_text'>
                                    <input type='password'
                                        id="password"
                                        class="password-text"
                                        placeholder='{{ __("New Password")}}'
                                        v-model="form.password">
                                </div>
                                <span class="invalid-feedback" v-show="form.errors.has('password')">
                                    @{{ form.errors.get('password') }}
                                </span>
                            </li>
                            <li class="field size2 align-top password-messages p-0"></li>
                            <li class='field size2 align-top'><label class='field_label'>{{ __('Confirm Password')}}</label>
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
            </div>
        </form>
    </div>
</section>
</change-password>
@endsection
