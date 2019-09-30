<div v-show="currentStep == 2 || campaignId">
    <camp-reward inline-template
        :campaign='campaign'
        :organization="organization"
        :rewards="rewards"
        v-if="campaign && rewards && showChildComponents">
        <div class="section__inner">
            <div class="section__head">
                @if (isset($action) && $action == 'edit')
                    <h3 class="pt-2 border-top-2">
                        <span class="edit-serial-no">{{ __('2')}}</span>
                        {{ __('Rewards')}}
                    </h3>
                @else
                    <h5>{{ __('Step 2 of 6')}}</h5>
                    <h3 class="mb-2">{{ __('Rewards')}}</h3>
                @endif
                <p class="f-18">{{ __('Offer rewards to encourage donors to give suggested amounts. Rewards are optional, but can increase your campaign\'s effectiveness.') }}</p>
            </div>
            <div class="section__content mt-lg-5">
                <div class="container px-x-2">
                    <div class='form_wrapper form_wrapper--def mt-2' v-for="(form, index) in forms">
                        <form method='post' @submit.prevent="submit">
                            <div class="field__container p-4">
                                <div class="row">
                                    <div class="col-12 col-lg-9 form_fields">
                                        <div class="form-row mb-3">
                                            <div class="form-group col-md-6 col-lg-8 pr-lg-4">
                                                <label class='field_label'>{{ __('Reward Title') }}</label>
                                                <input type="type" class="form-control" placeholder="{{ __('Gold level') }}" v-model="form.title"
                                                :class="{'is-invalid': form.errors.has('title')}">
                                                <span class="invalid-feedback" v-show="form.errors.has('title')">
                                                    @{{ form.errors.get('title') }}
                                                </span>
                                            </div>
                                            <div class="form-group col-md-6 col-lg-4">
                                                <label class='field_label text-tranform-none'>{{ __('Donation Required for Rewards') }}</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text px-3">$</span>
                                                    </div>
                                                    <input type="text"
                                                        class="form-control"
                                                        id="validationCustomUsername"
                                                        placeholder="{{ __('1000.00') }}"
                                                        v-model="form.min_amount"
                                                        :class="{'is-invalid': form.errors.has('min_amount')}">
                                                    <span class="invalid-feedback" v-show="form.errors.has('min_amount')">
                                                        @{{ form.errors.get('min_amount') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-lg-8 pr-lg-4">
                                                <label class='field_label' for="inputEmail4">{{ __('Reward Description') }} <span>{{ __('Optional') }}</span></label>
                                                <textarea class="field h-75"
                                                    placeholder="{{ __('Receive a water bottle and t-shirt for donating $100 or more') }}"
                                                    v-model="form.description"
                                                    :class="{'is-invalid': form.errors.has('description')}"></textarea>
                                                <span class="invalid-feedback" v-show="form.errors.has('description')">
                                                    @{{ form.errors.get('description') }}
                                                </span>
                                            </div>
                                            <div class="form-group col-md-6 col-lg-4 field">
                                                <label class='field_label text-tranform-none'>{{ __('Quantity of Rewards available') }}</label>
                                                <input type="type"
                                                    class="form-control"
                                                    placeholder="{{ __('100') }}"
                                                    v-model="form.quantity"
                                                    :class="{'is-invalid': form.errors.has('quantity')}">
                                                <span class="invalid-feedback" v-show="form.errors.has('quantity')">
                                                    @{{ form.errors.get('quantity') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-3">
                                        <ul class='form_fields'>
                                            <li class='field size2 align-top pb-2'><label class='field_label mb-0'>{{ __('Reward Image')}} <span>{{ __('Optional')}}</span></label>
                                                <div class='input_container input_container_text'>
                                                    <div class="image-uploader image-uploader--sec btn-file" :style="imageStyles[index].image">
                                                        <img src="{{ asset('images/icons/add-a-photo.png') }}" alt=""  v-if="! rewards[index].image">
                                                        <input type="file"
                                                            name="image"
                                                            class="image-uploader-control"
                                                            ref="image"
                                                            @change="changeImage('image', index)">
                                                        <template v-if="! rewards[index].image">
                                                            <span>{{ __("Add a Photo")}}</span>
                                                            <span class="small f-16">{{ __('Minimum size: ')}}@{{ originalImageFiles.image[index].requiredWidth }} X @{{ originalImageFiles.image[index].requiredHeight }}px</span>
                                                        </template>
                                                    </div>
                                                </div>
                                                <span class="invalid-feedback" v-show="form.errors.has('image')">
                                                    @{{ form.errors.get('image') }}
                                                </span>
                                                <span class="d-block f-16" v-if="rewards[index].image">
                                                    <a href="" class="text-link" @click.prevent="openFileDialog('image', index)">{{ __("Change")}}</a>
                                                    | <a href="" class="text-link text-link--red" @click.prevent="confirmDeleteUploadedFile('image', index)">{{ __("Delete")}}</a>
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col pt-1 pb-2 text-right"><a href="" class="text-link text-link--red aleo font-weight-bold" @click.prevent="confirmRemoveRewardForm(index)"><span class="font-weight-light"><i class="fas fa-minus"></i></span> {{ __('Remove Reward') }}</a></div>
                        </form>
                        @include('partials.modals.modal-crop-image-multiple', [
                            'modalId' => 'image-crop',
                            'imageName' => 'image',
                            'modalTitle' => __('Edit Image'),
                            'modalSubTitle' => __('Please use the editor below to select the image area you want.')
                        ])
                        </div>

                        <div class="row">
                            <div class="col pt-3 pb-5 mb-4"><a href="" class="text-link text-link--red aleo font-weight-bold" @click.prevent="addRewardForm"><span class="font-weight-light"><i class="fas fa-plus"></i></span> {{ __('Add Another Reward') }}</a></div>
                        </div>
                        <div class='form_footer d-flex flex-column flex-md-row align-items-start px-0'>
                            {{-- @include('partials.common.button-with-loading', [
                                'title' => __('Next') . ' <i class="fas fa-chevron-right f-8"></i>',
                                'busyCondition' => 'forms[0].busy',
                                'buttonClass' => 'btn--size6',
                                'attributes' => "@click.prevent='submit'",
                            ]) --}}
                            <button type='submit'
                                class='btn btn--dark btn--size6 btn--size15 rounded-pill'
                                @click.prevent="submit"
                                :disabled="forms[0].busy">
                                @if (isset($action) && $action == 'edit')
                                    <span v-if="! forms[0].busy">{{ __('Save Changes')}}</span>
                                @else
                                    <span v-if="! forms[0].busy">{{ __('Next')}} <i class="fas fa-chevron-right f-8"></i></span>
                                @endif

                                    <span v-else>
                                        @include('partials.common.loading')
                                    </span>
                            </button>
                                <button type='submit' class='btn btn--outline btn--size6 btn--size15 rounded-pill mt-4 mt-md-0 ml-md-4'
                                @click.prevent="$parent.showPreview('campaign-rewards-preview')">{{ __('Preview Campaign')}}</button>
                        </div>
                </div>
                @include('campaign.preview.modal-rewards-preview', [
                    'modalId' => 'campaign-rewards-preview',
                ])
            </div>
        </div>
    </camp-reward>
</div>
