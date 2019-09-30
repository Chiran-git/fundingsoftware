@component('mail::organization-message')

	<table class="inner-body" align="center" width="700" cellpadding="0" cellspacing="0" role="presentation">
		<!-- Body content -->
		<tr>
			<td class="bg-light-gray p-t-b-20 p-r-l-25">
				<table class="bg-white border-rounded-6 p-t-b-30 p-r-l-60" width="660">
					<tr>
						<td class="p-t-b-20">
							<h2 class="color-black m-b-0 assistant f-24">{{ __('Hello') }} {{ $user->name }}.</h2>
							<p class="color-black">{{ __('You have been assigned as admin for organization') }} {{ $organization->name}}.</p>
							<p class="color-black m-b-0">{{ __('Please set your password.') }}</p>
						</td>
					</tr>

					<tr>
						<td>
							@component('mail::button',
								[
									'url' => route('password.reset', ['token' => $token,])
								]
							)
							{{ __('Create Password') }}
						</td>
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td>
				<span class="d-block m-t-20">{{ __('Thanks,') }}</span>
				{{ config('app.name') }}
			</td>
		</tr>
	</table>

@endcomponent
