//import pagination from 'laravel-vue-pagination';
import { TableComponent, TableColumn } from 'vue-table-component';
import DateRangePicker from 'vue2-daterange-picker';
import 'vue2-daterange-picker/dist/vue2-daterange-picker.css';


Vue.component('donation-list', {

    props: ['organization'],

    components: {
        //pagination,
        TableComponent,
        TableColumn,
        DateRangePicker
    },

    data: function() {
        return  {
            donations: {},
            startDate: '',
            dateRange: '',
            endDate: '',
            resetPage: false,
            campaign: this.$route.query.campaign || '',
            campaignList: {},
        }
    },

    mounted() {
        this.getCampaigns();
    },

    methods: {
        async setDonationList ({ page, filter, sort }) {
            if (typeof page === 'undefined' || this.resetPage) {
                page = 1;
                this.resetPage = false;
            }

            var queryParams = {params: {'page': page, 'sort': sort, 'campaign': this.campaign, 'start_date': this.startDate, 'end_date': this.endDate}};

            // Load the donors
            return this.$root.getOrganizationDonations(this.organization.id, queryParams)
                .then(response => {
                    response.data.pagination = {'currentPage': response.data.current_page, 'totalPages': response.data.last_page};
                    return response.data;
                });

        },

        getCampaignOptionLabel (campaign) {
            return this.campaignList[campaign];
        },

        getCampaigns () {
            this.$root.getOrganizationCampaigns(this.organization.id)
                .then(response => {
                    this.campaignList = response.data;
                    this.campaignList[0] = this.$root.rj.translations.all_campaigns;
                });
        },

        dateRangeUpdated (range) {
            this.startDate = this.$root.convertBrowserToUTC(range.startDate);
            this.endDate = this.$root.convertBrowserToUTC(range.endDate);
            this.resetPage = true;
            this.$refs.donationList.refresh();
        }
    },

    watch: {
        campaign (value) {
            this.resetPage = true;
            this.$refs.donationList.refresh();
        }

    },

    computed: {
        campaignOptions () {
            let options = [];

            _.each(this.campaignList, (label, campaign) => {
                options.push(campaign);
            });

            return options;
        }

    }

});
