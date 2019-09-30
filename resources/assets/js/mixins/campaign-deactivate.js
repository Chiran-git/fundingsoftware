module.exports = {
    data () {
        return {

        }
    },

    methods: {
        deactivateCampaign (campaignId) {
            this.$swal({
                title: RJ.translations.confirm_deactivate_org_title,
                text: RJ.translations.confirm_deactivate_campaign_text,
                type: "warning",
                showCancelButton: true,
                confirmButtonText: RJ.translations.deactivate,
                cancelButtonText: RJ.translations.cancel
            })
            .then((result) => {
                if (result.value) {
                    this.sendDeactivateRequest(campaignId);
                }
            });
        },

        sendDeactivateRequest (campaignId) {
            axios.put(`${RJ.apiBaseUrl}/organization/${this.organization.id}/campaign/${campaignId}/deactivate`)
                .then(response => {
                    this.$swal({
                        title: RJ.translations.org_deactivated_title,
                        text: RJ.translations.campaign_deactivated_text,
                        type: 'success'
                    }).then(result => {
                        window.location.href = `${RJ.baseUrl}/campaign`;
                    });
                });
        },
    },
}
