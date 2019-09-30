module.exports = {
    /**
     * The mixin's data.
     */
    data() {
        return {
            stripe: RJ.stripeKey ? Stripe(RJ.stripeKey) : null
        }
    },


    methods: {
        /**
         * Create a Stripe Card Element.
         */
        createCardElement(container){
            if (! this.stripe) {
                throw "Invalid Stripe Key/Secret";
            }

            var card = this.stripe.elements({
                fonts: [{
                    cssSrc: 'https://fonts.googleapis.com/css?family=Assistant:300,400,600,700,800'
                }]
            }).create('card', {
                hideIcon: false,
                hidePostalCode: true,
                style: {
                    base: {
                        '::placeholder': {
                            color: '#7e8383',
                            fontWeight: '100',
                        },
                        fontFamily: '"Assistant", sans-serif',
                        color: '#495057',
                        fontSize: '14px',
                        fontWeight: '400',
                        textTransform: 'capitalize'
                    }
                }
            });

            card.mount(container);

            return card;
        }
    },
};
