
<section {{-- class="d-none" --}} v-if="showAddUserForm">
    <div class="row mb-1">
        <div class="col-12 col-md-6">
            <div class="px-x-2 mb-4">
                <h5 class="aleo mb-1">{{ __('Account Users')}}</h5>
                <h2 v-if="editForm">{{ __('Edit User')}}</h2>
                <h2 v-else>{{ __('New Account User')}}</h2>
            </div>
        </div>
    </div>
    <div class="section__content">
        <div class="form-container">
            <div class='form_wrapper form_wrapper--primary-alt'>
                <form method='post' @submit.prevent="">
                    <ul class='form_fields'>
                        <li class='field size1 align-top'><label class='field_label'>{{ __('first name')}}</label>
                            <div class='input_container input_container_text'>
                                <input type='text'
                                    placeholder='{{ __("Your first name")}}'
                                    v-model="form.first_name"
                                    :class="{'is-invalid': form.errors.has('first_name')}"
                                    :disabled="userExists">
                            </div>
                            <span class="invalid-feedback" v-show="form.errors.has('first_name')">
                                @{{ form.errors.get('first_name') }}
                            </span>
                        </li>
                        <li class='field size1 align-top'><label class='field_label'>{{ __('last name')}}</label>
                            <div class='input_container input_container_text'>
                                <input type='text'
                                    placeholder='{{ __("Your last name")}}'
                                    v-model="form.last_name"
                                    :class="{'is-invalid': form.errors.has('last_name')}"
                                    :disabled="userExists">
                            </div>
                            <span class="invalid-feedback" v-show="form.errors.has('last_name')">
                                @{{ form.errors.get('last_name') }}
                            </span>
                        </li>
                        <li class='field size2 align-top'><label class='field_label'>{{ __('Email Address')}}</label>
                            <div class='input_container input_container_text'>
                                <input type='text'
                                    placeholder='{{ __("Emma@xyz.com")}}'
                                    v-model="form.email"
                                    @change="checkEmail"
                                    :class="{'is-invalid': form.errors.has('email')}">
                            </div>
                            <span class="invalid-feedback" v-show="form.errors.has('email')">
                                @{{ form.errors.get('email') }}
                            </span>
                            <span class="text-info mt-1" v-show="userExists">
                                <strong>@{{ form.email }}</strong> {{ __('is already a registered user') }}
                            </span>
                        </li>
                        <li class='field size2 align-top'><label class='field_label'>{{ __('User Role')}}</label>
                            <div class='input_container_select'>
                                <v-select :options="roleOptions"
                                        placeholder="{{ __('Select Role') }}"
                                        v-model="form.role"
                                        :disabled="userExists">
                                    <template slot="option" slot-scope="option">
                                            @{{ getRoleOptionLabel(option.label) }}
                                    </template>
                                    <template slot="selected-option" slot-scope="option">
                                        @{{ getRoleOptionLabel(option.label) }}
                                    </template>
                                </v-select>
                            </div>
                            <span class="invalid-feedback" v-show="form.errors.has('role')">
                                @{{ form.errors.get('role') }}
                            </span>
                        </li>
                        <li class='field size2 align-top' v-if="form.role=='campaign-admin'">
                            <label class='field_label'>{{ __('Campaign Access')}}</label>
                            <div class="field_checkbox">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="campaign_ids"
                                        v-model="allSelected"
                                        id="camp-id-0"
                                        @click="selectAll">

                                    <label class="form-check-label font-weight-bold" for="camp-id-0">
                                        {{ __('Select All') }}
                                    </label>
                                </div>
                            </div>

                            <div class="field_checkbox my-3" v-for="(camp_name, camp_id) in campaignList">
                                <div class="form-check">
                                    <input type="checkbox" name="campaign_ids"
                                        :id="'camp-id-' + camp_id"
                                        :value="camp_id"
                                        class="form-check-input"
                                        v-model="form.campaign_ids">

                                    <label class="form-check-label" :for="'camp-id-' + camp_id">
                                        @{{ camp_name }}
                                    </label>
                                </div>
                            </div>
                            <span class="invalid-feedback" v-show="form.errors.has('campaign_ids')">
                                @{{ form.errors.get('campaign_ids') }}
                            </span>
                        </li>
                    </ul>
                    <div
                        class='form_footer d-flex flex-column flex-md-row justify-content-start align-items-start'
                        v-if="editForm">
                        @include('partials.common.button-with-loading', [
                            'title' => __('Update User'),
                            'busyCondition' => 'form.busy',
                            'disabledCondition' => 'form.busy',
                            'submitMethod' => 'update'
                        ])
                        <button type='submit'
                            class='btn btn--outline btn--size4 rounded-pill mt-4 mt-md-0 ml-md-4'
                            @click.prevent="toggleAddUserForm">
                            {{ __('Cancel')}}</button>
                    </div>
                    <div
                        class='form_footer d-flex flex-column flex-md-row justify-content-start align-items-start pl-0'
                        v-else>
                        @include('partials.common.button-with-loading', [
                            'title' => __('Add User'),
                            'busyCondition' => 'form.busy',
                            'disabledCondition' => 'form.busy',
                            'submitMethod' => 'submit'
                        ])
                        <button type='submit'
                            class='btn btn--outline btn--size4 rounded-pill mt-4 mt-md-0 ml-md-4'
                            @click.prevent="toggleAddUserForm">
                            {{ __('Cancel')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
