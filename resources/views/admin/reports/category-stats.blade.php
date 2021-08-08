@extends('layouts.app')

@section('title', "RocketJar")

@section('content')
<category-stats inline-template="">
    <section class="section--def">
        <div class="row mb-1">
            <div class="col-4 col-md-4">
                <div class="px-x-2 mb-4">
                    <h2 class="mb-1" v-if="$root.user">
                        {{ __('Category Report') }}
                    </h2>
                </div>
            </div>
        </div>

        <div>
            <div class="row">
                <div class="col-12">
                    <table-component :data="getCampaignCategories" :show-filter=false :show-caption=false
                        :cache-lifetime=0 ref="categoriesList" sort-order="desc" sort-by="created_at"
                        table-class="table table-gray table-gray-alt">
                        <table-column show="name" label="Name">
                            <template slot-scope="row">
                                @{{ row.name }}
                            </template>
                        </table-column>
                        <table-column show="total_campaigns" label="Total Campaigns">
                            <template slot-scope="row">
                                @{{ row.total_campaigns }}
                            </template>
                        </table-column>
                        <table-column show="active_campaigns" label="Active Campaigns">
                            <template slot-scope="row">
                                @{{ row.active_campaigns }}
                            </template>
                        </table-column>
                        <table-column show="completed_campaigns" label="Completed Campaigns">
                            <template slot-scope="row">
                                @{{ row.completed_campaigns }}
                            </template>
                        </table-column>
                    </table-component>
                </div>
            </div>
        </div>
    </section>

</category-stats>
@endsection
