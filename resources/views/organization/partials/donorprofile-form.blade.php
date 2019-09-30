<div  v-show="currentStep == 3">
<org-donorprofile :organization="organization ? organization : {}" inline-template>
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
            <h5 class="{{ (request()->is('organization/edit')) ? 'd-none' : '' }}">{{ __('Step 3 of 4')}}</h5>
            <h3 class="mb-0">{{ __('Donor Profiles')}}</h3>
            <p class="f-16">{{ __('Would you like to require any extra information from donors?')}}</p>
        </div>
        <div class="section__content">
            <form method='post' @submit.prevent="submit">
            <div class="row">
                <div class="{{ (request()->is('organization/edit')) ? 'col' : 'col-md-6' }}">
                    <div class="grid simple mb-5">
                        <div class="grid-body no-border">
                            <table class="table no-more-tables">
                                <thead>
                                    <tr class="">
                                        <th width="95%" colspan="2">Question</th>
                                        <th width="5%">Required</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="disabled">
                                        <td width="8%" valign="middle" class="pr-0">
                                            <div class="checkbox check-default">
                                                <input type="checkbox" checked disabled>
                                                <label></label>
                                            </div>
                                        </td>
                                        <td width="82%" valign="middle" class="">Donor Name</td>
                                        <td width="10%" valign="middle">
                                            <div class="checkbox check-default">
                                                <input type="checkbox" checked disabled>
                                                <label></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="disabled">
                                        <td width="8%" valign="middle" class="pr-0">
                                            <div class="checkbox check-default">
                                                <input type="checkbox" checked disabled>
                                                <label for="checkbox1"></label>
                                            </div>
                                        </td>
                                        <td width="82%" valign="middle" class="">Email Address</td>
                                        <td width="10%" valign="middle">
                                            <div class="checkbox check-default">
                                                <input type="checkbox" checked disabled>
                                                <label></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="8%" valign="middle">
                                                <div class="checkbox check-default">
                                                    <input id="mailing_address_enabled"
                                                        type="checkbox"
                                                        v-model="systemFieldsForm.mailing_address.enabled"
                                                        @change="updateSystemDonorQuestions">
                                                    <label for="mailing_address_enabled"></label>
                                                </div>
                                        </td>
                                        <td width="82%" valign="middle">{{ __('Mailing Address') }}</td>
                                        <td width="10%" valign="middle">
                                            <ul class="list-inline">
                                                <li class="list-inline-item">
                                                    <div class="checkbox check-default">
                                                        <input id="mailing_address_required"
                                                            type="checkbox"
                                                            v-model="systemFieldsForm.mailing_address.required"
                                                            @change="updateSystemDonorQuestions">
                                                        <label for="mailing_address_required"></label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="8%" valign="middle">
                                                <div class="checkbox check-default">
                                                    <input id="comment_enabled"
                                                        type="checkbox"
                                                        v-model="systemFieldsForm.comment.enabled"
                                                        @change="updateSystemDonorQuestions">
                                                    <label for="comment_enabled"></label>
                                                </div>
                                        </td>
                                        <td width="82%" valign="middle">{{ __('Comment') }}</td>
                                        <td width="10%" valign="middle">
                                            <ul class="list-inline">
                                                <li class="list-inline-item">
                                                    <div class="checkbox check-default">
                                                        <input id="comment_required"
                                                            type="checkbox"
                                                            v-model="systemFieldsForm.comment.required"
                                                            @change="updateSystemDonorQuestions">
                                                        <label for="comment_required"></label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr v-for="(question, index) in donorQuestions">
                                        <td width="8%" valign="middle">
                                                <div class="checkbox check-default">
                                                    <input :id="'question_enabled_' + question.id"
                                                        type="checkbox"
                                                        v-model="question.enabled"
                                                        @change="updateCustomDonorQuestions">
                                                    <label :for="'question_enabled_' + question.id"></label>
                                                </div>
                                        </td>
                                        <td width="82%" valign="middle">@{{ question.question }} <span class="font-italic text-muted">({{ __('Custom Field') }})</span></td>
                                        <td width="10%" valign="middle">
                                            <ul class="list-inline">
                                                <li class="list-inline-item">
                                                    <div class="checkbox check-default">
                                                        <input :id="'question_required_' + question.id"
                                                            type="checkbox"
                                                            v-model="question.is_required"
                                                            @change="updateCustomDonorQuestions">
                                                        <label :for="'question_required_' + question.id"></label>
                                                    </div>
                                                </li>
                                                <li class="list-inline-item ml-3">
                                                    <a href="#"
                                                        class="mx-1 cross f-16"
                                                        @click.prevent="removeDonorQuestion(index)"><i class="fas fa-times-circle"></i></a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            </form>
            <div class='form_wrapper form_wrapper--def px-2 {{ (request()->is('organization/edit')) ? '' : 'form-container--small' }}'>
                <form method="post" @submit.prevent="addCustomDonorQuestion">
                    <h3 class="field__title">{{ __('Add a Custom Field:')}}</h3>
                    <ul class='form_fields field__container w-100'>
                        <li class='field size2 align-top pb-3'>
                            <div class='input_container input_container_text'>
                                <input type='text'
                                    placeholder='{{ __("What question do you want to ask?")}}'
                                    v-model="customFieldForm.question"
                                    :class="{'is-invalid': customFieldForm.errors.has('question')}">
                            </div>
                            <span class="invalid-feedback" v-show="customFieldForm.errors.has('question')">
                                @{{ customFieldForm.errors.get('question') }}
                            </span>
                        </li>
                        <div class='form_footer d-flex flex-column flex-md-row justify-content-start align-items-start px-2 pb-3'>
                            <button type='submit' class='btn btn--transparent btn--size3 rounded-pill text-tranform-none btn--lightborder'>{{ __('Add this Field')}}</button>
                        </div>
                    </ul>
                </form>
            </div>
        </div>
        <div class='form_footer d-flex flex-column flex-md-row justify-content-start align-items-start mt-5'>
                @include('partials.common.button-with-loading', [
                    'title' => $buttonTitle,
                    'buttonClass' => $buttonclass,
                    'busyCondition' => 'customFieldForm.busy',
                    'submitMethod' => __('submit')
                ])
            {{-- <button type='submit'
                class='btn btn--dark btn--size4 rounded-pill'
                @click="{{ $submitMethod }}">
                {{ $buttonTitle }}</button> --}}
        </div>
    </div>
</org-donorprofile>
</div>
