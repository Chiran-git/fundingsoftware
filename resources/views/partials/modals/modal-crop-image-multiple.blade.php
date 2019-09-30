<div class="modal fade" :id="'{{ $modalId }}' + index" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header pb-0">
                <h1 class="modal-title">
                    {{ $modalTitle }}
                </h1>
            </div>

            <div class="modal-body pt-0">
                @if ($modalSubTitle)
                    <h3 class="text-center aleo fw-400 mb-3">{{ $modalSubTitle }}</h3>
                @endif
                <div class="img-container">
                    <img :id="'{{ $modalId }}' + index + '-img'" src="">
                </div>
            </div>

            <!-- Modal Actions -->
            <div class="modal-footer mb-4">
                <button type="button" class="btn btn-default rounded-pill"
                    @click.prevent="cropImage('{{ $imageName }}', index)">
                    <i class="fa fa-btn"></i> {{ __('OK') }}
                </button>
                <button type="button"
                    class="btn rounded-pill btn--outline"
                    @click="destroyCropper('{{ $imageName }}', index); resetFileInput('{{ $imageName }}', index);">{{ __('Cancel') }}</button>
            </div>
        </div>
    </div>
</div>
