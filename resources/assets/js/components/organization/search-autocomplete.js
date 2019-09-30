import Autocomplete from 'vuejs-auto-complete'

Vue.component('org-search-autocomplete', {
    components: {
        Autocomplete
    },

    data () {
        return {
            httpHeaders: {
                "x-csrf-token": document.head.querySelector('meta[name="csrf-token"]').content
            }
        }
    },

    methods: {
        showItem (item) {
            return `<strong>${item.name}</strong><span>${item.address}</span>`;
        },

        showOrganization (result) {
            window.location = `${RJ.baseUrl}/${result.selectedObject.slug}`;
        },

        showSearchResults (item) {
            return `<strong>${item.name}</strong><span>${item.type}</span>`;
        },

        showOrganizationForAdmin (result) {
            if (result.selectedObject.type == "Organization") {
                window.location = `${RJ.baseUrl}/admin/impersonate/${result.selectedObject.originalId}`;
            } else {
                window.location = `${RJ.baseUrl}/admin/campaign/${result.selectedObject.originalId}/details`;
            }
        },
    },
});
