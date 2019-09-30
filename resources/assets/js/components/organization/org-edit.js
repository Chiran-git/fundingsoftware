Vue.component('org-edit', {
    props: ['organization'],

    data: function() {
        return  {
            currentStep: 1,
            stepsCompleted: 0,
            active: true
        }
    },

    mounted() {
        this.isActive();

        app.$on('orgProfileSaved', () => {
            this.$root.refreshOrganization(this.organization.id);
            // Scroll to top
            this.scrollToTop();
        });

        app.$on('orgDesignSaved', () => {
            // Close the preview modal if open
            $('#modal-template-preview').modal('hide');
            this.$root.refreshOrganization(this.organization.id);
            this.scrollToTop();
        });

        app.$on('orgDonorProfileSaved', () => {
            this.$root.refreshOrganization(this.organization.id);
            this.scrollToTop();
        });

        if (this.$route.query.step) {
            this.currentStep = this.$route.query.step;
        }
    },

    methods: {
        changeTab(step) {
            this.currentStep = step;
            this.stepsCompleted = step - 1;
            this.$root.refreshOrganization(this.organization.id);
            this.scrollToTop();
        },

        isActive() {
            if (this.organization.deactivated_at) {
                this.active = false;
                this.$swal({
                    title:  RJ.translations.deactive,
                    text: RJ.translations.org_deactive_text,
                    type: 'warning'
                }).then(result => {
                    window.location.href = `${RJ.baseUrl}/dashboard`;
                });
            }
        },

        deactivate($orgId) {
            this.$swal({
                title: RJ.translations.confirm_deactivate_org_title,
                text: RJ.translations.confirm_deactivate_org_text,
                type: "warning",
                showCancelButton: true,
                confirmButtonText: RJ.translations.deactivate,
                cancelButtonText: RJ.translations.cancel
                })
                .then((result) => {
                    if (result.value) {
                        axios.put(`${RJ.apiBaseUrl}/organization/${$orgId}/deactivate`)
                        .then(response => {
                            this.$swal({
                                title: RJ.translations.org_deactivated_title,
                                text: RJ.translations.org_deactivated_text,
                                type: 'success'
                            }).then(result => {
                                window.location.href = `${RJ.baseUrl}/dashboard`;
                            });
                        });
                    } else {
                        this.$swal(RJ.translations.cancelled);
                    }
                });
        },

        changeStep (step) {
            // Change step only if the step is less than what has been completed
            if (step <= this.stepsCompleted) {
                this.currentStep = step;
            }
        },

        scrollToTop () {
            this.$SmoothScroll(document.getElementById('account-setup'));
        },
    }
});
