@extends('layouts.app')

@section('title', "RocketJar")

@section('content')
<admin-users inline-template>
    <section class="section--def">
        <div class="row mb-1">
            <div class="col-12 col-md-6">
                <div class="px-x-2 mb-4">
                    <h5 class="aleo mb-1">{{ __('Account Users')}}</h5>
                    <h2>{{ __('Deactivate Account')}}</h2>
                </div>
            </div>
        </div>
        <div class="section__content">
            <div class="form-container form-container--small">
                <div class='form_wrapper form_wrapper--primary-alt'>
                    <form method='post' @submit.prevent="">
                        <div class='form_footer d-flex flex-column flex-md-row justify-content-between align-items-start'>
                            {{-- @include('partials.common.button-with-loading', [
                                'title' => __('Add User to Campaign'),
                                'busyCondition' => '',
                                'disabledCondition' => ''
                            ]) --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</admin-users>
@endsection
