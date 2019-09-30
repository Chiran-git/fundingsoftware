<div  v-show="currentStep == 4">
<org-completed :organization="organization ? organization : {}" inline-template>
    <div class="section__inner">
        <form method='post' @submit.prevent="">
            <div class="section__content section__content--rounder">
                <div class="section__head">
                    <div class="container">
                        <div class="row text-center">
                            <div class="col-12">
                                <h2 class="mb-3">{{ __('Account Setup Complete')}}</h2>
                                <p class="f-20">{{ __('You’re now ready to start your first fundraising campaign!')}}</p>
                                <div class="row">
                                    <div class="col-8 mx-auto">
                                        <img src="{{ asset('images/account_complete.jpg') }}" alt="" class="img-fluid">
                                    </div>
                                </div>
                                <div class='d-flex flex-column flex-md-row justify-content-center align-items-center mt-5'>
                                    <div class="w-sm-50 py-5">
                                            <a @click.prevent="createCampaign()" href="" class='btn btn--dark w-100 btn--py rounded-pill text-tranform-none'>{{ __('Create a Campaign')}}</a>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</org-completed>
</div>
