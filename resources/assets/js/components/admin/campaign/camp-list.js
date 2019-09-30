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
        getCampaigns(page) {
            if (typeof page === 'undefined') {
                page = 1;
            }
            this.status = this.$route.query.status;
            axios.get(`${RJ.apiBaseUrl}/admin/campaigns?status=${this.status}&limit=10&&page=${page}`)
                .then(response => {
                    this.campaigns = response.data;
                });
        },

        clickCampaignRow(event) {
            window.location.href = `${RJ.baseUrl}/admin/campaign/${event.data.id}/details`;
        },
    }
});
