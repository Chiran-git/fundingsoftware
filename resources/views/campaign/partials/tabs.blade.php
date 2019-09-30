<div class="wizard-inline wizard-inline--secondary">
    <ul>
        <li>
            <a :class="{'iscompleted': currentStep != 1, 'isactive': currentStep == 1}"
                @click="changeStep(1)">{{ __('Campaign info')}}
            </a>
        </li>
        <li>
            <a :class="{'iscompleted': currentStep != 2, 'isactive': currentStep == 2}"
                    @click="changeStep(2)">{{ __('Rewards')}}
            </a>
        </li>
        <li>
            <a :class="{'iscompleted': currentStep != 3, 'isactive': currentStep == 3}"
                    @click="changeStep(3)">{{ __('Donor Message')}}
            </a>
        </li>
        <li>
            <a :class="{'iscompleted': currentStep != 4, 'isactive': currentStep == 4}"
                    @click="changeStep(4)">{{ __('Invite Users')}}
            </a>
        </li>
        <li>
            <a :class="{'iscompleted': currentStep != 5, 'isactive': currentStep == 5}"
                    @click="changeStep(5)">{{ __('Pay-outs')}}
            </a>
        </li>
        <li><a :class="{'iscompleted': currentStep != 6, 'isactive': currentStep == 6}">{{ __('Preview & Publish')}}</a></li>
    </ul>
</div>
@include('partials.common.flash-message')
