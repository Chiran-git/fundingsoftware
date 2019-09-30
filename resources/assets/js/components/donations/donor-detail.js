import VueSimplemde from 'vue-simplemde'
import 'simplemde/dist/simplemde.min.css'

Vue.component('donor-detail', {

    props: ['organization', 'donor'],

    components: {
        VueSimplemde
    },

    data: function() {

        const descriptionMdeConfig = _.merge(this.$root.defaultMdeConfig, {
            placeholder: RJ.translations.campaign_description_placeholder
        });

        return  {
            donations: {},
            donor_questions: {},
            form: new RJForm({
                subject: '',
                message: ''
            }),
            descriptionMdeConfig: descriptionMdeConfig,
        }
    },

    mounted() {
        this.setDonationsList();
        this.setDonorQuestions();

        // Call vue js resetForm function on modal hide.
        $(this.$refs.emailDonorModal).on("hidden.bs.modal", this.resetForm);
    },

    methods: {
        setDonationsList () {
            // Load the donors
            this.$root.getDonations(this.organization.id, this.donor.id)
                .then(response => {
                    this.donations = response.data;
                });

        },

        setDonorQuestions () {
            // Load the donor question answers
            this.$root.getDonorQuestionAnswers(this.organization.id, this.donor.id)
                .then(response => {
                    this.donor_questions = response.data;
                });
        },

        showEmailDonorModal () {
            $('#modal-email-donor').modal('show');
        },

        submit() {
            RJ.post(`${RJ.apiBaseUrl}/organization/${this.organization.id}/donor/${this.donor.id}/email`, this.form)
                .then(response => {
                    this.$root.sMessage(RJ.translations.email_sent);
                    $('#modal-email-donor').modal('hide');
                })
                .catch(error => {
                    this.$root.eMessage(RJ.translations.save_error);
                });
        },

        resetForm () {
            this.form.subject = this.form.message = '';
            $("#modal-email-donor span.invalid-feedback").attr('style', 'display:none !important');
            $("#modal-email-donor .is-invalid").removeClass('is-invalid');
        }
    }
});
