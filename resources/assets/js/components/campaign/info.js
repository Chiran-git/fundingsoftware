import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import VueSimplemde from 'vue-simplemde'
import 'simplemde/dist/simplemde.min.css'

Vue.component('campaign-info', {
    props: [
        'organization',
        'campaign'
    ],

    mixins: [
        require('../../mixins/campaign-progress'),
        require('../../mixins/crop-image')
    ],

    components: {
        flatPickr,
        VueSimplemde
    },

    data: function() {

        const descriptionMdeConfig = _.merge(this.$root.defaultMdeConfig, {
            placeholder: RJ.translations.campaign_description_placeholder
        });

        return  {
            form: new RJForm({
                id: '',
                name: '',
                fundraising_goal: '',
                end_date: '',
                video_url: '',
                description: '',
                image: '',
                campaign_category_id: '',
            }),
            categories:[],
            imageStyles: {
                image: {},
            },
            flatPickrConfig: {
                wrap: true, // set wrap to true only when using 'input-group'
                altFormat: 'm/d/Y H:i K',//'M	j, Y h:i K',
                altInput: true,
                dateFormat: 'Y-m-d H:i:S',
                enableTime: true,
                altInputClass: 'input__cale',
                minDate: 'today'
            },
            filesToDelete: [],
            descriptionMdeConfig: descriptionMdeConfig,
            originalImageFiles: {
                image: {
                    requiredWidth: 700,
                    requiredHeight: 470,
                }
            }
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
            if (typeof this.campaign.campaign_category !== 'undefined') {
                this.form.campaign_category_id = this.campaign.campaign_category.id ? this.campaign.campaign_category.id : "";
            }
            this.setImageStyles();

            let categoriesPromise = this.$root.getCategories()
                .then(response => {
                    this.categories = response.data
                });
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

        changeImage (field) {
            //this.campaign[field] = URL.createObjectURL(this.$refs[field].files[0]);
            this.initializeCrop(this.$refs[field].files[0], field, this.campaign);
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

        selectReward() {
            window.location.href = `${RJ.baseUrl}/${this.organization.slug}/${this.campaign.slug}/donate`;
        },

        getCategoryOptionLabel (category) {
            if (typeof this.categories[category] !== 'undefined') {
                return this.categories[category].name;
            }
        },

        submit ()  {
            this.form.startProcessing();
            // We need to gather a fresh FormData instance to POST it up to the server.
            // This is done to upload the files
            let formData = new FormData();
            // Replace # in the color codes with empty strings
            formData.append('name', this.form.name);
            formData.append('campaign_category_id', this.form.campaign_category_id);
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
                let image = this.croppedImageFiles.image || this.$refs.image.files[0];
                formData.append('image', image, this.$refs.image.files[0].name);
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
                this.$root.sMessage(RJ.translations.saved_successfully);
            })
            .catch((error) => {
                // Close the preview modal if open
                $('#campaign-info-preview').modal('hide');
                this.form.setErrors(error.response.data.errors);
                this.$root.eMessage(RJ.translations.save_error);
                this.form.finishProcessing();
            });
        },

    },

    computed : {
        categoryOptions () {
            let options = [];
            _.each(this.categories, (label, category) => {
                options.push(category);
            });
            return options;
        },
    }

});
