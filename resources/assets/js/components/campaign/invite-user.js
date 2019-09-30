Vue.component('campaign-invite-user', {
    props:
        [
            'campaign',
            'organization',
            'campaignUsers',
        ],

    data() {
        return {
            form: new RJForm({
                email: '',
                first_name: '',
                last_name: '',
                role: 'campaign-admin',
                campaign_ids: []
            }),
            roles: this.$root.getUserRoles(),
            userExists: false,
        }
    },

    mounted() {
        this.$parent.getCampaignAdmins();

        // Call vue js resetForm function on modal hide.
        $(this.$refs.inviteUserModal).on("hidden.bs.modal", this.resetForm);
    },

    methods: {

        showInviteModal () {
            $('#modal-add-user').modal('show');
        },

        closeInviteModal () {
            $('#modal-add-user').modal('hide');

            $(".modal").on("hidden.bs.modal", function(){
                $('.modal form')[0].reset();
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

        goToPayout()
        {
            app.$emit('invitationCreated');
        },

        submit() {
            this.form.campaign_ids.push(this.campaign.id);
            RJ.post(`${RJ.apiBaseUrl}/organization/${this.organization.id}/invite`, this.form)
                .then(response => {
                    this.$root.sMessage(RJ.translations.saved_successfully);
                    this.$parent.getCampaignAdmins();
                    this.closeInviteModal();
                })
                .catch(error => {
                    this.$root.eMessage(RJ.translations.save_error);
                });
        },

        getRoleOptionLabel (role) {
            return this.roles[role];
        },

        resetForm () {
            this.form.email = this.form.first_name = this.form.last_name = '';
            this.form.role = 'campaign-admin';
            this.form.campaign_ids = [];
            this.userExists = false;
            $("#modal-add-user span.invalid-feedback").attr('style', 'display:none !important');
            $("#modal-add-user input.is-invalid").removeClass('is-invalid');
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
