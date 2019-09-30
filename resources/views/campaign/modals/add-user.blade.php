<div class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" ref="inviteUserModal">
    <div class="modal-dialog modal-dialog-centered modal-cm-lg">
        <div class="modal-content grayscale rounded-0 py-5 px-3">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
            <h2 class="text-center">{{ __("Add a User") }}</h2>
            <div class='form_wrapper'>
                        <form method='post' @submit.prevent="submit">
                            <div class='form_body'>
                                <ul class='form_fields'>
                                    <li class='field size2 align-top'><label class='field_label assistant'>{{ __('Email Address')}}</label>
                                        <div class='input_container input_container_text'>
                                            <input type='text'
                                                tabindex='1'
                                                placeholder='{{ __("Emma@xyz.com")}}'
                                                @change="checkEmail"
                                                v-model="form.email"
                                                :class="{'is-invalid': form.errors.has('email')}">
                                        </div>
                                        <span class="invalid-feedback" v-show="form.errors.has('email')">
                                            @{{ form.errors.get('email') }}
                                        </span>
                                        <span class="text-info mt-1" v-show="userExists">
                                            <strong>@{{ form.email }}</strong> {{ __('is already a registered user') }}
                                        </span>
                                    </li>
                                    <li class='field size1 align-top'><label class='field_label assistant'>{{ __('first name')}}</label>
                                        <div class='input_container input_container_text'>
                                            <input type='text'
                                                tabindex='2'
                                                placeholder='{{ __("Your first name")}}'
                                                v-model="form.first_name"
                                                :class="{'is-invalid': form.errors.has('first_name')}"
                                                :disabled="userExists">
                                        </div>
                                        <span class="invalid-feedback" v-show="form.errors.has('first_name')">
                                            @{{ form.errors.get('first_name') }}
                                        </span>
                                    </li>
                                    <li class='field size1 align-top'><label class='field_label assistant'>{{ __('last name')}}</label>
                                        <div class='input_container input_container_text'>
                                            <input type='text'
                                                tabindex='3'
                                                placeholder='{{ __("Your last name")}}'
                                                v-model="form.last_name"
                                                :class="{'is-invalid': form.errors.has('last_name')}"
                                                :disabled="userExists">
                                        </div>
                                        <span class="invalid-feedback" v-show="form.errors.has('last_name')">
                                            @{{ form.errors.get('last_name') }}
                                        </span>
                                    </li>
                                    <li class='field size2 align-top'><label class='field_label assistant'>{{ __('User Role')}}</label>
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
                                                {{-- <select
                                                    :class="{'is-invalid': form.errors.has('state')}"
                                                    v-model="form.role"
                                                    :disabled="userExists">
                                                    <option
                                                        v-for="(label, value) in roles[0]"
                                                        :value="value"
                                                        >
                                                        @{{ label }}</option>
                                                </select> --}}
                                            </div>
                                        <span class="invalid-feedback" v-show="form.errors.has('role')">
                                            @{{ form.errors.get('role') }}
                                        </span>
                                    </li>
                                </ul>
                            </div>
                            <div class='form_footer d-flex flex-column flex-md-row justify-content-between align-items-start'>
                                {{-- @include('partials.common.button-with-loading', [
                                    'title' => __('Add User to Campaign'),
                                    'busyCondition' => '',
                                    'disabledCondition' => ''
                                ]) --}}
                        <button type='submit'
                            class='btn btn--dark btn--size6 rounded-pill'
                            @click.prevent="submit"
                            :disabled="form.busy">
                            <span v-if="! form.busy">{{ __('Add User To Campaign')}}</span>
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
