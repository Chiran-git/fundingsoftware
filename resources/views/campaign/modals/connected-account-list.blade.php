<div class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-cm-lg">
        <div class="modal-content grayscale rounded-0 px-3 py-5 p-md-4 p-lg-5">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <h1 class="text-center aleo mb-1">{{ __("Pay-Out Accounts") }}</h1>
            <section>
                <div class="row">
                    <div class="col-12">
                        <ul class="list-fixed-head list-fixed-head--secondary mt-4">
                            <li class="head-fixed">
                                <ul>
                                    <li>
                                        <strong>{{ __('Name') }}</strong>
                                    </li>
                                    <li>
                                        <strong>{{ __('Campaigns') }}</strong>
                                    </li>
                                    <li>
                                        <strong></strong>
                                    </li>
                                </ul>
                            </li>
                            <div class="list--content list--content--dark">
                                <li v-for="(account, index) in accounts">
                                    <ul>
                                        <li>
                                            <p><strong>@{{ account.account_nickname }}</strong>
                                            <span class="d-block" v-if="account.external_account_name">@{{ account.external_account_name }} xxxx@{{ account.external_account_last4 }}</span></p>
                                        </li>
                                        <li>
                                            <p :class="{'text-muted': account.campaigns.length == 0}">
                                                <span class="d-block" v-for="campaign in account.campaigns">
                                                    @{{ campaign.name }}
                                                </span>
                                                <span v-if="! account.campaigns.length">None</span>
                                            </p>
                                        </li>
                                        <li>
                                            <a href="" @click.prevent="editAccount(account)">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <a href="" class="ml-2" @click.prevent="confirmDeleteAccount(index)">
                                                <i class="fas fa-times-circle"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </div>
                        </ul><!-- /.list-members -->
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
