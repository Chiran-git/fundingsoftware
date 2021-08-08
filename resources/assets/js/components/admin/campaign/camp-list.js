Vue.component('pagination', require('laravel-vue-pagination'));

import { TableComponent, TableColumn } from 'vue-table-component';

Vue.component('table-component', TableComponent);
Vue.component('table-column', TableColumn);

Vue.component('camp-list', {

    data: function() {
        return  {
            campaigns: {},
            status: ''
        }
    },

    mounted() {
        this.getCampaigns();
    },

    methods: {
        async getCampaigns({ page, filter, sort }) {
            if (typeof page === 'undefined' || this.resetPage) {
                page = 1;
                this.resetPage = false;
            }
            this.status = this.$route.query.status;
            var queryParams = {params: {'page': page, 'sort': sort, 'status': this.status, 'start_date': this.startDate, 'end_date': this.endDate}};

            // Load the campaigns
            return axios.get(`${RJ.apiBaseUrl}/admin/campaigns`, queryParams)
                .then(response => {
                    response.data.pagination = {'currentPage': response.data.current_page, 'totalPages': response.data.last_page};
                    return response.data;
                });
        },

        clickCampaignRow(event) {
            window.location.href = `${RJ.baseUrl}/admin/campaign/${event.data.id}/details`;
        },
    }
});
