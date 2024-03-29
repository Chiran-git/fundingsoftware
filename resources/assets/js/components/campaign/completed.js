Vue.component('campaign-completed', {
    props: [
        'organization',
        'campaign'
    ],

    mixins: [
        require('../../mixins/campaign-progress')
    ],

    /*components: {
        flatPickr
    },*/

    data: function() {
        return  {
            form: new RJForm({
                id: '',
                name: '',
                fundraising_goal: '',
                end_date: '',
                video_url: '',
                description: '',
                image: '',
            }),
            rewards: [{
                image: ''
            }],
            imageStyles: {
                image: {},
            },
            flatPickrConfig: {
                wrap: true, // set wrap to true only when using 'input-group'
                altFormat: 'm/d/Y H:i K',//'M   j, Y h:i K',
                altInput: true,
                dateFormat: 'Y-m-d H:i:S',
                enableTime: true,
            },
            filesToDelete: []
        }
    },

    mounted() {
        this.setCampaignInfo();
    },

    methods: {

        setCampaignInfo () {
            this.form.id = this.campaign.id;
            this.form.name = this.campaign.name;
            this.form.fundraising_goal = this.campaign.fundraising_goal;
            this.form.end_date = this.campaign.end_date;
            this.form.video_url = this.campaign.video_url;
            this.form.description = this.campaign.description;
            this.form.image = this.campaign.image;
            this.setImageStyles();
        },

        setImageStyles () {
            _.each(this.imageStyles, (styles, field) => {
                if (this.campaign[field]) {
                    this.imageStyles[field] = {
                        "background-image": `url(${this.campaign[field]})`,
                        "background-repeat": 'no-repeat',
                        "background-size": 'cover',
                        "background-position": 'center',
                    }
                }
            });
        },

        showPreviewModal() {
            $('#campaign-complete-preview').modal('show');
        },

        publishCampaign() {
            axios.put(`${RJ.apiBaseUrl}/organization/${this.organization.id}/campaign/${this.campaign.id}/publish`)
                .then(response => {
                    this.$swal({
                        title: RJ.translations.congratulations,
                        text: RJ.translations.campaign_published,
                        type: 'success'
                    }).then(result => {
                        if (result.value) {
                            window.location.href = `${RJ.baseUrl}/campaign`;
                        }
                    });
                })
        },

        changeImage(field) {
            this.campaign[field] = URL.createObjectURL(this.$refs[field].files[0]);
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
            this.campaign[field] = null;
            this.imageStyles[field] = {
                "background": "#d8d8d8"
            };
            this.$refs[field].value = '';
        },

        submit ()  {
            this.form.startProcessing();
            // We need to gather a fresh FormData instance to POST it up to the server.
            // This is done to upload the files
            let formData = new FormData();
            // Replace # in the color codes with empty strings
            formData.append('name', this.form.name);
            formData.append('fundraising_goal', this.form.fundraising_goal);
            formData.append('end_date', this.form.end_date ? this.$root.convertBrowserToUTC(this.form.end_date) : '');
            formData.append('video_url', this.form.video_url ? this.form.video_url : '');
            formData.append('description', this.form.description);

            // If "image" is to be removed, then set its value to empty string. On server side
            // this indicates that it is to be deleted. Also, do it only if we are editing a campaign
            if (this.campaign.id && this.filesToDelete.indexOf('image') !== -1) {
                formData.append('image', '');
            }

            // Add all file uploads if a new file has been chosen
            if (this.$refs.image.files.length) {
                formData.append('image', this.$refs.image.files[0]);
            }

            let promise;

            if (this.campaign.id) {
                promise = axios.post(
                    `${RJ.apiBaseUrl}/organization/${this.organization.id}/campaign/${this.campaign.id}`,
                    formData, {headers: {'Content-Type': 'multipart/form-data'}}
                );
            } else {
                promise = axios.post(
                    `${RJ.apiBaseUrl}/organization/${this.organization.id}/campaign`,
                    formData, {headers: {'Content-Type': 'multipart/form-data'}}
                );
            }

            promise.then((response) => {
                app.$emit('campaignInfoSaved', response.data);
                this.form.finishProcessing();
            })
            .catch((error) => {
                // Close the preview modal if open
                $('#campaign-info-preview').modal('hide');

                this.form.setErrors(error.response.data.errors);
                this.form.finishProcessing();
            });
        }
    }
});
