import pagination from 'laravel-vue-pagination';

Vue.component('campaign-list', {
    props: [
        'organization',
    ],

    mixins: [
        require('../../mixins/campaign-progress'),
        require('../../mixins/campaign-deactivate')
    ],

    components: {
        pagination
    },

    data: function() {
        return  {
            campaigns: {},
            showCampaignList: false,
            active: true,
            campaign: "",
            status: ''
        }
    },

    mounted() {
        this.isActive();

        if (this.active == true) {
            this.setCampaignList();
        }
    },

    methods: {

        isActive() {
            if (this.organization.deactivated_at) {
                this.active = false;
                this.$swal({
                    title: "Deactive",
                    text: "Your organization is deactive",
                    type: 'warning'
                }).then(result => {
                    window.location.href = `${RJ.baseUrl}/dashboard`;
                });
            }
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

            this.status = this.$route.query.status;

            // Load the campaigns
            let campaignsPromise = this.$root.getCampaigns(this.organization.id, page, this.status)
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

        showCampaignViewModal (index) {
            this.campaign = this.campaigns.data[index];
            $('#campaign-view').modal('show');
        }

    }
});
