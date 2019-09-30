@component('mail::organization-message')
<!-- Email Body -->
        <table class="inner-body" align="center" width="700" cellpadding="0" cellspacing="0" role="presentation">
            <!-- Body content -->
            <tr>
                <td bgcolor="#f3f3f3" style="padding: 20px 25px;">
                    <table bgcolor="#ffffff" width="660" style="padding: 30px 60px; border-radius:6px;">
                        <tr>
                            <td style="border-bottom:1px solid #e7e7e7; padding: 10px 0;">
                                <img src="{{ RJ::assetCdn($organization->logo) }}" width="35" height="35" alt="" style="border-radius:100%;">
                                <strong style="vertical-align: top; margin-left: 10px;line-height: 35px; color:#000;">
                                    {{$organization->name}}
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{ Illuminate\Mail\Markdown::parse($content) }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="bg-light-gray text-center p-t-20 p-b-50">
                    <span class="f-14 color-gray">&copy; {{ date('Y') }} {!! __('RocketJar. All rights reserved.') !!}</span>
                </td>
            </tr>
        </table>    
@endcomponent
