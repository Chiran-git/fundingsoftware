@extends('layouts.app')

@section('title')
{{ __('Terms of Use') }}
@endsection

@section('title', "RocketJar")

@section('content')
<div class="row">
    <div class="col-12">
        <section class="section pages">
            <h2 class="aleo">{{ __('Terms and Conditions of Use') }}</h2>
            <p class="f-16">{{ __('SuperFanU, Inc. and RocketJar.com (“Us” or “We”) provides the RocketJar.com website application and mobile sites and various related services (collectively, the “site”) to you, the user, subject to your compliance with all the terms, conditions, and notices contained or referenced herein (the “Terms of Use”), as well as any other written agreement between us and you. In addition, when using particular services or materials on this site, users shall be subject to any posted rules applicable to such services or materials that may contain terms and conditions in addition to those in these Terms of Use. All such guidelines or rules are hereby incorporated by reference into these Terms of Use.') }}</p>
            <p class="f-16">{{ __('BY USING THIS SITE, YOU AGREE TO BE BOUND BY THESE TERMS OF USE. IF YOU DO NOT WISH TO BE BOUND BY THESE TERMS OF USE, PLEASE EXIT THE SITE NOW. YOUR REMEDY FOR DISSATISFACTION WITH THIS SITE, OR ANY PRODUCTS, SERVICES, CONTENT, OR OTHER INFORMATION AVAILABLE ON OR THROUGH THIS SITE, IS TO STOP USING THE SITE AND/OR THOSE PARTICULAR PRODUCTS OR SERVICES. YOUR AGREEMENT WITH US REGARDING COMPLIANCE WITH THESE TERMS OF USE BECOMES EFFECTIVE IMMEDIATELY UPON COMMENCEMENT OF YOUR USE OF THIS SITE.') }}</p>
            <p class="f-16">{{ __('These Terms of Use are effective as of January 1, 2013. We expressly reserve the right to change these Terms of Use from time to time without notice to you. You acknowledge and agree that it is your responsibility to review this site and these Terms of Use from time to time and to familiarize yourself with any modifications. Your continued use of this site after such modifications will constitute acknowledgment of the modified Terms of Use and agreement to abide and be bound by the modified Terms of Use.') }}</p>
            <p class="f-16">{{ __('As used in these Terms of Use, references to our “Affiliates” includes our owners, subsidiaries, affiliated companies, officers, directors, suppliers, partners, sponsors, and advertisers, and includes (without limitation) all parties involved in creating, producing, and/or delivering this site and/or its contents.') }}</p>
            <ul class="f-16 list-unstyled">
                <li><h3>{{ __('1. Description of Services.') }}</h3>
                    <p>{{ __('We make various services available on this site including, but not limited to, funding opportunities for collegiate, high school, and non-profit organizations.') }}</p>
                    <p>{{ __('We reserve the sole right to either modify or discontinue the site, including any of the site’s features, at any time with or without notice to you. We will not be liable to you or any third party should we exercise such right. Any new features that augment or enhance the then-current services on this site shall also be subject to these Terms of Use.') }}</p>
                    <p>{{ __('RocketJar.com uses Stripe, a division of PayPal, Inc. (Stripe) for payment processing services. By using the Stripe payment processing services you agree to the Stripe Payment Services Agreement available at') }} <a href="https://stripe.com/legal" class="text-link">{{ __('https://stripe.com/legal') }}</a>{{ __(', and the applicable bank agreement available at ') }}<a href="https://stripe.com/connect-account/legal" class="text-link">{{ __('https://stripe.com/connect-account/legal') }}</a></p>
                    <p>{{ __('SuperFanU, Inc and RocketJar.com do not provide refunds.') }}</p>
                </li>
                <li class="mt-3">
                    <h3>{{ __('2. Registration Data.') }}</h3>
                    <p>{{ __('In order to access some of the services on this site, you will be required to use an account and password that can be obtained by completing our online registration form, which requests certain information and data (“Registration Data”), and maintaining and updating your Registration Data as required. By registering, you agree that all information provided in the Registration Data is true and accurate and that you will maintain and update this information as required in order to keep it current, complete, and accurate.') }}</p>
                </li>
                <li class="mt-3">
                    <h3>{{ __('3. Third Party Sites and Information.') }}</h3>
                    <p>{{ __('This site may link you to other sites on the Internet or otherwise include references to information, documents, software, materials and/or services provided by other parties. These sites may contain information or material that some people may find inappropriate or offensive. These other sites and parties are not under our control, and you acknowledge that we are not responsible for the accuracy, copyright compliance, legality, decency, or any other aspect of the content of such sites, nor are we responsible for errors or omissions in any references to other parties or their products and services. The inclusion of such a link or reference is provided merely as a convenience and does not imply endorsement of, or association with, the site or party by us, or any warranty of any kind, either express or implied.') }}</p>
                </li>
                <li>
                    <h3>{{ __('4. Intellectual Property Information.') }}</h3>
                    <p>{{ __('For purposes of these Terms of Use, “content” is defined as any information, data, communications, software, photos, video, graphics, music, sounds, and other materials and services that can be viewed by users on our site.') }}</p>
                    <p>{{ __('By accepting these Terms of Use, you acknowledge and agree that all content presented to you on this site is protected by copyrights, trademarks, service marks, patents or other proprietary rights and laws, and is the sole property of SuperFanU, Inc. and/or its Affiliates. You are only permitted to use the content as expressly authorized by us or the specific content provider. You may not copy, reproduce, modify, republish, upload, post, transmit, or distribute any documents or information from this site in any form or by any means without prior written permission from us or the specific content provider, and you are solely responsible for obtaining permission before reusing any copyrighted material that is available on this site. Any unauthorized use of the materials appearing on this site may violate copyright, trademark and other applicable laws and could result in criminal or civil penalties.') }}</p>
                    <p>{{ __('Neither we or our Affiliates warrant or represent that your use of materials displayed on, or obtained through, this site will not infringe the rights of third parties. See “User’s Materials” below for a description of the procedures to be followed in the event that any party believes that content posted on this site infringes on any patent, trademark, trade secret, copyright, right of publicity, or other proprietary rights of any party.') }}</p>
                    <p>{{ __('The following are registered trademarks, trademarks or service marks of SuperFanU, Inc. or its Affiliates. All custom graphics, icons, logos and service names are registered trademarks, trademarks or service marks of SuperFanU, Inc. or its Affiliates. All other trademarks or service marks are property of their respective owners. Nothing in these Terms of Use grants you any right to use any trademark, service mark, logo, and/or the name of SuperFanU, Inc. or its Affiliates.') }}</p>
                </li>
                <li>
                    <h3>{{ __('5. Disclaimer of Warranties.') }}</h3>
                    <p>{{ __('ALL MATERIALS AND SERVICES ON THIS SITE ARE PROVIDED ON AN “AS IS” AND “AS AVAILABLE” BASIS WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESS OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY OR FITNESS FOR A PARTICULAR PURPOSE, OR THE WARRANTY OF NON-INFRINGEMENT. WITHOUT LIMITING THE FOREGOING, WE MAKE NO GUARANTEE THAT THE PROGRAMS WILL BE SUCCESSFUL AND MAKE NO WARRANTY THAT (A) THE SERVICES AND MATERIALS WILL MEET YOUR REQUIREMENTS, (B) THE SERVICES AND MATERIALS WILL BE UNINTERRUPTED, TIMELY, SECURE, OR ERROR-FREE, (C) THE RESULTS THAT MAY BE OBTAINED FROM THE USE OF THE SERVICES OR MATERIALS WILL BE EFFECTIVE, OR (D) THE QUALITY OF ANY PRODUCTS, SERVICES, OR INFORMATION PURCHASED OR OBTAINED BY YOU FROM THE SITE FROM US OR OUR AFFILIATES WILL MEET YOUR EXPECTATIONS OR BE FREE FROM MISTAKES, ERRORS OR DEFECTS.') }}</p>
                    <p>{{ __('THE USE OF THE SERVICES OR THE DOWNLOADING OR OTHER ACQUISITION OF ANY MATERIALS THROUGH THIS SITE IS DONE AT YOUR OWN DISCRETION AND RISK AND WITH YOUR AGREEMENT THAT YOU WILL BE SOLELY RESPONSIBLE FOR ANY DAMAGE TO YOUR COMPUTER SYSTEM OR LOSS OF DATA THAT RESULTS FROM SUCH ACTIVITIES.') }}</p>
                </li>
                <li>
                    <h3>{{ __('6. Limitation of Liability.') }}</h3>
                    <p>{{ __('IN NO EVENT SHALL WE OR OUR AFFILIATES BE LIABLE TO YOU OR ANY THIRD PARTY FOR ANY SPECIAL, PUNITIVE, INCIDENTAL, INDIRECT OR CONSEQUENTIAL DAMAGES OF ANY KIND, OR ANY DAMAGES WHATSOEVER, INCLUDING, WITHOUT LIMITATION, ON ANY THEORY OF LIABILITY, ARISING OUT OF OR IN CONNECTION WITH THE USE OF THIS SITE OR OF ANY WEB SITE REFERENCED OR LINKED TO FROM THIS SITE.') }}</p>
                </li>
                <li>
                    <h3>{{ __('7. Indemnification.') }}</h3>
                    <p>{{ __('Upon a request by us, you agree to defend, indemnify, and hold us and our Affiliates harmless from all liabilities, claims, and expenses, including attorney’s fees, that arise from your use or misuse of this site. We reserve the right, at our own expense, to assume the exclusive defense and control of any matter otherwise subject to indemnification by you, in which event you will cooperate with us in asserting any available defenses.') }}</p>
                </li>
                <li>
                    <h3>{{ __('8. Security and Password.') }}</h3>
                    <p>{{ __('You are solely responsible for maintaining the confidentiality of your password and account and for any and all statements made and acts or omissions that occur through the use of your password and account. Therefore, you must take steps to ensure that others do not gain access to your password and account. Our personnel will never ask you for your password. You may not transfer or share your account with anyone, and we reserve the right to immediately terminate your account if you do transfer or share your account.') }}</p>
                </li>
                <li>
                    <h3>{{ __('9. Awards.') }}</h3>
                    <p>{{ __('You acknowledge that awards or prizes from the site/application are subject to availability of such prizes and the eligibility requirements.  You acknowledge that by “unlocking” an award alone does not create a prize as certain awards may be achievement levels.  For any issue or questions regarding a prize, award, point totals or like items from the site/application, you acknowledge that the fan program provider will be contacted and not SuperFanU, Inc. or RocketJar.com to resolve such issue or question.') }}<p>
                        <ol class="pl-3">
                            <li>{{ __('Apple is not involved in any way with SuperFanU contests or sweepstakes.') }}</li>
                            <li>{{ __('Apple is not a sponsor, nor is it involved in any way with RocketJar.com contests or sweepstakes.') }}</li>
                            <li>{{ __('RocketJar.com does not offer Apple products as contest or sweepstake prizes.') }}</li>
                        </ol>
                </li>
                <li>
                    <h3>{{ __('10. Termination of Use.') }}</h3>
                    <p>{{ __('You agree that we may, in our sole discretion, terminate or suspend your access to all or part of the site with or without notice and for any reason, including, without limitation, breach of these Terms of Use. Any suspected fraudulent, abusive or illegal activity may be grounds for terminating your relationship and may be referred to appropriate law enforcement authorities.') }}</p>
                    <p>{{ __('Upon termination or suspension, regardless of the reasons therefore, your right to use the services available on this site immediately ceases, and you acknowledge and agree that we may immediately deactivate or delete your account and all related information and files in your account and/or bar any further access to such files or this site. We shall not be liable to you or any third party for any claims or damages arising out of any termination or suspension or any other actions taken by us in connection with such termination or suspension.') }}</p>
                </li>
                <li>
                    <h3>{{ __('11. Governing Law.') }}</h3>
                    <p>{{ __('This site is controlled by us from our offices within the Commonwealth of Kentucky, United States of America. It can be accessed from all 50 states, as well as from other countries around the world. As each of these places has laws that may differ from those of Kentucky, by accessing this site both of us agree that the statutes and laws of the Commonwealth of Kentucky, without regard to the conflicts of laws principles thereof, will apply to all matters relating to the use of this site and the purchase of products and services available through this site. Each of us agrees and hereby submits to the exclusive personal jurisdiction and venue any court of competent jurisdiction within the Commonwealth of Kentucky with respect to such matters.') }}</p>
                </li>
                <li>
                    <h3>{{ __('12. Notices.') }}</h3>
                    <p>{{ __('All notices to a party shall be in writing and shall be made either via email or conventional mail. Notices to us must be sent to the attention of Customer Service at' )}} <a class="text-link" href="mailto:info@rocketjar.com">info@rocketjar.com</a>{{ __(', if by email, or at SuperFanU, Inc., 732 E Market St Ste 300, Louisville, Kentucky 40202 if by conventional mail. Notices to you may be sent to the address supplied by you as part of your Registration Data. In addition, we may broadcast notices or messages through the site to inform you of changes to the site or other matters of importance, and such broadcasts shall constitute notice to you at the time of sending.') }}</p>
                </li>
                <li>
                    <h3>{{ __('13. Entire Agreement.') }}</h3>
                    <p>{{ __('These Terms of Use constitute the entire agreement and understanding between us concerning the subject matter of this agreement and supersedes all prior agreements and understandings of the parties with respect to that subject matter. These Terms of Use may not be altered, supplemented, or amended by the use of any other document(s). Any attempt to alter, supplement or amend this document or to enter an order for products or services which are subject to additional or altered terms and conditions shall be null and void, unless otherwise agreed to in a written agreement signed by you and us. To the extent that anything in or associated with this site is in conflict or inconsistent with these Terms of Use, these Terms of Use shall take precedence.') }}</p>
                </li>
                <li></li>
            </ul>
        </section>
    </div><!-- /.col-6 -->
</div><!-- /.row -->
@endsection
