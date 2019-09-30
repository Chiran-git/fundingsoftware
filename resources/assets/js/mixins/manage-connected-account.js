/**
 * Export the root Spark application.
 */
module.exports = {

    data () {
        return  {
            showNewAccountModal: false,
            account_exist: false,
            accountForm: new RJForm({
                id: '',
                nickname: '',
                is_default: 0,
            }),
            currentUrl: "",
        }
    },

    mounted () {
        // If we have account_exist in the URL query, then it means it is already created
        if (! _.isUndefined(this.$route.query.account_exist)) {
            this.account_exist = true;
        }
    },

    methods: {
        getAccountFromId (id) {
            return _.find(this.accounts,  account => {
                return account.account_id == id;
            });
        },

        editAccount (account) {
            // Hide the account list modal if it is currently shown
            $('#connected-account-list').modal('hide');

            this.accountForm.id = account.account_id;
            this.accountForm.nickname = account.account_nickname;
            this.accountForm.is_default = account.is_default;
            $('#connected-account-edit').modal('show');
        },

        confirmDeleteAccount (index) {
            this.$swal({
                title: RJ.translations.confirm_delete_title,
                html: RJ.translations.confirm_delete_connected_account,
                type: 'warning',
                showCancelButton: true
            }).then(result => {
                if (result.value) {
                    this.deleteAccount(this.accounts[index].account_id)
                        .then(response => {
                            this.$root.sMessage(RJ.translations.deleted_successfully);
                            // Remove the account from accounts array
                            this.accounts.splice(index, 1);
                        }).catch(error => {
                            let message = RJ.translations.default_error_message;
                            if (! _.isUndefined(error.message)) {
                                message = error.message;
                            }
                            this.$root.eMessage(message);
                        });
                }
            });
        },

        deleteAccount (id) {
            return axios.delete(`${RJ.apiBaseUrl}/organization/${this.organization.id}/connected-account/${id}/delete`);
        },

        submitAccount () {
            RJ.put(`${RJ.apiBaseUrl}/organization/${this.organization.id}/connected-account/${this.accountForm.id}`, this.accountForm)
                .then(response => {
                    // Update the account info
                    let account = this.getAccountFromId(response.account_id);
                    // Since the account is returned by reference, below will simply
                    // overwrite the account in this.accounts with the latest values
                    account.account_nickname = response.account_nickname;
                    account.is_default = response.is_default;

                    // Hide the account edit modal and set the new account setup flag to false
                    $('#connected-account-edit').modal('hide');
                    this.showNewAccountModal = false;
                    // Show the account list modal if it is present
                    // This comes up on campaign edit screen only
                    //$('#connected-account-list').modal('show');
                }).catch(error => {
                    let message = RJ.translations.default_error_message;
                    if (! _.isUndefined(error.message)) {
                        message = error.message;
                    }
                    this.$root.eMessage(message);
                });
        },

        closeModal () {
            // Hide the account edit modal and set the new account setup flag to false
            $('#connected-account-edit').modal('hide');
            //delete account_exist query param
            let query = Object.assign({}, this.$route.query);
            delete query.account_exist;
            this.$router.replace({ query });
            let currentObj = this;
            $('#connected-account-edit').on("hidden.bs.modal", function () {
                currentObj.account_exist = false;
                currentObj.showNewAccountModal = false;
            });

        }
    },
}
