//import pagination from 'laravel-vue-pagination';
import { TableComponent, TableColumn } from 'vue-table-component';

Vue.component('donor-list', {

    props: ['organization'],

    components: {
        //pagination,
        TableComponent,
        TableColumn
    },

    data: function() {
        return  {
            donors: {},
            searchQuery: '',
            resetPage: false,
            campaign: ''
        }
    },

    mounted() {
        //this.setDonorList();
    },

    methods: {
        async setDonorList ({ page, filter, sort }) {

            if (typeof page === 'undefined' || this.resetPage) {
                page = 1;
                this.resetPage = false;
            }

            this.campaign = this.$route.query.campaign;

            var queryParams = {params: {'page': page, 'filter': this.searchQuery, 'sort': sort, 'campaign': this.campaign}};

            // Load the donors
            return this.$root.getDonors(this.organization.id, queryParams)
                .then(response => {
                    response.data.pagination = {'currentPage': response.data.meta.current_page, 'totalPages': response.data.meta.last_page};
                    return response.data;

                });

        },
    },

    watch: {
        searchQuery (value) {
            this.resetPage = true;
            this.$refs.donorsList.refresh();
        }

    }

});
