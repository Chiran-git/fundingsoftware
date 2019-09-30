<div v-show="currentStep == 6">
    <campaign-completed
        inline-template
        :organization="organization"
        :campaign='campaign'
        v-if="showChildComponents">
    <div class="section__inner">
        <div class="section__head">
            <h5>{{ __('Step 6 of 6')}}</h5>
            <h3 class="mb-2">{{ __('Preview & Publish')}}</h3>
            <p class="f-18">{{ __("Almost finished! Publish your campaign to take it live. You can also preview your campaign prior to launching it.") }}</p>
        </div>
        <form method='post' @submit.prevent="">
            <div class="section__content section__content--rounder mt-lg-5">
                <div class="section__head">
                    <div class="container">
                        <div class="row text-center">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6 mx-auto">
                                        <img src="{{ asset('images/account_complete.jpg') }}" alt="" class="img-fluid">
                                    </div>
                                </div>
                                <h2 class="mt-5 mb-4">Ready to launch?</h2>
                                <div class='d-flex flex-column flex-md-row justify-content-center'>
                                    <button type='submit'
                                        class='btn btn--dark btn--size7 btn--size15 rounded-pill mb-4 mb-md-0'
                                        @click.prevent="publishCampaign">
                                        {{ __('Publish Campaign')}}</button>
                                    <button type='submit'
                                        class='btn btn--transparent btn--size7 btn--size15 rounded-pill ml-md-3'
                                        @click.prevent="showPreviewModal()" >
                                        {{ __('Preview Campaign')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        @include('campaign.preview.modal-campaign-preview', [
            'modalId' => 'campaign-complete-preview',
        ])
    </div>
    </campaign-completed>
</div>
