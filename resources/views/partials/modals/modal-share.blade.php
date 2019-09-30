<div class="modal-share" :id="modalId">
    <div class="modal-content p-3 p-md-5">
        <button type="button" class="close fw-400 close-share-popup" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h2 class="text-center aleo mb-2">@{{ modalTitle }}</h2>
        <h3 class="text-center aleo fw-400 mb-0 break-word">@{{ modalSubtitle }}</h3>

        <div class="container">
            <div class="row">
                <div class="col-12 px-0">
                    <div class="input-group my-5">
                        <input type="text" class="form-control rounded-lb-30" :value="shareUrl" :id="modalId + '-share-url-input'">
                        <div class="input-group-append">
                            <button class="btn btn--red py-1 rounded-rb-30 btn-share"
                                type="button"
                                :data-clipboard-target="'#' + modalId + '-share-url-input'">{{ __('Copy Link') }}</button>
                        </div>
                    </div>
                    <ul class="list-inline mb-0 mt-auto">
                        <li class="list-inline-item mb-2">
                            <button data-share-button="facebook" :data-share-url="shareUrl" class="btn btn--size2 btn-facebook rounded-pill cursor-pointer assistant text-left py-2 pl-4 l-h-normal f-18 share-button">
                                <i class="fab fa-facebook-f mr-2 f-18"></i>{{ __('Share') }}
                            </button>
                        </li>
                        <li class="list-inline-item mb-2">
                            <button data-share-button="twitter" :data-share-text="shareText" class="btn btn--size2 btn-twitter rounded-pill cursor-pointer assistant text-left py-2 pl-4 l-h-normal f-18 share-button"><i class="fab fa-twitter mr-2 f-18"></i></i>{{ __('Tweet') }}</button>
                        </li>
                        <li class="list-inline-item mb-2">
                            <a class="btn btn--size2 btn-email rounded-pill cursor-pointer assistant text-left py-2 pl-4 l-h-normal f-18" :href="'mailto:?subject=' + shareHeadline + '&amp;body=' + shareText"><i class="fa fa-envelope fa-fw mr-2 f-18"></i>{{ __('Email') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
