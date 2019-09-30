@extends('layouts.app')

@section('title')
{{ __('Privacy') }}
@endsection

@section('title', "RocketJar")

@section('content')
<div class="row">
    <div class="col-12">
        <section class="section pages">
            <h2 class="aleo">{{ __('Privacy Policy') }}</h2>
            <p class="f-16">{{ __('This privacy policy describes the manner in which SuperFanU, Inc. ("SuperFanU") and RocketJar.com uses and protects the information you give when you use this mobile application. SuperFanU is committed to protecting your privacy. If we ask you to provide information by which you can be identified, we assure you that it will be used in accordance with this privacy statement.') }}</p>
            <p class="f-16">{{ __('SuperFanU may change this policy by updating this page, so we encourage you to check this page periodically. This policy was updated on January 1, 2013.') }}</p>
            <ul class="f-16 list-unstyled">
                <li><h3>{{ __('Information we collect.') }}</h3>
                    <p>{{ __('We may collect the following information:')}}</p>
                    <ol class="pl-3">
                        <li>{{ __('Information submitted by the user during signup or check-out including the user’s name, address, student ID number, phone number, birthday, year in school and other information.')}}</li>
                        <li>{{ __('Information submitted by the user through the website’s contact form.')}}</li>
                        <li>{{ __('Information and data collected from your use of the website including your purchases made, clicks made, awards won and other information that is derived from the use of the website.')}}</li>
                    </ol>
                </li>
                <li class="mt-3">
                    <h3>{{ __('What we do with the information:') }}</h3>
                    <ol class="pl-3">
                        <li>{{ __('We use it to understand your needs and provide better service including referencing specific programs for your needs.')}}</li>
                        <li>{{ __('We use it for fraud checks and other authorization during a sale.')}}</li>
                        <li>{{ __('We use it for internal record keeping and product/service improvement.')}}</li>
                    	<li>{{ __('We use it to improve our products and services.')}}</li>
                        <li>{{ __('We use it to test the products and to show anonymous data for case studies.  We may aggregate user data for purposes of developing content and ensure relevant advertising content, but such data will not personally identify individual users.')}}</li>
                        <li>{{ __('We may periodically use it to send promotional materials to you.')}}</li>
                        <li>{{ __('We use it to contact you and communicate about our services as this is the express purpose of tools like the site contact form.')}}</li>
                    </ol>
                </li>
                <li class="mt-3">
                    <h3>{{ __('Security') }}</h3>
                    <p>{{ __('Any information you provide using this mobile application is managed in a secure way using password-protected technology and appropriate organizational policies.') }}</p>
                </li>
                <li>
                    <h3>{{ __('Site Analysis Technology') }}</h3>
                    <p>{{ __('Information such as the user’s IP address, the referring domain, geographic location, time/duration of visit, entry/exit page, and other like statistics is automatically gathered using web analytics tools in a way that is now standard practice. This information is only used to improve the quality and effectiveness of our mobile application.') }}</p>
                </li>
                <li>
                    <h3>{{ __('Outbound Links') }}</h3>
                    <p>{{ __('Periodically, our application may contain outbound links to other websites and applications. When following an outbound link, you must recognize that we have no control over the site or application to which you are navigating. We provide outbound links because we think the information contained therein may be of interest or usefulness to our application visitors, but we do not take responsibility for any site or application to which we link. Links are a useful part of the experience, but we acknowledge the inherent risks of navigating from application to application.') }}</p>
                </li>
                <li>
                    <h3>{{ __('Controlling your Information') }}</h3>
                    <p>{{ __('You may restrict the collection of your information by:') }}</p>
                    <ul class="pl-2 mb-3 list-unstyled">
                        <li>{{ __('(i) not registering as a user of this website;') }}</li>
                        <li>{{ __('(ii) not funding any organization or project; and') }}</li>
                        <li>{{ __('(iii) not using our contact form(s)') }}</li>
                    </ul>
                </li>
                <li>
                    <h3>{{ __('What we do not do with the information:') }}</h3>
                    <p>{{ __('We will not sell, rent or distribute your personal information to third parties other than the organization in which you funded without your express consent.') }}</p>
                </li>
            </ul>
        </section>
    </div><!-- /.col-6 -->
</div><!-- /.row -->
@endsection
