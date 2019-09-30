Vue.component('campaign-admin-details', {
    props: [
        'organization',
        'campaignId'
    ],

    mixins: [
        require('../../mixins/campaign-progress'),
        require('../../mixins/campaign-deactivate')
    ],

    data: function() {
        return  {
            campaign: {
                id: '',
                name: '',
                fundraising_goal: '',
                end_date: '',
                video_url: '',
                description: '',
                image: '',
                donor_message: '',
            },
            // Initializing with 1 empty reward object
            rewards: [{
                image: ''
            }],
            showChildComponents: false,
            // campaign stats
            campaignStats : {},
            // for chart
            chartStartDate: '',//moment().subtract(15, 'd').format('MMM D, YYYY'),
            chartEndDate: '', //moment().format('MMM D, YYYY'),
            dataset: [],
            labels: {
                xLabels: [],
                yLabels: 5,
                yLabelsTextFormatter: val => this.$root.donationMoney(val, this.organization.currency.symbol)
            },
            grid: {
                verticalLines: true,
                verticalLinesNumber: 1,
                horizontalLines: true,
                horizontalLinesNumber: 1
            },
            // recent donations
            recentDonations: {},
            // payout history
            payoutHistory: {},
            // for view modal
            form: new RJForm({
                id: '',
                name: '',
                fundraising_goal: '',
                end_date: '',
                video_url: '',
                description: '',
                image: '',
            }),
            imageStyles: {
                image: {},
            }
        }
    },

    mounted() {
        this.setCampaignInfo();
        this.getCampaignStatistics();
        this.getRecentDonations();
    },

    methods: {

        showPreviewModal() {
            $('#campaign-view').modal('show');
        },

        setCampaignInfo () {
            // We will not show child components till we get the campaign and other information
            this.showChildComponents = false;

            let promises = [];

            let campaignId;

            if (this.campaignId) {
                campaignId = this.campaignId;
            } else {
                campaignId = this.$route.query.id;
            }

            // Load the campaign
            let campaignPromise = this.$root.getCampaign(this.organization.id, campaignId)
                .then(response => {
                    this.campaign = response.data;

                    this.form.id = this.campaign.id;
                    this.form.name = this.campaign.name;
                    this.form.fundraising_goal = this.campaign.fundraising_goal;
                    this.form.end_date = this.campaign.end_date;
                    this.form.video_url = this.campaign.video_url;
                    this.form.description = this.campaign.description;
                    this.form.image = this.campaign.image;
                    this.setImageStyles();
                });

            let rewardsPromise = this.$root.getRewards(this.organization.id, campaignId)
                .then(response => {
                    if (response.data.length > 0) {
                        Vue.set(this, 'rewards', response.data);
                    } else {
                        this.rewards = [];
                    }
                });

            promises.push(campaignPromise, rewardsPromise);

            // When all axios requests are finished,
            // show the child components
            axios.all(promises)
                .then(() => {
                    this.showChildComponents = true;
                })
        },

        setImageStyles () {
            _.each(this.imageStyles, (styles, field) => {
                if (this.campaign[field]) {
                    this.imageStyles[field] = {
                        "background-image": `url(${this.campaign[field]})`,
                        "background-repeat": 'no-repeat',
                        "background-size": 'cover',
                        "background-position": 'center',
                    }
                }
            });
        },

        getCampaignStatistics () {
            let offset = moment().utcOffset();
            axios.get(`${RJ.apiBaseUrl}/organization/${this.organization.id}/campaign/${this.campaignId}/campaign-statistics?offset=${offset}`)
                .then(response => {
                    this.campaignStats = response.data;
                    let counter = 1;
                    const data = response.data.bpi;
                    for (let key in data) {
                        this.dataset.push(data[key]);
                        this.labels.xLabels.push(moment(key).format("MM/DD"));
                        counter++;
                    }

                    if (! _.isEmpty(data)) {
                        this.chartStartDate = moment(_.keys(data)[0]).format('MMM D, YYYY');
                        if (_.size(data) > 1) {
                            this.chartEndDate = moment(_.keys(data)[_.size(data) - 1]).format('MMM D, YYYY');
                        } else {
                            this.chartEndDate = this.chartStartDate;
                        }
                    }
                    // this.grid.verticalLinesNumber = counter;
                });
        },

        getRecentDonations () {
            // Get recent donation by limit
            axios.get(`${RJ.apiBaseUrl}/organization/${this.organization.id}/campaign/${this.campaignId}/recent-campaign-donations/5`)
                .then(response => {
                    this.recentDonations = response.data;
                    _.each(this.recentDonations, (donation, index) => {
                        this.recentDonations[index].time = this.$root.convertUTCToBrowser(donation.created_at);
                        this.recentDonations[index].created = this.$root.getDateDifferenceInHumanize(null, this.recentDonations[index].time);
                    });
                });
        },

        getPayoutHistory () {
            axios.get(`${RJ.apiBaseUrl}/organization/${this.organization.id}/campaign/${this.campaignId}/campaign-payouts`)
                .then(response => {
                    this.payoutHistory = response.data;
                });
        },

        clickRow: function (donorId) {
            window.location.href = `${RJ.baseUrl}/organization/${this.organization.id}/donor/${donorId}`;
        },

        reactivateCampaign (campaignId) {
            this.$swal({
                title: RJ.translations.confirm_reactivate_org_title,
                text: RJ.translations.confirm_reactivate_campaign_text,
                type: "warning",
                showCancelButton: true,
                confirmButtonText: RJ.translations.reactivate,
                cancelButtonText: RJ.translations.cancel
            })
            .then((result) => {
                if (result.value) {
                    this.sendReactivateRequest(campaignId);
                }
            });
        },

        sendReactivateRequest (campaignId) {
            axios.put(`${RJ.apiBaseUrl}/organization/${this.organization.id}/campaign/${campaignId}/reactivate`)
                .then(response => {
                    this.$swal({
                        title: RJ.translations.org_reactivated_title,
                        text: RJ.translations.campaign_reactivated_text,
                        type: 'success'
                    }).then(result => {
                        window.location.href = `${RJ.baseUrl}/campaign`;
                    });
                });
        },
    }


});
