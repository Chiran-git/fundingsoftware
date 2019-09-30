<button type='submit' class='btn btn--dark rounded-pill btn--size4 {{ isset($buttonClass) ? $buttonClass : '' }}'
    :disabled="{{ isset($disabledCondition) ? $disabledCondition : $busyCondition }}" {{ isset($attributes) ? $attributes : '' }}
    @click.prevent="{{ isset($submitMethod) ? $submitMethod : 'submit' }}">
    <span v-if="! {{ $busyCondition }}">{!! $title !!}</span>
    <span v-else class="ani-loader">
        @include('partials.common.loading')
    </span>
</button>
