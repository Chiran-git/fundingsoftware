import pagination from 'laravel-vue-pagination';

Vue.component('admin-dashboard', {
    props: [
        'organization',
    ],

    data: function() {
        return  {
            stats: {},
            organizations: {},
            activeCampaigns: {},
            completedCampaigns: {}
        }
    },

    mounted() {
        this.getStats();
        this.getOrganizations();
        this.getCampaigns();
    },

    methods: {
        /**
         * Method to fix the table header using jquery
         * We basically copy the cell text of header row to every other respective columns
         * which are then shown only in mobile resolution
         */
        fixTableHeader() {
            $('.list-fixed-head-js > li:first-child li').each(function() {
                var $this = $(this);
                var txt   = $this.text();
                var indx  = $this.index();
                $('.list-fixed-head-js li:nth-child(' + (indx + 1) + ')').attr('data-text', txt);
            });
        },

        /**
         * Its a copy of above code as it was overriding previous code
         */
        stickyHeader() {
            $('.list-fixed-head > li:first-child li').each(function() {
                var $this = $(this);
                var txt   = $this.text();
                var indx  = $this.index();
                $('.list-fixed-head li:nth-child(' + (indx + 1) + ')').attr('data-text', txt);
            });
        },

        getStats() {
            axios.get(`${RJ.apiBaseUrl}/admin/stats`)
                .then(response => {
                    this.stats = response.data;
                });
        },

        getOrganizations() {
            axios.get(`${RJ.apiBaseUrl}/admin/organizations?limit=4`)
                .then(response => {
                    this.organizations = response.data.data;
                    this.$nextTick(() => {
                        this.fixTableHeader();
                    });
                });
        },

        getGreeting () {
            return `${this.$root.greetingBaseOnTime(moment())}, ${this.$root.user.first_name}`;
        },

        getCampaigns() {
            axios.get(`${RJ.apiBaseUrl}/admin/campaigns?limit=4&status=active`)
                .then(response => {
                    this.activeCampaigns = response.data.data;
                    // Apply the StackedTable
                    this.$nextTick(() => {
                        this.stickyHeader();
                    });
                });

            axios.get(`${RJ.apiBaseUrl}/admin/campaigns?limit=4&status=completed`)
                .then(response => {
                    this.completedCampaigns = response.data.data;
                    // Apply the StackedTable
                    this.$nextTick(() => {
                        this.stickyHeader();
                    });
                });
        },

        clickRow(orgId) {
            window.location.href = `${RJ.baseUrl}/admin/impersonate/${orgId}`;
        },

        clickCampaignRow(campId) {
            window.location.href = `${RJ.baseUrl}/admin/campaign/${campId}/details`;
        },

    }

});
