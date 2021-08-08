Vue.component('pagination', require('laravel-vue-pagination'));
import { TableComponent, TableColumn } from 'vue-table-component';

import DateRangePicker from 'vue2-daterange-picker';
import 'vue2-daterange-picker/dist/vue2-daterange-picker.css';

Vue.component('donation-stats', {
    components: {
        TableComponent,
        TableColumn,
        DateRangePicker
    },

    data: function() {
        return  {
            stats: {},
            status: '',
            startDate: '',
            endDate: '',
            resetPage: false,
            dataset: [],
            labels: {
                xLabels: [],
                yLabels: 5,
            },
            chartStartDate: '',
            chartEndDate: '',
            grid: {
                verticalLines: true,
                verticalLinesNumber: '',
                horizontalLines: true,
                horizontalLinesNumber: 1
            }
        }
    },

    mounted() {
        this.getStats();
        this.getChartData();
    },

    methods: {

        getStats() {
            let queryParams = {params: {'start_date': this.startDate, 'end_date': this.endDate}};
            axios.get(`${RJ.apiBaseUrl}/admin/reports/donation-stats`, queryParams)
                .then(response => {
                    this.stats = response.data;
                });
        },

        getChartData() {
            axios.get(`${RJ.apiBaseUrl}/admin/reports/monthly-donations`)
                .then(res => {
                let counter = 1;
                const data = res.data.bpi;
                for (let key in data) {
                    this.dataset.push(data[key]);
                    this.labels.xLabels.push(key);
                    counter++;
                }
                this.grid.verticalLinesNumber = counter;

                let lastKey = Object.keys(res.data.bpi).length - 1;
                this.chartStartDate = Object.keys(res.data.bpi)[0];
                this.chartEndDate = Object.keys(res.data.bpi)[lastKey];
            });
        },

    }

});
