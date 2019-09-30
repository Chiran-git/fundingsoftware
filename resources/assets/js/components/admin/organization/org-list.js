Vue.component('pagination', require('laravel-vue-pagination'));

import { TableComponent, TableColumn } from 'vue-table-component';

Vue.component('table-component', TableComponent);
Vue.component('table-column', TableColumn);

Vue.component('org-list', {

    data: function() {
        return  {
            organizations: {},
            status: '',
            startDate: '',
            endDate: '',
            resetPage: false,
        }
    },

    mounted() {
        this.getOrganizations();
    },

    methods: {
        async getOrganizations({ page, filter, sort }) {
            /*if (typeof page === 'undefined') {
                page = 1;
            }
            axios.get(`${RJ.apiBaseUrl}/admin/organizations?limit=10&&page=${page}`)
                .then(response => {
                    this.organizations = response.data;
                });*/

            if (typeof page === 'undefined' || this.resetPage) {
                page = 1;
                this.resetPage = false;
            }

            var queryParams = {params: {'page': page, 'sort': sort, 'start_date': this.startDate, 'end_date': this.endDate}};

            // Load the organizations
            return axios.get(`${RJ.apiBaseUrl}/admin/organizations`, queryParams)
                .then(response => {
                    response.data.pagination = {'currentPage': response.data.current_page, 'totalPages': response.data.last_page};
                    return response.data;
                });
        },

        clickRow(event) {
            window.location.href = `${RJ.baseUrl}/admin/impersonate/${event.data.id}`;
        },
    }
});
