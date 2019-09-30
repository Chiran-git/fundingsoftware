Vue.component('accept-invitation', {
    props: [
        'organization',
        'code',
        'set_password'
    ],

    data: function() {
        return  {
            form: new RJForm({
                password: "",
                password_confirmation: ""
            })
        }
    },

    mounted() {
        if (! this.set_password) {
            // Reset the forms array to empty
            this.form = new RJForm();
        }
    },

    methods: {

        acceptInvitation() {
            this.form.startProcessing();
            RJ.post(`${RJ.apiBaseUrl}/organization/${this.organization.id}/invitation/${this.code}`, this.form)
                .then(response => {
                    if (response.id) {
                        this.form.finishProcessing();
                        this.$swal({
                            title: 'Invitation Accepted!',
                            text: 'Login to manage campaigns.',
                            confirmButtonText: 'OK',
                            allowOutsideClick: false
                          }).then((result) => {
                            if(result.value) {
                                window.location.href = "/login";
                            }
                          });
                    }
                }).catch((error) => {

                    this.form.setErrors(error.response.data.errors);
                    this.form.finishProcessing();
                });
        }
    }
});
