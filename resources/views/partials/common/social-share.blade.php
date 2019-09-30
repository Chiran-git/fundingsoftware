<ul class="list-inline mb-0 mt-auto share-campaign">
     <li class="list-inline-item mb-2">
        <button data-share-button="facebook" class="btn btn-facebook rounded-pill cursor-pointer assistant text-left py-1 pl-3 pl-sm-4 l-h-normal share-button" data-share-url="{{ $shareUrl }}">
            <i class="fab fa-facebook-f mr-2 f-16"></i>{{ __('Share') }}
        </button>
    </li>
    <li class="list-inline-item mb-2">
        <button data-share-button="twitter" data-share-text="{{ $shareText ?: $shareUrl }}" class="btn btn-twitter rounded-pill cursor-pointer assistant text-left py-1 pl-2 pl-sm-3 l-h-normal share-button"><i class="fab fa-twitter mr-2 f-16"></i></i>{{ __('Tweet') }}</button>
    </li>
    <li class="list-inline-item mb-2">
        @if (isset($shareButton))
            <button class="btn btn-share rounded-pill cursor-pointer assistant text-left py-1 px-2 pl-sm-4 l-h-normal" data-href="{{ $shareUrl }}"><i class="fa fa-link fa-fw mr-2 f-16"></i>{{ __('Share') }}</button>
        @endif

        @if (isset($emailButton))
            <a class="btn btn-email rounded-pill cursor-pointer assistant text-left py-1 pl-4 l-h-normal" href="mailto:?subject={{ urlencode($shareHeadline) }}&amp;body={{ urlencode($shareText) }}"><i class="fa fa-link fa-fw mr-1 mr-sm-2 f-16"></i>{{ __('Email') }}</a>
        @endif
    </li>
</ul>
