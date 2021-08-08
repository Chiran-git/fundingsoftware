Vue.component('org-create', {
    props: ['organization'],
    data: function() {
        return  {
            createdOrganization: {},
            currentStep: 1,
            stepsCompleted: 0,
            donorUpdated: false,
            active: true
        }
    },

    mounted() {

        if (this.$route.query.step) {
            this.currentStep = this.$route.query.step;
        }
    },

    methods: {
        changeTab(step) {
            this.currentStep = step;
            this.stepsCompleted = step - 1;
            if (this.createdOrganization.id) {
                this.$root.refreshOrganization(this.createdOrganization.id);
            }
            this.scrollToTop();
        },

        changeStep (step) {
            // Change step only if the step is less than what has been completed
            if (step <= this.stepsCompleted) {
                this.currentStep = step;
            }
        },

        markSetupCompleted () {
            if (this.createdOrganization.id) {
                return axios.put(`${RJ.apiBaseUrl}/organization/${this.createdOrganization.id}/setup-complete`);
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
