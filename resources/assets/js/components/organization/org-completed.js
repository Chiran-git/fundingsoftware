Vue.component('org-completed', {
    props: ['organization'],

    data: function() {
        return  {
            form: new RJForm({

            }),
        }
    },

    mounted() {

    },

    methods: {
        submit() {
            var organizationId = (typeof this.$parent.createdOrganization.id != 'undefined') ? this.$parent.createdOrganization.id : this.organization.id;
            RJ.put(`${RJ.apiBaseUrl}/organization/${organizationId}/profile`, this.form)
                .then(response => {
                    //app.$emit('orgProfileSaved');
                });
        },

        createCampaign() {
            var url = `${RJ.baseUrl}/campaign/create`;
            if (typeof this.$parent.createdOrganization != 'undefined') {
                url = `${RJ.baseUrl}/admin/organization/${this.$parent.createdOrganization.id}/campaign/create`;
            }
            window.location.href = url;
        }
    },
});
