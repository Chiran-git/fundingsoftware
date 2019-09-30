<div class="modal fade" id="switch-organization" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-switch-organization">
        <div class="modal-content bg-grey p-3 pt-5 p-md-5">
            {{--  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>  --}}
            <h3 class="text-center aleo">{{ __("Select an Organization:") }}</h3>

            <div class="container">
                <div class="row">
                    <switch-organization inline-template>
                    <div class="col-12 px-0 bg-white">
                        <form class="form-inline border">
                            <div class="form-group has-search mb-0">
                                <span class="fa fa-search form-control-feedback"></span>
                                <input type="text" class="form-control bg-white" v-model="search" placeholder="{{ __('Search') }}">
                            </div>
                            <ul class="border-top" v-if="organizations">
                                <li v-for="organization in filteredOrganizations" v-on:click="clickRow(organization.id)" class='clickable-row'>
                                    <img src="https://d1npqtu3gctny5.cloudfront.net/local/uploads/public/organizations/4/FpziwY94hvuOz6mdxEDmERv04S4MwLTj1OHqajYF.jpeg" alt="" class="rounded-circle mr-2">
                                    @{{ organization.name }}
                                </li>
                            </ul>
                        </form>
                    </div>
                </switch-organization>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="select-organization-campaign" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-switch-organization">
        <div class="modal-content bg-grey p-3 pt-5 p-md-5">
            {{--  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>  --}}
            <h3 class="text-center aleo">{{ __("Select an Organization:") }}</h3>

            <div class="container">
                <div class="row">
                    <switch-organization inline-template>
                    <div class="col-12 px-0 bg-white">
                        <form class="form-inline border">
                            <div class="form-group has-search mb-0">
                                <span class="fa fa-search form-control-feedback"></span>
                                <input type="text" class="form-control bg-white" v-model="search" placeholder="{{ __('Search') }}">
                            </div>
                            <ul class="border-top" v-if="organizations">
                                <li v-for="organization in filteredOrganizations" v-on:click="selectOrganizationForCampaign(organization.id)" class='clickable-row'>
                                    <img src="https://d1npqtu3gctny5.cloudfront.net/local/uploads/public/organizations/4/FpziwY94hvuOz6mdxEDmERv04S4MwLTj1OHqajYF.jpeg" alt="" class="rounded-circle mr-2">
                                    @{{ organization.name }}
                                </li>
                            </ul>
                        </form>
                    </div>
                </switch-organization>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="select-organization-donation" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-switch-organization">
        <div class="modal-content bg-grey p-3 pt-5 p-md-5">
            {{--  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>  --}}
            <h3 class="text-center aleo">{{ __("Select an Organization:") }}</h3>

            <div class="container">
                <div class="row">
                    <switch-organization inline-template>
                    <div class="col-12 px-0 bg-white">
                        <form class="form-inline border">
                            <div class="form-group has-search mb-0">
                                <span class="fa fa-search form-control-feedback"></span>
                                <input type="text" class="form-control bg-white" v-model="search" placeholder="{{ __('Search') }}">
                            </div>
                            <ul class="border-top" v-if="organizations">
                                <li v-for="organization in filteredOrganizations" v-on:click="selectOrganizationForDonation(organization.id)" class='clickable-row'>
                                    <img src="https://d1npqtu3gctny5.cloudfront.net/local/uploads/public/organizations/4/FpziwY94hvuOz6mdxEDmERv04S4MwLTj1OHqajYF.jpeg" alt="" class="rounded-circle mr-2">
                                    @{{ organization.name }}
                                </li>
                            </ul>
                        </form>
                    </div>
                </switch-organization>
                </div>
            </div>
        </div>
    </div>
</div>

