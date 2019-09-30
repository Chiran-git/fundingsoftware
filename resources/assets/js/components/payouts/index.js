//import pagination from 'laravel-vue-pagination';
import { TableComponent, TableColumn } from 'vue-table-component';

Vue.component('payout-list', {

    props: ['organization'],

    components: {
        //pagination,
        TableComponent,
        TableColumn
    },

    data: function() {
        return  {
            resetPage: false,
            campaign: this.$route.query.campaign || '',
            account: '',
            campaignList: this.getCampaigns(),
            accountList: this.getConnectedAccounts()
        }
    },

    mounted() {

    },

    methods: {
        async setPayoutsList ({ page, filter, sort }) {
            if (typeof page === 'undefined' || this.resetPage) {
                page = 1;
                this.resetPage = false;
            }

            var queryParams = {params: {'page': page, 'sort': sort, 'campaign': this.campaign, 'account': this.account}};

            // Load the donors
            return this.$root.getOrganizationPayouts(this.organization.id, queryParams)
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

        getConnectedAccounts () {
            this.$root.getOrganizationAccounts(this.organization.id)
                .then(response => {
                    this.accountList = response.data;
                    this.accountList[0] = this.$root.rj.translations.all_accounts;
                });
        },

        getAccountOptionLabel (account) {
            return this.accountList[account];
        },

    },

    watch: {
        campaign (value) {
            this.resetPage = true;
            this.$refs.payoutsList.refresh();
        },
        account (value) {
            this.resetPage = true;
            this.$refs.payoutsList.refresh();
        }

    },

    computed: {
        campaignOptions () {
            let options = [];

            _.each(this.campaignList, (label, campaign) => {
                options.push(campaign);
            });

            return options;
        },

        accountOptions () {
            let options = [];

            _.each(this.accountList, (label, account) => {
                options.push(account);
            });

            return options;
        }
    }

});
