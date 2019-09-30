@extends('layouts.app')

@section('title', "RocketJar")

@section('content')
<admin-list inline-template :user=" {{ Auth::user() ? Auth::user() : ''}} ">
    <section class="section section--primary">
        <div class="row mb-5">
            <div class="col-12 col-md-6 mr-md-auto">
                <h2 class="aleo">{{ __('Admin Users')}}</h2>
            </div>
            @if (auth()->user()->issuperAdmin())
            <div class="col-12 col-md-auto">
                <a :href="`${$root.rj.baseUrl}/admin/create`"
                    class="btn btn--outline rounded-pill btn--size6 mt-2 mt-md-0 ml-md-3 f-14">{{ __('New Admin User') }}</a>
            </div>
            @endif
        </div>
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="table-responsive">
                    <table-component
                        :data="getAdminUsers"
                        :show-filter=false
                        :show-caption=false
                        :cache-lifetime=0
                        sort-order="asc"
                        sort-by="first_name"
                        table-class="table table-striped table-striped--alternate">
                        <table-column show="first_name" label="Name">
                            <template slot-scope="row">
                                <strong class="text-red">@{{ _.capitalize(row.first_name) + ' ' + _.capitalize(row.last_name) }}</strong>
                            </template>
                        </table-column>
                        <table-column show="user_type" label="Role">
                            <template slot-scope="row">
                                <strong class="text-grey-4">@{{ _.capitalize(row.user_type) }}</strong>
                            </template>
                        </table-column>
                        <table-column show="email" label="Email" :sortable="false" :filterable="false"></table-column>
                        <table-column label="" :sortable="false" :filterable="false">
                            <template slot-scope="row">
                                @if (auth()->user()->user_type == "superadmin")
                                    <span v-if="row.id != {{auth()->user()->id}}">
                                    <a :href="`${$root.rj.baseUrl}/admin/${row.id}/edit`" class="btn btn--lightborder btn--transparent rounded-pill mr-1">Edit</a>
                                    <a href="#" @click.prevent="deleteAdmin(`${row.id}`)" class="btn btn--lightborder btn--transparent rounded-pill">Delete</a>
                                    </span>
                                @endif
                            </template>
                        </table-column>
                    </table-component>
                </div>
            </div>
        </div>
    </section>
</admin-list>
@endsection
