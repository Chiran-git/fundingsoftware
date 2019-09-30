Vue.component('connected-account-list', {

    props: [
        'organization'
    ],

    mixins: [
        require('../../mixins/manage-connected-account'),
    ],

    data: function() {
        return  {
            accounts: [],
        }
    },

    mounted() {
        this.fetchConnectedAccounts()
            .then(() => {
                // If we have account id in the URL query, then it means it is a newly created
                // account so let's ask them to change its nickname
                if (! _.isUndefined(this.$route.query.connected_account_id)) {
                    this.showNewAccountModal = true;
                    let account = this.getAccountFromId(this.$route.query.connected_account_id);
                    this.editAccount(account);
                }
            });
    },

    methods: {
        fetchConnectedAccounts () {
            return this.$root.getConnectedAccounts(this.organization.id)
                .then(response => {
                    this.accounts = response.data;
                });
        },
    }
});
