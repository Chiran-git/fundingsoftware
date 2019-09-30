Vue.component('campaign-pay-out', {
    props: [
        'organization',
        'campaign',
        'accounts',
    ],

    mixins: [
        require('../../mixins/manage-connected-account'),
    ],

    data () {
        return  {
            form: new RJForm({
                payout_method: this.campaign.payout_method,
                payout_connected_account_id: (this.campaign.payout_connected_account && this.campaign.payout_connected_account.id ) ? this.campaign.payout_connected_account.id : '',
                payout_schedule: this.campaign.payout_schedule,
                payout_name: this.campaign.payout_name ? this.campaign.payout_name : ( this.$root.user ? (this.$root.user.first_name + ' ' + this.$root.user.last_name) : ''),
                payout_organization_name: this.campaign.payout_organization_name ? this.campaign.payout_organization_name : this.organization.name,
                payout_address1: this.campaign.payout_address1 ? this.campaign.payout_address1 : this.organization.address1,
                payout_address2: this.campaign.payout_address2 ? this.campaign.payout_address2 : this.organization.address2,
                payout_city: this.campaign.payout_city ? this.campaign.payout_city : this.organization.city,
                payout_state: this.campaign.payout_state ? this.campaign.payout_state : this.organization.state,
                payout_zipcode: this.campaign.payout_zipcode ? this.campaign.payout_zipcode : this.organization.zipcode,
                payout_country_id: this.campaign.payout_country_id ? this.campaign.payout_country_id : this.organization.country.id,
                payout_payable_to: this.campaign.payout_payable_to ? this.campaign.payout_payable_to : this.organization.name,
            }),
            // We are duplicating the payout data in form
            // into this object. We want to show the data saved
            // in db in the payout mailing address instead of what is in the form
            payoutInfo: {},
            states: {},
        }
    },

    mounted () {
        // Get the list of states
        this.$root.getStates(this.organization.country.iso_code)
            .then(response => {
                this.states = response.data;
            });

        // If we have account id in the URL query, then it means we need to show
        // the Direct Deposit option selected
        if (! _.isUndefined(this.$route.query.connected_account_id)) {
            this.setUpNewBankAccount(this.$route.query.connected_account_id);
        }

        this.setPayoutInfo();

        this.updateSchedule();
    },

    methods: {
        setUpNewBankAccount (accountId) {
            this.form.payout_method = 'bank';
            this.form.payout_connected_account_id = accountId;
            this.showNewAccountModal = true;
            let account = this.getAccountFromId(accountId);
            this.accountForm.id = accountId;
            this.accountForm.nickname = account.account_nickname;
            this.accountForm.is_default = account.is_default;
            $('#connected-account-edit').modal('show');
        },

        setPayoutInfo () {
            this.payoutInfo = _.clone(this.form);
        },

        getPayoutAccountOptionLabel (id) {
            let optionAccount = this.getAccountFromId(id);
            return optionAccount.account_nickname;
        },

        showPayoutAccountsList () {
            // We don't want New account modal but edit account modal
            this.showNewAccountModal = false;
            $('#connected-account-list').modal('show');
        },

        showMailAddressPreview (modalId) {
            $('#' + modalId).modal('show');

            if (this.form.errors.errors.payout_name ) {
                 delete this.form.errors.errors.payout_name;
            }
            if (this.form.errors.errors.payout_organization_name) {
                delete this.form.errors.errors.payout_organization_name;
            }
            if (this.form.errors.errors.payout_address1) {
                delete this.form.errors.errors.payout_address1;
            }
            if (this.form.errors.errors.payout_city) {
                delete this.form.errors.errors.payout_city;
            }
            if (this.form.errors.errors.payout_zipcode) {
                delete this.form.errors.errors.payout_zipcode;
            }

            this.form.payout_name = this.payoutInfo.payout_name;
            this.form.payout_organization_name = this.payoutInfo.payout_organization_name;
            this.form.payout_address1 = this.payoutInfo.payout_address1;
            this.form.payout_city = this.payoutInfo.payout_city;
            this.form.payout_zipcode = this.payoutInfo.payout_zipcode;
        },

        submit (action = null) {
            RJ.put(`${RJ.apiBaseUrl}/organization/${this.organization.id}/campaign/${this.campaign.id}/payout`, this.form)
                .then(response => {
                    this.$root.sMessage(RJ.translations.saved_successfully);
                    this.$parent.closePreview('add-check-mail-address');
                    this.$parent.closePreview('add-check-mail-payable-to');
                    // Set the latest form info into the payoutInfo variable
                    this.setPayoutInfo();
                    app.$emit('campaignPayoutSaved');
                    if (action == 'edit') {
                        window.location.href = `${RJ.baseUrl}/campaign/${this.campaign.id}/details`;
                    }
                })
                .catch((error) => {
                    this.$root.eMessage(RJ.translations.save_error);
                    if (this.form.payout_method == 'check') {
                        if (this.form.errors.has('payout_payable_to')) {
                            this.$parent.showPreview('add-check-mail-payable-to');
                        } else if (this.form.errors.has('payout_name')
                            || this.form.errors.has('payout_organization_name')
                            || this.form.errors.has('payout_address1')
                            || this.form.errors.has('payout_city')
                            || this.form.errors.has('payout_state')
                            || this.form.errors.has('payout_zipcode')) {
                            this.$parent.showPreview('add-check-mail-address');
                        }
                    }
                });
        },

        update () {
            let action = 'edit';
            this.submit(action);
        },

        updateSchedule () {
            if (this.form.payout_method == 'bank') {
                this.form.payout_schedule = 'daily';
            } else {
                this.form.payout_schedule = 'monthly';
            }
        }
    },

    computed: {
        accountOptions () {
            let options = [];

            _.each(this.accounts, account => {
                options.push(account.account_id);
            });

            return options;
        }
    },

});
