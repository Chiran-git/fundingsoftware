<div v-show="currentStep == 4">
    <org-accountuser :organization="organization" inline-template>
    <section>
        <div v-if="! showAddUserForm">
            <div class="row mb-5">
                <div class="col-12 col-md-6 mr-md-auto">
                    <h2 class="aleo">{{ __('Account Users')}}</h2>
                </div>
                <div class="col-12 col-md-auto">
                    <a href="#"
                        class="btn btn--outline rounded-pill btn--size6 mt-2 mt-md-0 ml-md-3 f-14"
                        @click.prevent="toggleAddUserForm">
                        {{ __('New Account user') }}</a>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-striped--dark-text">
                            <thead>
                                <tr>
                                <th scope="col"><a href="#">{{ __("Name") }}</a></th>
                                <th scope="col"><a href="#">{{ __('Email') }}</a></th>
                                <th scope="col"><a href="#">{{ __('Role') }}</a></th>
                                <th scope="col"><a href="#">{{ __('Access') }}</a></th>
                                <th scope="col"><a href="#">{{ __('Last Login') }}</a></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(user, index) in accountUsers">
                                    <th scope="row">@{{ user.user.first_name + " " + user.user.last_name}}</th>
                                    <td>@{{ user.user.email }}</td>
                                    <td>@{{ `${$root.rj.translations.roles[user.role]}`}}</td>
                                    <td>
                                        <div v-if="user.role == 'campaign-admin'" v-for="campaign of user.campaigns">
                                            <span>@{{ campaign.name }}</span><br/>
                                        </div>
                                        <div v-if="user.role != 'campaign-admin'">@{{ `${$root.rj.translations.all_campaigns}`}}</div>
                                    </td>
                                    <td>
                                        <span v-if="user.user.last_login_at">@{{ $root.convertUTCToBrowser(user.user.last_login_at, 'M/D/YY h:mmA') }}</span>
                                        <span v-else>{{ "-" }}</span>
                                    </td>
                                    <td><a href="#" @click.prevent="editAccountUser(index)"><i class="fas fa-pencil-alt"></i></a></td>
                                    <td><a href="#" @click.prevent="removeAccountUser(index)"><i class="fas fa-times-circle"></a></td>
                                </tr>
                            </tbody>
                        </table>

                        {{--  <table-component
                            :data=Object.values(accountUsers)
                            :show-filter=false
                            table-class="table table-striped table-striped--dark-text"

                            >
                            <table-column show="user.first_name" label="{{ __('Name') }}">
                                <template slot-scope="row">
                                    @{{ row.user.first_name +' '+ row.user.last_name }}
                                 </template>
                            </table-column>
                            <table-column show="user.email" label="{{ __('Email') }}"></table-column>
                            <table-column show="role" :formatter="formatRole" label="{{ __('Role') }}"></table-column>
                            <table-column show="campaigns" :formatter="formatCampaign" label="{{ __('Access') }}"></table-column>
                            <table-column show="user.last_login_at" :formatter="formatLastLogin" label="{{ __('Last Login') }}"></table-column>
                            <table-column label="" :sortable="false" :filterable="false">
                                <template slot-scope="row">
                                   <a :href="`#${row}`">Edit</a>
                                </template>
                            </table-column>
                        </table-component>  --}}

                    </div>
                </div>
            </div>

            <div class="mt-5" v-if="invitedUsers">
                <div class="row mb-5">
                    <div class="col-12 col-md-6 mr-md-auto">
                        <h2 class="aleo">{{ __('Invited Users')}}</h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-striped--dark-text">
                                <thead>
                                    <tr>
                                    <th scope="col"><a href="#">{{ __("Name") }}</a></th>
                                    <th scope="col"><a href="#">{{ __('Email') }}</a></th>
                                    <th scope="col"><a href="#">{{ __('Role') }}</a></th>
                                    <th scope="col"><a href="#">{{ __('Access') }}</a></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(invitedUser, index) of invitedUsers">
                                        <th scope="row">@{{ invitedUser.first_name + " " + invitedUser.last_name}}</th>
                                        <td>@{{ invitedUser.email }}</td>
                                        <td>@{{ `${$root.rj.translations.roles[invitedUser.role]}`}}</td>
                                        <td>
                                            <div v-if="invitedUser.role == 'campaign-admin'" v-for="campaign_name of invitedUser.campaigns">
                                                <span>@{{ campaign_name }}</span><br/>
                                            </div>
                                            <div v-if="invitedUser.role != 'campaign-admin'">@{{ `${$root.rj.translations.all_campaigns}`}}</div>
                                        </td>
                                        <td><a href="#"
                                            v-if="!clicked.includes(index)"
                                            @click.prevent="resendInvitationEmail(index)"
                                            class="text-link--red">
                                            {{ __('Resend') }}</a>
                                        </td>
                                        <td><a href="#" @click.prevent="removeInvitation(index)"><i class="fas fa-times-circle"></a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('organization.partials.addaccount-user')
    </section>

    </org-accountuser>
</div>
