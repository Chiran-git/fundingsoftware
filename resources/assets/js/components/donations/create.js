Vue.component('donation-create', {
    props: ['organization', 'mailingAddressEnabled', 'mailingAddressRequired', 'commentEnabled', 'commentRequired'],

    data: function() {
        return  {
            form: new RJForm({
                first_name: '',
                last_name: '',
                email: '',
                donation_method: '',
                affiliation_id: '',
                check_number: '',
                gross_amount: '',
                campaign_id: this.$route.query.campaign || '',
                mailing_address1: '',
                mailing_address2: '',
                mailing_city: '',
                mailing_state: '',
                mailing_zipcode: '',
                billing_address1: '',
                billing_address2: '',
                billing_city: '',
                billing_state: '',
                billing_zipcode: '',
                comments: '',
                donor_answers: {},

            }),
            sameAsBilling: 0,
            states: {},
            paymentMethods: this.getPaymentMethodOptions(),
            campaignList: this.getCampaigns(),
            donorQuestions: [],
            affiliations: {},
        }
    },

    mounted() {
        // Get states is defined in rj mixin
        this.$root.getStates(this.organization.country.iso_code)
            .then(response => {
                this.states = response.data;
            });

        // Get the organization donor questions.
        this.$root.getDonorQuestions(this.organization.id)
            .then(response => {
                this.donorQuestions = response.data;

                _.each(this.donorQuestions, question => {
                    this.form.donor_answers[question.id] = {
                        is_required: question.is_required,
                        answer: ''
                    };
                });

            });

        this.getAffiliations();
    },

    methods: {
        getAffiliations() {
            return axios.get(`${RJ.apiBaseUrl}/affiliations`)
            .then( response => {
                this.affiliations = response.data;
            });
        },

        submit() {
            if (this.sameAsBilling) {
                this.form.mailing_address1 = this.form.billing_address1;
                this.form.mailing_address2 = this.form.billing_address2;
                this.form.mailing_city = this.form.billing_city;
                this.form.mailing_state = this.form.billing_state;
                this.form.mailing_zipcode = this.form.billing_zipcode;
            }

            RJ.post(`${RJ.apiBaseUrl}/organization/${this.organization.id}/donor`, this.form)
                .then(response => {
                    if (! _.isUndefined(response.id)) {
                        this.$root.sMessage(RJ.translations.saved_successfully);
                        window.location.href = `${RJ.baseUrl}/donations`;
                    }
                })
                .catch(error => {
                    this.$root.eMessage(RJ.translations.save_error);
                });
        },

        getPaymentMethodOptions () {
            return RJ.translations.payment_methods;
        },

        getStateOptionLabel (state) {
            return this.states[state];
        },

        getPaymentMethodOptionLabel (paymentMethod) {
            return this.paymentMethods[paymentMethod];
        },

        getAffiliationOptionLabel (affiliation) {
            return this.affiliations[affiliation].name;
        },

        getCampaignOptionLabel (campaign) {
            return _.has(this.campaignList, campaign) ? this.campaignList[campaign] : '';
        },

        getCampaigns () {
            this.$root.getOrganizationCampaigns(this.organization.id)
                .then(response => {
                    this.campaignList = response.data;
                });
        },
        resetForm () {
            this.form.first_name = this.form.last_name = this.form.email =this.form.donation_method = this.form.check_number = this.form.gross_amount = this.form.campaign_id = this.form.mailing_address1 = this.form.mailing_address2 = this.form.mailing_city = this.form.mailing_state = this.form.mailing_zipcode = this.form.billing_address1 = this.form.billing_address2 = this.form.billing_city = this.form.billing_state = this.form.billing_zipcode = this.form.comments = '';
            this.sameAsBilling = 0;

            _.each(this.form.donor_answers, (answer, key) => {
                this.form.donor_answers[key].answer = '';
            });
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

        paymentMethodOptions () {
            let options = [];

            _.each(this.paymentMethods, (label, paymentMethod) => {
                options.push(paymentMethod);
            });

            return options;
        },

        campaignOptions () {
            let options = [];

            _.each(this.campaignList, (label, campaign) => {
                options.push(campaign);
            });

            return options;
        },

        affiliationOptions () {
            let options = [];

            _.each(this.affiliations, (label, affiliation) => {
                options.push(affiliation);
            });
            return options;
        },

    },

    watch: {
        sameAsBilling (value) {
            if (this.sameAsBilling) {
                this.form.mailing_address1 = this.form.billing_address1;
                this.form.mailing_address2 = this.form.billing_address2;
                this.form.mailing_city = this.form.billing_city;
                this.form.mailing_state = this.form.billing_state;
                this.form.mailing_zipcode = this.form.billing_zipcode;
            } else {
                this.form.mailing_address1 = this.form.mailing_address2 = this.form.mailing_city = this.form.mailing_state = this.form.mailing_zipcode = '';
            }
        }
    }
});
