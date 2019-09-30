<div v-show="currentStep == 4 || campaignId">
    <campaign-invite-user inline-template
        :campaign='campaign'
        :organization="organization"
        :campaign-users="campaignUsers"
        v-if="showChildComponents">
        <div class="section__inner">
            <div class="section__head">
                @if (isset($action) && $action == 'edit')
                    <h3 class="pt-2 border-top-2">
                        <span class="edit-serial-no">{{ __('4')}}</span>
                        {{ __('Users')}}
                    </h3>
                @else
                    <h5>{{ __('Step 4 of 6')}}</h5>
                    <h3 class="mb-2">{{ __('Invite Users')}}</h3>
                @endif
                <p class="f-18">{{ __("Invite your colleagues to access and edit this campaign and view donations.") }}</p>
            </div>
            <div class="section__content container px-x-2 mt-lg-5">
                <div class='form_wrapper form_wrapper--def'>
                    <form method='post' @submit.prevent="submit">
                        <h3 class="field__title">{{ __('Current Users')}}</h3>
                        <div class='field__container'>
                            <dl class="d-flex flex-wrap mb-0" v-for="user of campaignUsers">
                                <dt class="pl-md-4 mr-3 f-16">@{{ user.first_name + " " + user.last_name}}</dt>
                                <dd class="font-italic">
                                    @{{ $root.rj.translations.roles[user.role] }}
                                    <span v-if="! user.id">(@{{ $root.rj.translations.invitation_pending }})</span>
                                </dd>
                            </dl>
                            @if (in_array(auth()->user()->currentRole(), ['owner', 'admin']))
                                <div class='form_footer d-flex flex-column flex-md-row justify-content-start align-items-start px-0 p-md-4'>
                                    <button type='submit' class='btn btn--transparent btn--size4 rounded-pill text-tranform-none btn--lightborder px-4' @click.prevent="showInviteModal()">{{ __('Add Another User')}}</button>
                                </div>
                            @endif
                        </div>
                        @if (!isset($action) || $action != 'edit')
                            <div class='form_footer d-flex flex-column flex-md-row justify-content-between align-items-start px-0 mt-5'>
                                <button type='submit'
                                    class='btn btn--dark btn--size6 btn--size15 rounded-pill'
                                    @click.prevent="goToPayout()">
                                        {{ __('Next')}} <i class="fas fa-chevron-right f-8"></i>
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
            @include('campaign.modals.add-user', [
                'modalId' => 'modal-add-user',
            ])
        </div>
    </campaign-invite-user>
</div>
