Vue.component('org-sign-up', {
    data() {
        let firstName = '';
        let lastName = '';
        if (this.$route.query.name) {
            const names = this.$route.query.name.split(' ');
            firstName = names[0] || '';
            lastName = names[1] || '';
        }

        return {
            signupForm: new RJForm({
                name: this.$route.query.organization || '',
                first_name: firstName,
                last_name: lastName,
                email: '',
                password: '',
                password_confirmation: '',
            }),
            signupFormIsValid: false,
        }
    },

    methods: {
        signup() {
            // If form is not valid then return
            if (! this.signupFormIsValid) {
                return false;
            }

            RJ.post(`${RJ.apiBaseUrl}/organization`, this.signupForm)
                .then(response => {
                    this.signupForm.resetData();
                    $('#signup-success-modal').modal('show');
                });
        },

        // Method to validate the form. We are doing only very basic
        // validation, mainly fields are filled in or not. Other complex
        // validation is done on server side.
        validate() {
            // By default lets put the form as valid
            this.signupFormIsValid = true;

            if (this.signupForm.name == ''
                || this.signupForm.first_name == ''
                || this.signupForm.last_name == ''
                || this.signupForm.email == ''
                || this.signupForm.password == ''
                || this.signupForm.password_confirmation == '') {
                    this.signupFormIsValid = false;
                }
        },
    }
});
