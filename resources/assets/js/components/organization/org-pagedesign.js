Vue.component('org-pagedesign', {
    props: ['organization'],

    mixins: [
        require('../../mixins/crop-image')
    ],

    data: function() {
        return  {
            form: new RJForm({
                cover_image: false,
                logo: false,
                primary_color: this.organization.primary_color || '',
                secondary_color: this.organization.secondary_color || '',
                appeal_headline: '',
                appeal_message: '',
                appeal_photo: '',
            }),
            imageStyles: {
                cover_image: {},
                logo: {},
                appeal_photo: {},
            },
            campaigns: {},
            showCampaignList: false,
            originalImageFiles: {
                cover_image: {
                    requiredWidth: 1600,
                    requiredHeight: 400,
                },
                logo: {
                    requiredWidth: 150,
                    requiredHeight: 150,
                },
                appeal_photo: {
                    requiredWidth: 150,
                    requiredHeight: 150,
                }
            },
            primary_color: '',
            secondary_color: '',
            orgBackgroundColor: {},
            newOrganization: {}
        }
    },

    mounted() {
        if (this.organization.id) {
            this.setDesigns();
            this.setImageStyles();
            this.setCampaignList();
            this.setbackgroundColorStyle();
        }
    },

    methods: {

        setDesigns() {
            this.form.primary_color = this.organization.primary_color || '';
            this.form.secondary_color = this.organization.secondary_color || '';
            this.form.appeal_headline = this.organization.appeal_headline || '';
            this.form.appeal_message = this.organization.appeal_message || '';

            this.primary_color = this.organization.primary_color || '';
            this.secondary_color = this.organization.secondary_color || '';
        },

        setImageStyles() {
            _.each(this.imageStyles, (styles, field) => {
                if (this.organization[field]) {
                    this.imageStyles[field] = {
                        "background-image": `url(${this.organization[field]})`,
                        "background-repeat": 'no-repeat',
                        "background-size": 'cover',
                        "background-position": 'center',
                    }
                }
            });
        },

        changeImage(field) {

            // this.organization[field] = URL.createObjectURL(this.$refs[field].files[0]);
            
            // Remove the field name from files to delete if present
            // as we are actually uploading a new image
            let fileName = this.$refs[field].files[0].name;
            let fileExtension = fileName.replace(/^.*\./, '').toLowerCase().trim();

            if (fileExtension == 'jpeg' || fileExtension == 'jpg' || fileExtension == 'bmp' ||
                fileExtension == 'png' || fileExtension == 'gif' ||
                fileExtension == 'svg' || fileExtension == 'webp') {
                
                this.initializeCrop(this.$refs[field].files[0], field, this.organization);
                
            } else {
                let error = `Please upload image (Only jpeg, png, bmp, gif, svg, or webp supported).`;
                this.$root.eMessage(error);
                this.resetFileInput(field);
                
            }

            this.setImageStyles();
        },

        showPreview () {
            this.setCreatedOrganizationdata();
            $('#modal-template-preview').modal('show');
        },

        closePreview() {
            $('#modal-template-preview').modal('hide');
        },

        openFileDialog(field) {
            this.$refs[field].click();
        },

        submit() {
            this.form.startProcessing();
            // We need to gather a fresh FormData instance to POST it up to the server.
            // This is done to upload the files
            let formData = new FormData();
            // Replace # in the color codes with empty strings
            formData.append('primary_color', this.form.primary_color.replace('#', ''));
            formData.append('secondary_color', this.form.secondary_color.replace('#', ''));
            formData.append('appeal_headline', this.form.appeal_headline);
            formData.append('appeal_message', this.form.appeal_message);

            // Add all file uploads if a new file has been chosen
            if (this.$refs.cover_image.files.length) {
                let coverImage = this.croppedImageFiles.cover_image || this.$refs.cover_image.files[0];
                formData.append('cover_image', coverImage, this.$refs.cover_image.files[0].name);
            }

            if (this.$refs.logo.files.length) {
                let logo = this.croppedImageFiles.logo || this.$refs.logo.files[0];
                formData.append('logo', logo, this.$refs.logo.files[0].name);
            }

            if (this.$refs.appeal_photo.files.length) {
                let appealPhoto = this.croppedImageFiles.appeal_photo || this.$refs.appeal_photo.files[0];
                formData.append('appeal_photo', appealPhoto, this.$refs.appeal_photo.files[0].name);
            }

            if (this.organization.id) {
                var organizationId = this.organization.id;
            } else if(this.$parent.createdOrganization.id) {
                var organizationId = this.$parent.createdOrganization.id;
            }

            if (organizationId) {
                //post data to server
                axios.post(
                    `${RJ.apiBaseUrl}/organization/${organizationId}/design`,
                        formData, {headers: {'Content-Type': 'multipart/form-data'}})
                        .then((response) => {
                            this.$root.sMessage(RJ.translations.saved_successfully);
                            this.form.finishProcessing();

                            if (! this.organization.id && this.$parent.createdOrganization.id) {
                                // Move to next tab
                                this.$parent.currentStep = 3;
                                this.$parent.stepsCompleted = 2;
                                this.$root.refreshOrganization(this.$parent.createdOrganization.id);
                                this.$parent.scrollToTop();
                            } else {
                                app.$emit('orgDesignSaved');
                            }
                        })
                        .catch((error) => {
                            this.$root.eMessage(RJ.translations.save_error);
                            this.form.setErrors(error.response.data.errors);
                            this.form.finishProcessing();
                        });
            }
        },

        setCampaignList (page) {
            // We will not show child components till we get the campaign and other information
            this.showCampaignList = false;

            let promises = [];

            if (typeof page === 'undefined') {
                page = 1;
            }

            // Load the campaigns
            let campaignsPromise = this.$root.getCampaigns(this.organization.id, page)
                .then(response => {
                    this.campaigns = response.data;

                    _.each(this.campaigns.data, (campaign, index) => {
                        if (campaign.end_date) {
                            this.campaigns.data[index].end_date = this.$root.convertUTCToBrowser(campaign.end_date);
                            this.campaigns.data[index].days_left = this.$root.getDateDifferenceInDays(null, this.campaigns.data[index].end_date);
                        }
                        if (campaign.funds_raised == null) {
                            campaign.funds_raised = 0;
                        }
                        this.campaigns.data[index].donation_percent = Math.floor((campaign.funds_raised / campaign.fundraising_goal) * 100);
                        this.campaigns.data[index].fundraising_goal = this.$root.formatAmount(this.campaigns.data[index].fundraising_goal, this.organization.currency.symbol);
                        this.campaigns.data[index].funds_raised = this.$root.formatAmount(this.campaigns.data[index].funds_raised, this.organization.currency.symbol);
                    });

                });

            promises.push(campaignsPromise);

            // When all axios requests are finished,
            // show the child components
            axios.all(promises)
                .then(() => {
                    this.showCampaignList = true;
                })

        },

        setbackgroundColorStyle () {
            let primary_color = this.form.primary_color || this.organization.primary_color;
            let secondary_color = this.form.secondary_color || this.organization.secondary_color;
            this.orgBackgroundColor = {
                "background": primary_color,
                "border-top": "0.9375rem solid " + secondary_color,
                "border-bottom": "0.9375rem solid " + secondary_color,
                "border-left": "0px",
                "border-right": "0px"
            }
        },

        setCreatedOrganizationdata () {
            if (typeof this.$parent.createdOrganization != "undefined") {
                this.newOrganization = this.$parent.createdOrganization;
            }
        }
    },

    watch: {
        primary_color (value) {
            this.form.primary_color = value;
            this.setbackgroundColorStyle ();
            this.$parent.setStepsCompleted();
        },
        secondary_color (value) {
            this.form.secondary_color = value;
            this.setbackgroundColorStyle ();
            this.$parent.setStepsCompleted();
        }
    }
});
