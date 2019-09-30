// import { TableComponent, TableColumn } from 'vue-table-component';

Vue.component('org-accountuser', {
    props: [
        'organization',
    ],

    components: {
        //TableComponent,
        //TableColumn
    },

    data: function() {
        return  {
            form: new RJForm({
                first_name: '',
                last_name: '',
                email: '',
                role: 'campaign-admin',
                campaign_ids: []
            }),
            accountUsers: {},
            invitedUsers: {},
            selected: [],
            showAddUserForm: false,
            editForm: false,
            roles: this.$root.getUserRoles(),
            userExists: false,
            campaignList: {},
            allSelected: false,
            clicked: []
        }
    },

    mounted() {
        // Get the list of account users
        this.getAccountUsers();
        // Get the list of pending invitees
        this.getPendingUsers();
        // Get the list of campaigns for current organization.
        this.getCampaigns();
    },

    methods: {
        getUserRoles() {
            return {
                'owner': 'Account Owner - Can create and edit all users, campaigns, organization profile and billing information',
                'admin': 'Organization Admin - Can create and edit all users, campaigns and organization',
                'campaign-admin': 'Campaign Admin - Can edit assigned campaigns'
            }
        },

        getRoleOptionLabel (role) {
            return this.roles[role];
        },

        getAccountUsers() {
            axios.get(`${RJ.apiBaseUrl}/organization/${this.organization.id}/account-users`)
                .then((response) => this.accountUsers = response.data );
        },

        toggleAddUserForm(editForm) {
            if (typeof editForm == 'undefined' ||  editForm != 1) {
                this.form.first_name = '';
                this.form.last_name = '';
                this.form.email = '';
                this.form.role = 'campaign-admin';
                this.form.campaign_ids = [];
            }
            this.showAddUserForm = !this.showAddUserForm;
            this.editForm = false;
        },

        getPendingUsers() {
            axios.get(`${RJ.apiBaseUrl}/organization/${this.organization.id}/invited-users`)
                .then(response => {
                    this.invitedUsers = response.data;
                });
        },

        resendInvitationEmail (index) {
            this.clicked.push(index);
            this.$root.resendInvitation(this.organization.id, this.invitedUsers[index].id)
                .then(response => {
                    this.$swal({
                        title: RJ.translations.sent,
                        text: RJ.translations.invitation_sent,
                        type: 'success'
                    });
                });
        },

        removeInvitation (index) {
            this.$root.deleteInvitation(this.organization.id, this.invitedUsers[index].id)
                .then(response => {
                    // Remove the invited user from invitedUsers array
                    this.invitedUsers.splice(index, 1);
                });
        },

        removeAccountUser (index) {
            this.$root.deleteAccountUser(this.organization.id, this.accountUsers[index].user.id)
                .then(response => {
                    // Remove the invited user from invitedUsers array
                    this.accountUsers.splice(index, 1);
                });
        },

        submit() {
            //this.form.campaign_ids.push(this.campaign.id);
            RJ.post(`${RJ.apiBaseUrl}/organization/${this.organization.id}/invite`, this.form)
                .then(response => {
                    // Get the list of account users
                    this.getAccountUsers();
                    // Get the list of pending invitees
                    this.getPendingUsers();

                    this.showAddUserForm = false;

                    this.form.first_name = this.form.last_name = this.form.email = ''
                    this.form.role = 'campaign-admin';
                    this.form.campaign_ids = [];
                    this.allSelected = false;
                });
        },

        checkEmail () {
            axios.post(`${RJ.apiBaseUrl}/organization/${this.organization.id}/check-email`, {email: this.form.email})
                .then(response => {
                    if (_.isUndefined(response.data.user)) {
                        this.userExists = false;
                    } else {
                        this.userExists = true;
                        this.form.first_name = response.data.user.first_name;
                        this.form.last_name = response.data.user.last_name;
                        this.form.role = response.data.role;
                    }
                });
        },

        getCampaigns () {
            this.$root.getOrganizationCampaigns(this.organization.id)
                .then(response => {
                    this.campaignList = response.data;
                });
        },

        selectAll: function() {
            this.form.campaign_ids = [];

            if (! this.allSelected) {
                _.each(this.campaignList, (camp_name, camp_id) => {
                    this.form.campaign_ids.push(camp_id);
                });
            }
        },

        editAccountUser (index) {
            this.toggleAddUserForm(1);

            this.editForm = true;

            this.form.id = this.accountUsers[index].user.id;
            this.form.first_name = this.accountUsers[index].user.first_name;
            this.form.last_name = this.accountUsers[index].user.last_name;
            this.form.email = this.accountUsers[index].user.email;
            this.form.role = this.accountUsers[index].role;

            if (this.form.role == 'campaign-admin' && (this.accountUsers[index].campaigns.length > 0)) {
                _.each(this.accountUsers[index].campaigns, (campaign) => {
                    this.form.campaign_ids.push(campaign.id);
                });
            }
        },

        update () {
            RJ.put(`${RJ.apiBaseUrl}/organization/${this.organization.id}/user/${this.form.id}/update-user`,
                    this.form)
                .then(response => {
                    this.$swal({
                        title: 'Updated',
                        text: 'Account User Updated',
                        type: 'success'
                    })
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
    }
});
