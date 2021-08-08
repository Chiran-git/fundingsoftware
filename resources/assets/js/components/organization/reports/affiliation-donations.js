Vue.component('pagination', require('laravel-vue-pagination'));
import { TableComponent, TableColumn } from 'vue-table-component';

Vue.component('affiliation-donations', {
    props: ['organization'],
    components: {
        TableComponent,
        TableColumn
    },

    data: function() {
        return  {
            affiliations: {}
        }
    },

    mounted() {
        this.getAffiliationDonationsReport();
    },

    methods: {

        getAffiliationDonationsReport() {
            if (typeof this.organization !== 'undefined') {
                // For individual organization
                axios.get(`${RJ.apiBaseUrl}/organization/${this.organization.id}/affiliation-donations`)
                .then(response => {
                    this.affiliations = response.data;
                });
            } else {
                // for Superadmin
                axios.get(`${RJ.apiBaseUrl}/admin/reports/affiliation-donations`)
                .then(response => {
                    this.affiliations = response.data;
                });
            }
        },

    }

});
