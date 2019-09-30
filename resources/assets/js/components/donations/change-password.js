import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';

Vue.component('change-password', {

    data: function() {
        return  {
        	form: new RJForm({
                id: '',
                old_password: '',
                password: '',
                password_confirmation: '',
        	})
        }
    },

    mounted() {
        this.getUserDetails();
    },

    methods: {

        getUserDetails() {
            axios.get(`${RJ.apiBaseUrl}/user`)
                .then(response => {
                    this.form.id = response.data.id;
                });
        },

        submit(event) {
            RJ.put(`${RJ.apiBaseUrl}/user/${this.form.id}/change-password`, this.form)
                .then(response => {
                    this.$swal({
                        title: 'Password Changed',
                        text: 'Your password has been changed successfully.',
                        type: 'success'
                    }).then(result => {
                        if (result.value) {
                            window.location.reload();
                        }
                    });
                });
        }

    }
});
