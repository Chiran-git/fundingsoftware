Vue.component('org-donorprofile', {
    props: ['organization'],

    data: function() {
        return  {
            systemFieldsForm: new RJForm({
                mailing_address: {
                    required: false,
                    enabled: false,
                },
                comment: {
                    required: false,
                    enabled: false,
                }
            }),
            customFieldForm: new RJForm({
                question: "",

            }),
            donorQuestions: [],
        }
    },

    mounted() {
        this.setSystemFieldsForm();
        this.getDonorQuestions();
    },

    methods: {

        setSystemFieldsForm() {
            this.systemFieldsForm.mailing_address.required = this.organization.system_donor_questions ? this.organization.system_donor_questions.mailing_address.required : false;

            this.systemFieldsForm.mailing_address.enabled = this.organization.system_donor_questions ?
                        this.organization.system_donor_questions.mailing_address.enabled : false;

            this.systemFieldsForm.comment.required = this.organization.system_donor_questions ?this.organization.system_donor_questions.comment.required : false;

            this.systemFieldsForm.comment.enabled = this.organization.system_donor_questions ?
                        this.organization.system_donor_questions.comment.enabled : false;
        },

        getDonorQuestions () {
            var organizationId = this.getOrganizationId();
            if (organizationId) {
                this.$root.getDonorQuestions(organizationId)
                    .then(response => {
                        this.donorQuestions = response.data;
                    });
            }
        },

        removeDonorQuestion (index) {
            this.$swal({
                title: RJ.translations.confirm_delete_title,
                text: RJ.translations.confirm_delete_profile_question,
                type: 'warning',
                showCancelButton: true
              }).then((result) => {
                if(result.value) {
                    this.deleteCustomDonorQuestion(this.donorQuestions[index].id)
                        .then(response => {
                            this.$root.sMessage(RJ.translations.deleted_successfully);
                            // Remove the question from the donorQuestions array
                            this.donorQuestions.splice(index, 1);
                        });
                }
              });
        },

        addCustomDonorQuestion () {

            var organizationId = this.getOrganizationId();
            RJ.post(`${RJ.apiBaseUrl}/organization/${organizationId}/donor-question`, this.customFieldForm)
                .then(response => {
                    this.$root.sMessage(RJ.translations.saved_successfully);
                    this.donorQuestions.push(response);
                    this.customFieldForm.resetData();
                })
                .catch(error => {
                    this.$root.eMessage(RJ.translations.save_error);
                });
        },

        submit () {
            this.customFieldForm.question = '';
            $("div.form_wrapper:visible input.is-invalid").removeClass('is-invalid');
            $("div.form_wrapper:visible span.invalid-feedback").attr('style', 'display:none !important');

            this.$root.sMessage(RJ.translations.saved_successfully);

            if (! this.organization.id && this.$parent.createdOrganization.id) {
                // Move to next tab
                this.$parent.currentStep = 4;
                this.$parent.stepsCompleted = 3;
                // Since this is the last step, mark account setup completed
                this.$parent.markSetupCompleted();
                this.$root.refreshOrganization(this.$parent.createdOrganization.id);
                this.$parent.scrollToTop();
            } else {
                app.$emit('orgDonorProfileSaved');
            }
        },

        updateSystemDonorQuestions () {
            var organizationId = this.getOrganizationId();
            return RJ.put(`${RJ.apiBaseUrl}/organization/${organizationId}/system-donor-questions`, this.systemFieldsForm);
        },

        updateCustomDonorQuestions () {
            if (! this.donorQuestions.length) {
                // No donor questions to update
                return;
            }
            var organizationId = this.getOrganizationId();
            _.each(this.donorQuestions, question => {
                axios.put(
                    `${RJ.apiBaseUrl}/organization/${organizationId}/donor-question/${question.id}`,
                    {
                        question: question.question,
                        is_required: question.is_required,
                        enabled: question.enabled
                    }
                ).then(response => {
                    //
                });
            });
        },

        deleteCustomDonorQuestion (id) {
            var organizationId = this.getOrganizationId();
            return axios.delete(`${RJ.apiBaseUrl}/organization/${organizationId}/donor-question/${id}`);
        },

        getOrganizationId () {
            if (this.organization.id) {
                return this.organization.id;
            } else if (this.$parent.createdOrganization.id) {
                return this.$parent.createdOrganization.id;
            }
        }
    },

});
