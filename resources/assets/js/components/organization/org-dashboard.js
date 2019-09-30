Vue.component('org-dashboard', {
    props: ['organization'],

    mixins: [
        require('../../mixins/campaign-progress')
    ],

    data: function() {
        return {
            donationStats: {},
            recentDonors: '',
            topDonors: '',
            campaigns: {},
            showCampaignList: false,
            dataset: [],
            labels: {
                xLabels: [],
                yLabels: 5,
                yLabelsTextFormatter: val => this.$root.donationMoney(val, this.organization.currency.symbol)
            },
            chartStartDate: moment().subtract(15, 'd').format('MMM D, YYYY'),
            chartEndDate: moment().format('MMM D, YYYY'),
            grid: {
                verticalLines: true,
                verticalLinesNumber: '',
                horizontalLines: true,
                horizontalLinesNumber: 1
            }
        }
    },

    mounted() {
        this.getDonationStats();
        this.getChartData();
        this.getRecentDonors();
        this.getTopDonors();
        this.setCampaignList();
    },

    methods: {

        getGreeting () {
            return `${this.$root.greetingBaseOnTime(moment())}, ${this.$root.user.first_name}`;
        },

        getDonationStats () {
            axios.get(`${RJ.apiBaseUrl}/organization/${this.organization.id}/donation-statistics`)
                .then(response => {
                    this.donationStats = response.data;
                });
        },

        getChartData() {
            let offset = moment().utcOffset();
            axios.get(`${RJ.apiBaseUrl}/organization/${this.organization.id}/chart-data?offset=${offset}`)
                .then(res => {
                let counter = 1;
                const data = res.data.bpi;
                for (let key in data) {
                    this.dataset.push(data[key]);
                    this.labels.xLabels.push(moment(key).format("MM/DD"));
                    counter++;
                }
                this.grid.verticalLinesNumber = counter;
            });
        },

        getRecentDonors () {
            axios.get(`${RJ.apiBaseUrl}/organization/${this.organization.id}/recent-donors`)
                .then(response => {
                    this.recentDonors = response.data
                });
        },

        getTopDonors () {
            axios.get(`${RJ.apiBaseUrl}/organization/${this.organization.id}/top-donors`)
                .then(response => {
                    this.topDonors = response.data
                });
        },

        setCampaignList (page) {
            // We will not show child components till we get the campaign and other information
            this.showCampaignList = false;

            let promises = [];
            let daysLeft;
            let arr = [];

            if (typeof page === 'undefined') {
                page = 1;
            }

            // Load the campaigns
            let campaignsPromise = this.$root.getCampaigns(this.organization.id, page)
                .then(response => {
                    this.campaigns = response.data;
                });

            promises.push(campaignsPromise);

            // When all axios requests are finished,
            // show the child components
            axios.all(promises)
                .then(() => {
                    this.showCampaignList = true;
                })

        },

        clickRow: function (donorId) {
            window.location.href = `${RJ.baseUrl}/organization/${this.organization.id}/donor/${donorId}`;
        },
    },
});
