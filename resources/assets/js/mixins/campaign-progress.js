module.exports = {
    props: ['organization'],

    data () {
        return {
            campaignBackgroundColor: {}
        }
    },

    mounted() {
        this.setCampaignBackgroundColorStyle();
    },

    methods: {
        fundsRaised (campaign, organization) {
            return this.$root.donationMoney(campaign.funds_raised, organization.currency.symbol);
        },

        fundRaisingGoal (campaign, organization) {
            return this.$root.donationMoney(campaign.fundraising_goal, organization.currency.symbol);
        },

        endsAt (campaign) {

            if (! campaign.end_date) {
                return RJ.translations.never_ending;
            }

            return this.$root.getDateDifferenceHumanize(null, campaign.end_date);
        },

        progress (campaign) {
            if (! campaign.funds_raised || ! campaign.fundraising_goal) {
                return 0;
            }

            return Math.round((campaign.funds_raised / campaign.fundraising_goal) * 100);
        },

        setCampaignBackgroundColorStyle () {
            this.campaignBackgroundColor = {
                "background": this.organization.primary_color,
                "border-bottom": "2.3125rem solid " + this.organization.secondary_color,
                "border-top": "0px",
                "border-left": "0px",
                "border-right": "0px"                
            }
        }
    },
}
