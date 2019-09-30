Vue.component('switch-organization', {

    data: function() {
        return  {
            organizations: [],
            search: '',
            filteredData: {}
        }
    },

    mounted() {
        this.getOrganizations();
    },

    methods: {
        getOrganizations() {
            axios.get(`${RJ.apiBaseUrl}/admin/organizations`)
                .then(response => {
                    this.organizations = response.data.data;
                });
        },

        clickRow(id) {
            window.location.href = `${RJ.baseUrl}/admin/impersonate/${id}`;
        },

        selectOrganizationForCampaign(id) {
            window.location.href = `${RJ.baseUrl}/admin/organization/${id}/campaign/create`;
        },

        selectOrganizationForDonation(id) {
            window.location.href = `${RJ.baseUrl}/admin/organization/${id}/donation/create`;
        }
    },

    computed: {
        filteredOrganizations: function() {
            return this.organizations.filter( (organization) => {
                return organization.name.toLowerCase().match(this.search.toLowerCase());
            });
        }
    }

});
