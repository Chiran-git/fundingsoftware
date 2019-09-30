import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';

Vue.component('admin-edit', {

    props: ['user'],

    mixins: [
        require('../../mixins/crop-image')
    ],

    data: function() {
        return  {
            form: new RJForm({
                id: '',
                first_name: '',
                last_name: '',
                email: '',
                image: '',
                password: '',
                password_confirmation: '',
            }),
            imageStyles: {
                image: {}
            },
            filesToDelete: [],
            originalImageFiles: {
                image: {
                    requiredWidth: 150,
                    requiredHeight: 150,
                }
            }
        }
    },

    mounted() {
        this.getUserDetails();
        this.setImageStyles();
    },

    methods: {

        getUserDetails() {
            if (Object.keys(this.user).length > 0) {
                axios.get(`${RJ.apiBaseUrl}/admin/user/${this.user.id}`)
                .then(response => {
                    this.form.id = response.data.id || '';
                    this.form.first_name = response.data.first_name || '';
                    this.form.last_name = response.data.last_name || '';
                    this.form.email = response.data.email || '';
                    this.form.image = response.data.image || '';
                    this.setImageStyles();
                });
            }
        },

        setImageStyles () {
            _.each(this.imageStyles, (styles, field) => {
                if (this.form[field]) {
                    this.imageStyles[field] = {
                        "background-image": `url(${this.form[field]})`,
                        "background-repeat": 'no-repeat',
                        "background-size": 'cover',
                        "background-position": 'center',
                    }
                }
            });
        },

        changeImage(field) {
            // this.user[field] = URL.createObjectURL(this.$refs[field].files[0]);
            this.initializeCrop(this.$refs[field].files[0], field, this.form);
            // Remove the field name from files to delete if present
            // as we are actually uploading a new image
            _.remove(this.filesToDelete, fileField => {
                return fileField === field;
            });
            this.setImageStyles();
        },

        confirmDeleteUploadedFile (field) {
            this.$swal({
                title: RJ.translations.confirm_delete_title,
                text: RJ.translations.confirm_delete_image,
                type: 'warning',
                showCancelButton: true
            }).then(result => {
                if (result.value) {
                    this.deleteUploadedFile(field);
                }
            });
        },

        deleteUploadedFile (field) {
            this.filesToDelete.push(field);
            this.form[field] = null;
            this.imageStyles[field] = {
                "background": "#d8d8d8"
            };
            this.$refs[field].value = '';
        },

        submit() {
            this.form.startProcessing();
            // We need to gather a fresh FormData instance to POST it up to the server.
            // This is done to upload the files
            let formData = new FormData();
            // Replace # in the color codes with empty strings
            formData.append('first_name', this.form.first_name);
            formData.append('last_name', this.form.last_name);
            formData.append('email', this.form.email);
            formData.append('password', this.form.password);
            formData.append('password_confirmation', this.form.password_confirmation);

            // If "image" is to be removed, then set its value to empty string. On server side
            // this indicates that it is to be deleted. Also, do it only if we are editing a user
            if (this.form.id && this.filesToDelete.indexOf('image') !== -1) {
                formData.append('image', '');
            }

            // Add all file uploads if a new file has been chosen
            if (this.$refs.image.files.length) {
                let image = this.croppedImageFiles.image || this.$refs.image.files[0];
                formData.append('image', image, this.$refs.image.files[0].name);
            }

            let promise;

            if (this.form.id) {
                promise = axios.post(
                    `${RJ.apiBaseUrl}/admin/user/${this.user.id}/update`,
                    formData, {headers: {'Content-Type': 'multipart/form-data'}}
                );
            } else {
                promise = axios.post(
                    `${RJ.apiBaseUrl}/admin/user`,
                    formData, {headers: {'Content-Type': 'multipart/form-data'}}
                );
            }

            promise.then((response) => {
                this.$root.sMessage(RJ.translations.saved_successfully);
                this.form.finishProcessing();
                window.location.href = `${RJ.baseUrl}/admin/admins`;
            })
            .catch((error) => {
                this.$root.eMessage(RJ.translations.save_error);
                this.form.setErrors(error.response.data.errors);
                this.form.finishProcessing();
            });
        }

    }
});
