<div class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-cm-lg">
        <div class="modal-content grayscale rounded-0 py-5 px-3">
            <h2>{{ __("Are you sure you want to deactivate this campaign?") }}</h2>
            <p>No one will be able to edit, view or donate to this campaign once it's deactivated. You can re-activated at any time.</p>
            <div class='form_footer d-flex flex-column flex-md-row justify-content-between align-items-start'>
                    {{-- @include('partials.common.button-with-loading', [
                        'title' => __('Add User to Campaign'),
                        'busyCondition' => '',
                        'disabledCondition' => ''
                    ]) --}}
                </div>
        </div>
    </div>
</div>
