Vue.component('org-profile', {

    props: ['organization'],

    data: function() {
        return  {
            form: new RJForm({
                first_name: '',
                last_name: '',
                email: '',
                name: '',
                address1: '',
                address2: '',
                city: '',
                state: '',
                zipcode: '',
                //country: '',
                currency: '',
                phone: '',
                slug: '',
            }),
            states: {},
            currencies: {},
            imageStyles: {
                cover_image: {},
                logo: {},
                appeal_photo: {},
            },
            campaigns: {},
            showCampaignList: false,
            orgBackgroundColor: {}
        }
    },

    mounted() {
        this.setOrganization();

        let isoCode = 'US';
        if (this.organization.id) {

            if (this.organization.country) {
                isoCode = this.organization.country.iso_code;
            }

            this.setImageStyles();
            this.setCampaignList();
            this.setbackgroundColorStyle();

        }

        // Get states is defined in rj mixin
        this.$root.getStates(isoCode)
        .then(response => {
            this.states = response.data;
        });

        this.$root.getCurrencies()
        .then(response => {
            this.currencies = response.data;
        });
    },

    methods: {

        setOrganization() {
            if (this.organization) {
                this.form.name = this.organization.name ;
                this.form.address1 = this.organization.address1 ;
                this.form.address2 = this.organization.address2 ;
                this.form.city = this.organization.city ;
                this.form.state = this.organization.state ;
                this.form.zipcode = this.organization.zipcode ;
                this.form.country = this.organization.country ? this.organization.country.iso_code : '';
                this.form.currency = this.organization.currency ? this.organization.currency.iso_code : '';
                this.form.phone = this.organization.phone ;
                this.form.slug = this.organization.slug ;
            } else {
                this.form.country = 'US' ;
            }
        },

        setImageStyles() {
            _.each(this.imageStyles, (styles, field) => {
                if (this.organization[field]) {
                    this.imageStyles[field] = {
                        "background-image": `url(${this.organization[field]})`,
                        "background-repeat": 'no-repeat',
                        "background-size": 'cover',
                        "background-position": 'center',
                    }
                }
            });
        },

        submit() {
            if (this.organization.id || this.$parent.createdOrganization.id) {
                var emit = true;
                if (typeof this.$parent.createdOrganization !== "undefined") {
                    var organizationId = this.$parent.createdOrganization.id
                    emit = false;
                } else {
                    organizationId = this.organization.id;
                }
                RJ.put(`${RJ.apiBaseUrl}/organization/${organizationId}/profile`, this.form)
                .then(response => {
                    this.$root.sMessage(RJ.translations.saved_successfully);
                    if (emit === true) {
                        app.$emit('orgProfileSaved');
                    } else {
                        // Move to next tab
                        this.showNextTab();
                    }
                })
                .catch(error => {
                    this.$root.eMessage(RJ.translations.save_error);
                });
            } else {
                RJ.post(`${RJ.apiBaseUrl}/admin/organization/create`, this.form)
                .then(response => {
                    this.$root.sMessage(RJ.translations.saved_successfully);

                    this.$parent.createdOrganization = response;
                    this.$root.refreshOrganization(response.id);

                    // Move to next tab
                    this.showNextTab();

                })
                .catch(error => {
                    this.$root.eMessage(RJ.translations.save_error);
                });
            }
        },

        getStateOptionLabel (state) {
            return this.states[state];
        },

        setCampaignList (page) {
            // We will not show child components till we get the campaign and other information
            this.showCampaignList = false;

            let promises = [];

            if (typeof page === 'undefined') {
                page = 1;
            }

            // Load the campaigns
            let campaignsPromise = this.$root.getCampaigns(this.organization.id, page)
                .then(response => {
                    this.campaigns = response.data;

                    _.each(this.campaigns.data, (campaign, index) => {
                        if (campaign.end_date) {
                            this.campaigns.data[index].end_date = this.$root.convertUTCToBrowser(campaign.end_date);
                            this.campaigns.data[index].days_left = this.$root.getDateDifferenceInDays(null, this.campaigns.data[index].end_date);
                        }
                        if (campaign.funds_raised == null) {
                            campaign.funds_raised = 0;
                        }
                        this.campaigns.data[index].donation_percent = Math.floor((campaign.funds_raised / campaign.fundraising_goal) * 100);
                        this.campaigns.data[index].fundraising_goal = this.$root.formatAmount(this.campaigns.data[index].fundraising_goal, this.organization.currency.symbol);
                        this.campaigns.data[index].funds_raised = this.$root.formatAmount(this.campaigns.data[index].funds_raised, this.organization.currency.symbol);
                    });

                });

            promises.push(campaignsPromise);

            // When all axios requests are finished,
            // show the child components
            axios.all(promises)
                .then(() => {
                    this.showCampaignList = true;
                })

        },

        setbackgroundColorStyle () {
            this.orgBackgroundColor = {
                "background": this.organization.primary_color,
                "border-top": "0.9375rem solid " + this.organization.secondary_color,
                "border-bottom": "0.9375rem solid " + this.organization.secondary_color,
                "border-left": "0px",
                "border-right": "0px"
            }
        },

        showNextTab () {
            // Move to next tab
            this.$parent.currentStep = 2;
            this.$parent.stepsCompleted = 1;

            // Scroll to top
            this.$parent.scrollToTop();
        }
    },

    computed: {
        stateOptions () {
            let options = [];

            _.each(this.states, (label, state) => {
                options.push(state);
            });

            return options;
        },

        currencyOptions () {
            let options = [];
            _.each(this.currencies, (currency) => {
                options.push(currency.iso_code);
            });

            return options;
        }
    }

});
