import Autocomplete from 'vuejs-auto-complete'
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import VueSimplemde from 'vue-simplemde'
import 'simplemde/dist/simplemde.min.css'

Vue.component('payout-create', {
    components: {
        flatPickr,
        VueSimplemde,
        Autocomplete
    },

    data: function() {
        return  {
            httpHeaders: {
                "x-csrf-token": document.head.querySelector('meta[name="csrf-token"]').content
            },
            form: new RJForm({
                organization_id: '',
                campaign_id: '',
                issue_date: '',
                start_date: '',
                end_date: '',
                gross_amount: 0,
                total_amount: 0,
                donation_ids: [],
                payout_name: '',
                payout_address1: '',
                payout_address2: '',
                payout_city: '',
                payout_state: '',
                payout_zipcode: '',
                payout_country_id: '',
                payout_payable_to: '',
                timezone: '',

            }),
            flatPickrConfig: {
                wrap: true, // set wrap to true only when using 'input-group'
                altFormat: 'm/d/Y',//'M   j, Y h:i K',
                altInput: true,
                dateFormat: 'Y-m-d',
                enableTime: false,
                altInputClass: 'input__cale',
                //minDate: 'today'
            },
            getDonationBtn: false,
            campaigns: [],
            donations: {},
            campaign: '',
            currency: '',
            errors:{},
            showLoading: false
        }
    },

    mounted() {
        this.form.issue_date = new Date();
    },

    methods: {
        showItem (item) {
            return `${item.name}`;
        },

        getCampaigns (result) {
            this.campaign = '';
            this.form.campaign_id = '';
            this.updateDonation();
            this.form.organization_id = result.value;

            axios.get(`${RJ.apiBaseUrl}/admin/organization/${result.value}/payout-campaigns`)
                .then(response => {
                    this.campaigns = response.data;
                })
                .catch(error => {
                    this.$root.eMessage(RJ.translations.save_error);
                });
        },

        selectedCampaign (result) {
            this.campaign = result;
            this.form.campaign_id = result.id;
            this.form.payout_name = result.payout_name ;
            this.form.payout_address1 = result.payout_address1 ;
            this.form.payout_address2 = result.payout_address2 ;
            this.form.payout_city = result.payout_city ;
            this.form.payout_state = result.payout_state ;
            this.form.payout_zipcode = result.payout_zipcode ;
            this.form.payout_country_id = result.payout_country_id ;
            this.form.payout_payable_to = result.payout_payable_to ;
            this.currency = result.organization.currency.symbol;
            this.updateDonation();
        },

        getDonations(result) {
            this.showLoading = true;
            let zone_name =  moment.tz.guess();
            this.form.timezone = moment.tz(zone_name).zoneAbbr();

            this.errors = {};

            if (this.form.organization_id == '') {
                this.errors.organization_id = "Please select organization";
            }

            if (this.form.campaign_id == '') {
                this.errors.campaign_id = "Please select campaign";
            }

            if (this.form.start_date == '') {
                this.errors.start_date = "Please select start date";
            }

            if ( this.form.end_date == '') {
                this.errors.end_date = "Please select end date";
            }

            if (! this.errors.length > 0){
                axios.get(`${RJ.apiBaseUrl}/admin/campaign/${this.form.campaign_id}/donations?start_date=${this.form.start_date}&end_date=${this.form.end_date}&timezone=${this.form.timezone}`)
                .then(response => {
                    if (response.data) {
                        this.donations = response.data;
                        this.calculateAmounts(response.data);
                    } else {
                        this.$swal("No donation found!");
                    }
                    this.showLoading = false;
                })
                .catch(error => {
                    this.showLoading = false;
                    this.$root.eMessage(RJ.translations.save_error);
                });
            }
            this.getDonationBtn = true;
        },

        calculateAmounts(donations) {
            this.form.donation_ids = [];
            this.form.gross_amount = 0;
            this.form.total_amount = 0;

            for (var i in donations) {
                this.form.donation_ids.push(donations[i].id);
                this.form.gross_amount += donations[i].gross_amount;
                this.form.total_amount += donations[i].net_amount;
            }
        },

        submit() {
            this.form.startProcessing();
            this.$swal({
                title: RJ.translations.confirm_record_payout_title,
                text: RJ.translations.confirm_record_payout,
                type: 'warning',
                showCancelButton: true
            }).then(result => {
                if (result.value) {
                    
                    RJ.post(`${RJ.apiBaseUrl}/admin/payout`, this.form)
                    .then(response => {
                        this.$root.sMessage(RJ.translations.saved_successfully);

                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    })
                    .catch(error => {
                        this.$root.eMessage(RJ.translations.save_error);
                    });
                }
            });
            this.form.finishProcessing();
        },

        updateDonation () {
            this.donations = {};
        }

    }
});
