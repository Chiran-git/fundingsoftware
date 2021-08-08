Vue.component('pagination', require('laravel-vue-pagination'));
import { TableComponent, TableColumn } from 'vue-table-component';

import DateRangePicker from 'vue2-daterange-picker';
import 'vue2-daterange-picker/dist/vue2-daterange-picker.css';

Vue.component('online-donations', {
    components: {
        TableComponent,
        TableColumn,
        DateRangePicker
    },

    data: function() {
        return  {
            stats: {},
            status: '',
            dateRange: '',
            startDate: '',
            endDate: '',
            resetPage: false,
        }
    },

    mounted() {
        this.getDonations();
        this.preselectDateRange();
    },

    methods: {

        getStats() {

            if (this.startDate == '') {
                this.startDate = moment().startOf('month').format('YYYY-MM-DD hh:mm:ss');
                this.endDate = moment().format('YYYY-MM-DD hh:mm:ss');
            }

            let queryParams = {params: {'start_date': this.startDate, 'end_date': this.endDate}};

            axios.get(`${RJ.apiBaseUrl}/admin/stats/online-donations`, queryParams)
                .then(response => {
                    this.stats = response.data;
                });
        },

        async getDonations({ page, filter, sort }) {

            this.getStats();

            if (typeof page === 'undefined' || this.resetPage) {
                page = 1;
                this.resetPage = false;
            }

            let queryParams = {params: {'page': page, 'sort': sort, 'start_date': this.startDate, 'end_date': this.endDate}};

            // Load the donations
            return axios.get(`${RJ.apiBaseUrl}/admin/reports/online-donations`, queryParams)
                .then(response => {
                    response.data.pagination = {'currentPage': response.data.current_page, 'totalPages': response.data.last_page};
                    return response.data;
                });
        },

        dateRangeUpdated (range) {
            this.startDate = this.$root.convertBrowserToUTC(range.startDate);
            this.endDate = this.$root.convertBrowserToUTC(range.endDate);
            this.resetPage = true;
            this.$refs.donationList.refresh();
        },

        preselectDateRange () {
            this.dateRange = {
                startDate: this.startDate,
                endDate: this.endDate
            }
        }

    }
});
