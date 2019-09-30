Vue.component('make-donation', {
    props: [
        'organization',
        'campaign',
        'reward',
        'questions',
        'country'
    ],

    mixins: [
        require('./../../mixins/stripe')
    ],

    data () {
        return {
            form: new RJForm({
                first_name: '',
                last_name: '',
                email: '',
                card_name: '',
                card: '',
                amount: this.$route.query.amount || '',
                reward: this.$route.query.reward || '',
                questions: [],
                mailing_address1: '',
                mailing_address2: '',
                mailing_city: '',
                mailing_state: '',
                mailing_zipcode: '',
                mailing_country_id: this.country.id,
                comment: '',
            }),
            cardForm: new RJForm({
                name: '',
            }),
            cardElement: null,
            states: {}
        }
    },

    mounted () {
        this.cardElement = this.createCardElement('#card-element');
        // Add the questions to the form
        _.each(this.questions, (question) => {
            Vue.set(this.form, `question_${question.id}`, '');
            this.form.questions.push(question.id);
        });

        // Get states is defined in rj mixin
        this.$root.getStates(this.country.iso_code)
            .then(response => {
                this.states = response.data;
            });
    },

    methods: {
        submit () {
            this.form.busy = true;
            this.form.errors.forget();
            this.form.successful = false;

            // Here we will build out the payload to send to Stripe to obtain a card token so
            // we can create the actual subscription. We will build out this data that has
            // this credit card number, CVC, etc. and exchange it for a secure token ID.
            const payload = {
                name: this.cardForm.name,
            };

            // Once we have the Stripe payload we'll send it off to Stripe and obtain a token
            // which we will send to the server to make the donation. If there is
            // an error we will display that back out to the user for their information.
            this.stripe.createToken(this.cardElement, payload).then(response => {
                if (response.error) {
                    this.form.errors.set({card: [
                        response.error.message
                    ]});

                    this.form.busy = false;
                } else {
                    this.submitToServer(response.token.id);
                }
            });
        },

        /**
         * Send the donor information to the server
         */
        submitToServer(token) {
            this.form.stripe_token = token;
            this.form.card_name = this.cardForm.name;

            RJ.post(`${RJ.baseUrl}/donation/${this.campaign.slug}`, this.form)
                .then((response) => {
                    window.location = `${RJ.baseUrl}/${this.organization.slug}/${this.campaign.slug}/donation/${response.id}/success`;
                })
                .catch(error => {
                    let message = RJ.translations.default_error_message;
                    if (! _.isUndefined(error.message)) {
                        message = error.message;
                    }
                    this.$root.eMessage(message);
                });
        },

        /**
         *
         * Get the state options label.
         */
        getStateOptionLabel (state) {
            return this.states[state];
        },
    },

    computed: {
        stateOptions () {
            let options = [];

            _.each(this.states, (label, state) => {
                options.push(state);
            });

            return options;
        }
    },
});
