<div class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            @if (! empty($modalImage))
                <div class="modal-image">
                    <img src="{{$modalImage}}" alt="" class="img-fluid">
                </div>
            @endif
            <div class="modal-header pb-0">
                <h1 class="modal-title">
                    {{ $modalTitle }}
                </h1>
            </div>

            <div class="modal-body pt-0">
                {{ $modalBody }}
            </div>

            <!-- Modal Actions -->
            <div class="modal-footer mb-4">
                @if (! empty($buttons['action']))
                    <a href="{{ $buttons['action']['url'] }}">
                        <button type="button" class="btn btn-default rounded-pill">
                            <i class="fa fa-btn fa-sign-in"></i> {{ $buttons['action']['title'] }}
                        </button>
                    </a>
                @endif
                @if (! empty($buttons['close']))
                    <button type="button"
                        class="btn rounded-pill {{ ! empty($buttons['close']['class']) ? $buttons['close']['class'] : 'btn--outline' }}"
                        data-dismiss="modal">{{ $buttons['close']['title'] }}</button>
                @endif
            </div>
        </div>
    </div>
</div>
