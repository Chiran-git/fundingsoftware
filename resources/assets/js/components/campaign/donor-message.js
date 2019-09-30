import VueSimplemde from 'vue-simplemde';
import 'simplemde/dist/simplemde.min.css';

Vue.component('campaign-donor-message', {
    props: [
        'organization',
        'campaign'
    ],

    mixins: [
        require('../../mixins/campaign-progress'),
    ],

    components: {
        VueSimplemde
    },

    data () {
        const descriptionMdeConfig = _.merge(this.$root.defaultMdeConfig, {
            placeholder: RJ.translations.donor_message_placeholder
        });

        return  {
            form: new RJForm({
                donor_message: this.campaign.donor_message || '',
            }),
            descriptionMdeConfig: descriptionMdeConfig,
        }
    },

    methods: {
        submit () {
            RJ.put(`${RJ.apiBaseUrl}/organization/${this.organization.id}/campaign/${this.campaign.id}/donor-message`, this.form)
                .then(response => {
                    this.$root.sMessage(RJ.translations.saved_successfully);
                    app.$emit('donorMessageSaved');
                })
                .catch(error => {
                    this.$root.eMessage(RJ.translations.save_error);
                });
        }
    }
});
