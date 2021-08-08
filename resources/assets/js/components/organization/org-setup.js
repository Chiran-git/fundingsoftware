Vue.component('org-setup', {
    props: ['organization'],

    data: function() {
        return  {
            currentStep: 1,
            stepsCompleted: 0,
            active: true
        }
    },

    mounted() {
        if (this.organization.id) {
            app.$on('orgProfileSaved', () => {
                // Move to next tab
                this.currentStep = 2;
                this.stepsCompleted = 1;
                if (this.organization) {
                    this.$root.refreshOrganization(this.organization.id);
                }
                // Scroll to top
                this.scrollToTop();
            });

            app.$on('orgDesignSaved', () => {
                // Close the preview modal if open
                $('#modal-template-preview').modal('hide');

                // Move to next tab
                this.currentStep = 3;
                this.stepsCompleted = 2;
                if (this.organization) {
                    this.$root.refreshOrganization(this.organization.id);
                }
                this.scrollToTop();
            });

            app.$on('orgDonorProfileSaved', () => {
                // Move to next tab
                this.currentStep = 4;
                this.stepsCompleted = 3;
                // Since this is the last step, mark account setup completed
                this.markSetupCompleted();
                if (this.organization) {
                    this.$root.refreshOrganization(this.organization.id);
                }
                this.scrollToTop();
            });
        }

        if (this.$route.query.step) {
            this.currentStep = this.$route.query.step;
        }
    },

    methods: {
        changeTab(step) {
            this.currentStep = step;
            this.stepsCompleted = step - 1;
            if (this.organization.id) {
                this.$root.refreshOrganization(this.organization.id);
            }
            this.scrollToTop();
        },

        changeStep (step) {
            // Change step only if the step is less than what has been completed
            if (step <= this.stepsCompleted || step == (this.stepsCompleted + 1)) {
                this.currentStep = step;
            }
        },

        markSetupCompleted () {
            if (this.organization.id) {
                return axios.put(`${RJ.apiBaseUrl}/organization/${this.organization.id}/setup-complete`);
            }
        },

        scrollToTop () {
            this.$SmoothScroll(document.getElementById('account-setup'));
        },

        setStepsCompleted () {
            this.stepsCompleted = 0;
            if ((this.currentStep > 1) && (this.stepsCompleted != this.currentStep)) {
                this.stepsCompleted = this.currentStep - 1;
            }            
        }
    }
});
