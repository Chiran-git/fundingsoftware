@extends('layouts.app')

@section('title', "RocketJar")

@section('content')
    <section class="section">
        <div class="row mb-5">
            <div class="col-12 col-md-12 col-lg-4">
                <h2 class="aleo">{{ __('Pay-Out History')}}</h2>
            </div>
            <div class="col-12 ml-lg-auto col-md-12 col-lg-6">
                <div class="d-md-flex">
                    <div class="form_wrapper form--filters flex-grow-1">
                        <h6 class="text-uppercase float-left mt-2 pt-1 mr-2">{{ __('Filter by') }}</h6>
                        <form class="mt-2 mt-md-0">
                            <ul class='form_fields'>
                                <li class='field size1 align-top pb-0 mr-3 mr-lg-0'>
                                    <div class='input_container_select'>
                                        <select>
                                            <option selected disabled value="">{{ __("All Campaigns")}}</option>
                                            <option>{{ __('text') }}</option>
                                        </select>
                                    </div>
                                </li>
                                <li class='field size1 align-top pb-0'>
                                    <div class='input_container_select'>
                                        <select>
                                            <option selected disabled value="">{{ __("All Accounts")}}</option>
                                            <option>{{ __('text') }}</option>
                                        </select>
                                    </div>
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>
            </div>
        </div>
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-striped--def">
                            <thead>
                                <tr>
                                <th scope="col"><a href="#">{{ __("Issue Date") }}</a></th>
                                <th scope="col"><a href="#">{{ __('Time Period') }}</a></th>
                                <th scope="col"><a href="#">{{ __('Campaign') }}</a></th>
                                <th scope="col"><a href="#">{{ __('Account') }}</a></th>
                                <th scope="col"><a href="#">{{ __('Total Donations') }}</a></th>
                                <th scope="col"><a href="#">{{ __('Net Deposit') }}</a></th>
                                <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">Roy</th>
                                    <td>Mark@way.in</td>
                                    <td>$125.35</td>
                                    <td>5</td>
                                    <td>Mark@way.in</td>
                                    <td>$125.35</td>
                                    <td><a href="#"><i class="fas fa-file-download"></i></a></td>
                                </tr>
                                <tr>
                                    <th scope="row">Emma</th>
                                    <td>Jacob@way.in</td>
                                    <td>$125.35</td>
                                    <td>5</td>
                                    <td>Mark@way.in</td>
                                    <td>$125.35</td>
                                    <td><a href="#"><i class="fas fa-file-download"></i></a></td>
                                </tr>
                                <tr>
                                    <th scope="row">Edi</th>
                                    <td>Larry@way.in</td>
                                    <td>$125.35</td>
                                    <td>5</td>
                                    <td>Mark@way.in</td>
                                    <td>$125.35</td>
                                    <td><a href="#"><i class="fas fa-file-download"></i></a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </section>
@endsection
