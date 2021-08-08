Vue.component('pagination', require('laravel-vue-pagination'));
import { TableComponent, TableColumn } from 'vue-table-component';

Vue.component('category-stats', {
    components: {
        TableComponent,
        TableColumn,
    },

    data: function() {
        return  {
            resetPage: false,
        }
    },

    mounted() {

    },

    methods: {

        async getCampaignCategories({ page, filter, sort }) {

            if (typeof page === 'undefined' || this.resetPage) {
                page = 1;
                this.resetPage = false;
            }

            let queryParams = {params: {'page': page, 'sort': sort}};

            // Load the donations
            return axios.get(`${RJ.apiBaseUrl}/admin/reports/categories`, queryParams)
                .then(response => {
                    response.data.pagination = {'currentPage': response.data.current_page, 'totalPages': response.data.last_page};
                    return response.data;
                });
        },

    }
});
