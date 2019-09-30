Vue.component('pagination', require('laravel-vue-pagination'));

import { TableComponent, TableColumn } from 'vue-table-component';

Vue.component('table-component', TableComponent);
Vue.component('table-column', TableColumn);

Vue.component('admin-list', {

    props: [
        'user',
    ],

    data: function() {

        return  {
            adminUsers: {},
            status: '',
            startDate: '',
            endDate: '',
            resetPage: false,
        }
    },

    mounted() {
        this.getAdminUsers();
    },

    methods: {
        async getAdminUsers({ page, filter, sort }) {

            if (typeof page === 'undefined' || this.resetPage) {
                page = 1;
                this.resetPage = false;
            }

            var queryParams = {params: {'page': page, 'sort': sort, 'start_date': this.startDate, 'end_date': this.endDate}};

            // Load the admins
            return axios.get(`${RJ.apiBaseUrl}/admin/get-admin-users`, queryParams)
                .then(response => {
                    response.data.pagination = {'currentPage': response.data.current_page, 'totalPages': response.data.last_page};
                    return response.data;
                });
        },

        deleteAdmin(id) {
            if (this.user.id == id) {
                this.$swal({
                    title: RJ.translations.reject_admin_delete_title,
                    text: RJ.translations.reject_admin_delete_text,
                    type: 'warning'
                });

            } else {
                this.$swal({
                title: RJ.translations.confirm_deactivate_org_title,
                text: RJ.translations.confirm_admin_delete_text,
                type: "warning",
                showCancelButton: true,
                confirmButtonText: RJ.translations.delete,
                cancelButtonText: RJ.translations.cancel
                })
                .then((result) => {
                    if (result.value) {
                        axios.put(`${RJ.apiBaseUrl}/admin/user/${id}/delete`)
                        .then(response => {
                            this.$swal({
                                title: RJ.translations.admin_deleted_title,
                                text: RJ.translations.admin_deleted_text,
                                type: 'success'
                            }).then(result => {
                                window.location.href = `${RJ.baseUrl}/admin/admins`;
                            });
                        });
                    } else {
                        this.$swal(RJ.translations.cancelled);
                    }
                });
            }
        },
    }

});
