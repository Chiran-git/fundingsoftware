Vue.component('campaign-create', {
    props: ['organization', 'campaignId'],

    data: function() {
        return  {
            currentStep: 1,
            stepsCompleted: 0,
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
            accounts: [],
            showChildComponents: false,
            campaignUsers: {}
        }
    },

    mounted() {
        app.$on('campaignInfoSaved', (campaign) => {
            Vue.set(this, 'campaign', campaign);
            // Close the preview modal if open
            $('#campaign-info-preview').modal('hide');

            if (! this.campaignId) {
                this.$root.addQueryToCurrentUrl('id', campaign.id);
                // Move to next tab
                this.currentStep = 2;
                this.stepsCompleted = 1;
                // Scroll to top
                this.scrollToTop();
            }

            // Get the list of campaign admins.
            this.getCampaignAdmins();
        });

        app.$on('campaignRewardSaved', () => {
            // Close the preview modal if open
            $('#campaign-rewards-preview').modal('hide');

            if (! this.campaignId) {
                this.$root.addMultipleQueryToCurrentUrl({'step':3, 'id':this.campaign.id});
                // Move to next tab only if all reward forms are in finished status
                this.currentStep = 3;
                this.stepsCompleted = 2;
                this.scrollToTop();
            }
        });

        app.$on('donorMessageSaved', () => {
            if (! this.campaignId) {
                this.$root.addMultipleQueryToCurrentUrl({'step':4, 'id':this.campaign.id});
                // Move to next tab only if donor message saved
                this.currentStep = 4;
                this.stepsCompleted = 3;
                this.scrollToTop();
            }
        });

        app.$on('invitationCreated', () => {
            if (! this.campaignId) {
                this.$root.addMultipleQueryToCurrentUrl({'step':5, 'id':this.campaign.id});
                // Move to next tab only if invitation is saved
                this.currentStep = 5;
                this.stepsCompleted = 4;
                this.scrollToTop();
            }
        });

        app.$on('campaignPayoutSaved', () => {
            if (! this.campaignId) {
                // Move to next tab only if invitation is saved
                this.currentStep = 6;
                this.stepsCompleted = 5;
                this.scrollToTop();
            }
        });

        this.$root.getConnectedAccounts(this.organization.id)
            .then(response => {
                Vue.set(this, 'accounts', response.data);
                // If we have id in the query param, then it means campaign has been created already
                if (this.$route.query.id || this.campaignId) {
                    this.setCampaignInfo();
                } else {
                    this.showChildComponents = true;
                }
            });

        // Set the current step if given in the url query.
        // But do it only if we have a campaign id present
        if (this.$route.query.id && this.$route.query.step) {
            this.currentStep = this.$route.query.step;
            this.stepsCompleted = this.currentStep - 1;
        }
    },

    methods: {
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

                    if (response.data.end_date) {
                        this.campaign.end_date = this.$root.convertUTCToBrowser(response.data.end_date);
                    }
                });

            let rewardsPromise = this.$root.getRewards(this.organization.id, campaignId)
                .then(response => {
                    if (response.data.length <= 0) {
                        response.data = [{
                            image: ''
                        }];
                    }
                    Vue.set(this, 'rewards', response.data);
                });

            promises.push(campaignPromise, rewardsPromise);

            // When all axios requests are finished,
            // show the child components
            axios.all(promises)
                .then(() => {
                    this.showChildComponents = true;
                })


        },

        changeStep (step) {
            // Change step only if the step is less than what has been completed
            if (step <= this.stepsCompleted || step < this.currentStep) {
                this.currentStep = step;
            }
        },
        scrollToTop () {
            this.$SmoothScroll(document.getElementById('campaign-create'));
        },

        showPreview (modalId) {
            $('#' + modalId).modal('show');
        },

        closePreview(modalId) {
            $('#' + modalId).modal('hide');
        },

        getCampaignAdmins() {
            if (this.campaign.id) {
                axios.get(`${RJ.apiBaseUrl}/organization/${this.organization.id}/campaign/${this.campaign.id}/campaign-admins`)
                    .then((response) => this.campaignUsers = response.data);
            }
        }
    }
});
