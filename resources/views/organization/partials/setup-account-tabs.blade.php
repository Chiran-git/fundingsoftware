<div class="wizard-inline {{ (request()->is('organization/edit')) ? 'wizard-inline--vertical mb-5' : ''  }}">
    <ul>
        <li>
            <a :class="{'iscompleted': currentStep > 1, 'isactive': currentStep == 1}"
                @click="changeTab(1)">{{ __('Organization Profile')}}
            </a>
        </li>
        <li>
            <a :class="{'iscompleted': currentStep > 2, 'isactive': currentStep == 2}"
                @click="changeTab(2)">{{ __('Organization Page design')}}
            </a>
        </li>
        <li>
            <a :class="{'iscompleted': currentStep > 3, 'isactive': currentStep == 3}"
                @click="changeTab(3)">{{ __('Donor Profiles')}}
            </a>
        </li>
        @if ((request()->is('organization/edit')))
            @php
                $currentOrgId = auth()->user()->organization->id;
                $role = auth()->user()->findAssociatedOrganization($currentOrgId)->pivot->role ;
            @endphp
            @if (in_array($role, ['owner', 'admin']) )
            <li>
                <a :class="{'iscompleted': currentStep > 4, 'isactive': currentStep == 4}"
                    @click="changeTab(4)">{{ __('Account Users')}}
                </a>
            </li>
            <li>
                <a :class="{'isactive': currentStep == 5}" @click="deactivate({{$currentOrgId}})">{{ __('Deactivate Account')}}</a>
            </li>
            @endif
        @else
            <li><a :class="{'isactive': currentStep == 4}">{{ __('Setup complete')}}</a></li>
        @endif
    </ul>
</div>
