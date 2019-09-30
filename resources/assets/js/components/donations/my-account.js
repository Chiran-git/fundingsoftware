import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';

Vue.component('my-account', {
    props: ['currentUser'],

    mixins: [
        require('../../mixins/crop-image')
    ],

    data: function() {
        return  {
        	user: new RJForm({
                id: '',
        		first_name: '',
        		last_name: '',
        		email: '',
        		image: '',
        		job_title: '',
                role: ''
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
            },
            roles: this.getAdminRoles(),
        }
    },

    mounted() {
        this.getUserDetails();
    },

    methods: {
        getUserDetails() {
            this.user.id = this.currentUser.id || '';
            this.user.first_name = this.currentUser.first_name || '';
            this.user.last_name = this.currentUser.last_name || '';
            this.user.email = this.currentUser.email || '';
            this.user.job_title = this.currentUser.job_title || '';
            this.user.user_type = this.currentUser.user_type || '';
            this.user.image = this.currentUser.image || '';
            this.setImageStyles();

        },

        getAdminRoles() {
            return {
                'RocketJar Account Executive': 'RocketJar Account Executive',
                'Organization Admin': 'Organization Admin - Can create and edit all users, campaigns and organization'
            }
        },

        setImageStyles () {
            _.each(this.imageStyles, (styles, field) => {
                if (this.user[field]) {
                    this.imageStyles[field] = {
                        "background-image": `url(${this.user[field]})`,
                        "background-repeat": 'no-repeat',
                        "background-size": 'cover',
                        "background-position": 'center',
                    }
                }
            });
        },

        changeImage(field) {
            // this.user[field] = URL.createObjectURL(this.$refs[field].files[0]);
            this.initializeCrop(this.$refs[field].files[0], field, this.user);
            // Remove the field name from files to delete if present
            // as we are actually uploading a new image
            _.remove(this.filesToDelete, fileField => {
                return fileField === field;
            });
            this.setImageStyles();
        },

        openFileDialog(field) {
            this.$refs[field].click();
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
            this.user[field] = null;
            this.imageStyles[field] = {
                "background": "#d8d8d8"
            };
            this.$refs[field].value = '';
        },

    	submit() {
            this.user.startProcessing();
            // We need to gather a fresh FormData instance to POST it up to the server.
            // This is done to upload the files
            let formData = new FormData();
            // Replace # in the color codes with empty strings
            formData.append('first_name', this.user.first_name);
            formData.append('last_name', this.user.last_name);
            formData.append('email', this.user.email);
            formData.append('job_title', this.user.job_title);


            // If "image" is to be removed, then set its value to empty string. On server side
            // this indicates that it is to be deleted. Also, do it only if we are editing a campaign
            if (this.user.id && this.filesToDelete.indexOf('image') !== -1) {
                formData.append('image', '');
            }

            // Add all file uploads if a new file has been chosen
            if (this.$refs.image.files.length) {
                let image = this.croppedImageFiles.image || this.$refs.image.files[0];
                formData.append('image', image, this.$refs.image.files[0].name);
            }

            let promise;

            if (this.user.id) {
                promise = axios.post(
                    `${RJ.apiBaseUrl}/user/${this.user.id}`,
                    formData, {headers: {'Content-Type': 'multipart/form-data'}}
                );
            }

    		promise.then((response) => {
                this.$root.sMessage(RJ.translations.saved_successfully);
                this.user.finishProcessing();
                window.location.reload();
            })
            .catch((error) => {
                this.$root.eMessage(RJ.translations.save_error);
                this.user.setErrors(error.response.data.errors);
                this.user.finishProcessing();
            });
    	}
    },

    computed: {
        roleOptions () {
            let options = [];

            _.each(this.roles, (label, role) => {
                options.push(role);
            });

            return options;
        }
    },
});
