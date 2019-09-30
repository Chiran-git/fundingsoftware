Vue.component('camp-reward', {
    props: [
        'organization',
        'campaign',
        'rewards',
    ],

    mixins: [
        require('../../mixins/campaign-progress'),
        require('../../mixins/crop-image')
    ],

    data: function() {
        return  {
            forms: [
                this.getEmptyRewardForm()
            ],
            imageStyles: [{
                image: {},
            }],
            filesToDelete: [],
            rewardsToDelete: [],
            originalImageFiles: {
                image: [{
                    requiredWidth: 320,
                    requiredHeight: 200,
                }]
            }
        }
    },

    mounted() {
        // If we have a campaign and at least 1 reward, then set its rewards in the form
        if (this.campaign.id && ! _.isUndefined(this.rewards[0]) && ! _.isUndefined(this.rewards[0].id)) {
            this.setRewardsForm();
        }

        this.setImageStyles();
    },

    methods: {

        getEmptyRewardForm () {
            return new RJForm({
                title: '',
                reward_image: '',
                min_amount: '',
                quantity: '',
                description: ''
            });
        },

        setRewardsForm () {
            // Reset the forms array to empty
            this.forms = [];
            this.imageStyles = [];
            this.originalImageFiles.image = [];

            _.each(this.rewards, reward => {
                let form = new RJForm({
                    id: reward.id,
                    title: reward.title,
                    reward_image: reward.image,
                    min_amount: reward.min_amount,
                    quantity: reward.quantity,
                    description: reward.description
                });

                this.imageStyles.push({
                    image: {},
                });

                this.forms.push(form);

                this.originalImageFiles.image.push({
                    requiredWidth: 320,
                    requiredHeight: 200,
                });
            });
        },

        addRewardForm () {
            this.forms.push(this.getEmptyRewardForm());
            this.imageStyles.push({
                image: {},
            });
            this.rewards.push({
                image: ''
            });
            this.originalImageFiles.image.push({
                requiredWidth: 320,
                requiredHeight: 200,
            });
        },

        confirmRemoveRewardForm (index) {
            if (! _.isUndefined(this.rewards[index].id) && this.rewards[index].id) {
                axios.get(`${RJ.apiBaseUrl}/organization/${this.organization.id}/campaign/${this.campaign.id}/reward/${this.rewards[index].id}`)
                    .then(response => {
                        if (response.data.quantity_rewarded) {
                            this.$swal({
                                title: RJ.translations.reject_delete_reward_title,
                                text: RJ.translations.reject_delete_reward_text,
                                type: 'error',
                            });
                        } else {
                            this.confirmRemove(index);
                        }
                    });
            } else {
                this.confirmRemove(index);
            }
        },

        confirmRemove (index) {
            this.$swal({
                title: RJ.translations.confirm_delete_title,
                text: RJ.translations.confirm_delete_reward,
                type: 'warning',
                showCancelButton: true
            }).then(result => {
                if (result.value) {
                    this.removeRewardForm(index);
                }
            });
        },

        removeRewardForm (index) {
            this.forms.splice(index, 1);
            this.imageStyles.splice(index, 1);
            if (! _.isUndefined(this.rewards[index].id)) {
                this.rewardsToDelete.push(this.rewards[index].id);
            }

            this.originalImageFiles.image.splice(index, 1);

            this.rewards.splice(index, 1);
            // If this was the last reward to be removed,
            // then add an empty reward form
            if (! this.forms.length) {
                this.addRewardForm();
            }
        },

        setImageStyles () {
            _.each(this.imageStyles, (rewardStyles, index) => {
                _.each(rewardStyles, (styles, field) => {
                    if (! _.isUndefined(this.rewards[index]) && ! _.isUndefined(this.rewards[index][field]) && this.rewards[index][field]) {
                        this.imageStyles[index][field] = {
                            "background-image": `url(${this.rewards[index][field]})`,
                            "background-repeat": 'no-repeat',
                            "background-size": 'cover',
                            "background-position": 'center',
                        }
                    }
                });
            });
        },

        changeImage(field, index) {
            // this.rewards[index][field] = URL.createObjectURL(this.$refs[field][index].files[0]);
            this.initializeCrop(this.$refs[field][index].files[0], field, this.rewards[index], index);
            // Remove the field name from files to delete if present
            // as we are actually uploading a new image
            _.remove(this.filesToDelete, fileField => {
                return fileField === `${index}_${field}`;
            });

            this.setImageStyles();
        },

        openFileDialog(field, index) {
            this.$refs[field][index].click();
        },

        confirmDeleteUploadedFile (field, index) {
            this.$swal({
                title: RJ.translations.confirm_delete_title,
                text: RJ.translations.confirm_delete_image,
                type: 'warning',
                showCancelButton: true
            }).then(result => {
                if (result.value) {
                    this.deleteUploadedFile(field, index);
                }
            });
        },

        deleteUploadedFile (field, index) {
            this.filesToDelete.push(`${index}_${field}`);
            this.rewards[index][field] = null;
            this.imageStyles[index][field] = {
                "background": "#d8d8d8"
            };
            this.$refs[field][index].value = '';
        },

        submit () {
            let promises = [];
            let countSubmits = 0;

            // We will put the first form in processing state
            this.forms[0].startProcessing();

            _.each(this.forms, (form, index) => {

                // If all fields are empty, then we will skip saving this reward
                if (_.isEmpty(form.title) && _.isEmpty(form.min_amount) && _.isEmpty(form.description)
                    && _.isEmpty(form.quantity) && _.isEmpty(form.image)) {
                        return;
                }

                countSubmits++;
                // Since we want to add multiple rewards, lets push it to promise array
                promises.push(this.submitForm(form, index));
            });

            // If we have rewards to delete
            if (this.rewardsToDelete.length) {
                let deletePromise;
                _.each(this.rewardsToDelete, rewardId => {
                    deletePromise = axios.delete(
                        `${RJ.apiBaseUrl}/organization/${this.organization.id}/campaign/${this.campaign.id}/reward/${rewardId}`
                    );
                });
                promises.push(deletePromise);
            }

            // If no rewards, then fire the event anyways so that user can move to next tab
            if (countSubmits === 0) {
                app.$emit('campaignRewardSaved');
                this.forms[0].finishProcessing();
            } else {
                // After all axios promises are finished successfully, we will emit the campaignRewardSaved event
                axios.all(promises).then(() => {
                    app.$emit('campaignRewardSaved');
                    this.$root.sMessage(RJ.translations.saved_successfully);
                    this.forms[0].finishProcessing();
                }).catch(() => {
                    this.forms[0].finishProcessing();
                    // Close the preview modal if open
                    $('#campaign-rewards-preview').modal('hide');
                    this.$root.eMessage(RJ.translations.save_error);
                });
            }
        },

        submitForm (form, index) {
            let thisForm = form;
            let thisIndex = index;

            // We need to gather a fresh FormData instance to POST it up to the server.
            // This is done to upload the files
            let formData = new FormData();
            // Replace # in the color codes with empty strings
            formData.append('title', thisForm.title || '');
            formData.append('min_amount', thisForm.min_amount || '');
            formData.append('quantity', thisForm.quantity || '' )
            formData.append('description', thisForm.description || '');

            // If "image" is to be removed, then set its value to empty string. On server side
            // this indicates that it is to be deleted. Also, do it only if we are editing a reward
            if (this.rewards[thisIndex].id && this.filesToDelete.indexOf(`${thisIndex}_image`) !== -1) {
                formData.append('image', '');
            }

            // Add all file uploads if a new file has been chosen
            if (this.$refs.image[thisIndex].files.length) {
                let image = this.croppedImageFiles.image[thisIndex] || this.$refs.image[thisIndex].files[0];
                formData.append('image', image, this.$refs.image[thisIndex].files[0].name);
            }

            let promise;

            if (this.rewards[thisIndex].id) {
                promise = axios.post(
                    `${RJ.apiBaseUrl}/organization/${this.organization.id}/campaign/${this.campaign.id}/reward/${this.rewards[thisIndex].id}`,
                    formData, {headers: {'Content-Type': 'multipart/form-data'}}
                );
            } else {
                promise = axios.post(
                    `${RJ.apiBaseUrl}/organization/${this.organization.id}/campaign/${this.campaign.id}/reward`,
                    formData, {headers: {'Content-Type': 'multipart/form-data'}}
                );
            }

            promise.then((response) => {
                Vue.set(this.rewards, thisIndex, response.data);
                thisForm.setErrors();
            })
            .catch((error) => {
                thisForm.setErrors(error.response.data.errors);
            });

            return promise;
        }
    }
});
