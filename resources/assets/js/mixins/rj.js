const md = require('markdown-it')();

/**
 * Export the root Spark application.
 */
module.exports = {
    el: '#app',

    /**
     * The application's data.
     */
    data: {
        user: null,
        currentOrganization: null,
        defaultMdeConfig: {
            promptURLs: true,
            spellChecker: false,
            status: false,
            hideIcons: ["guide", "side-by-side", "fullscreen"],
            insertTexts: {
                horizontalRule: ["", "\n\n-----\n\n"],
                table: ["", "\n\n| Column 1 | Column 2 | Column 3 |\n| -------- | -------- | -------- |\n| Text     | Text      | Text     |\n\n"],
            }
        }
    },


    /**
     * The component has been created by Vue.
     */
    created() {
        var self = this;
        //
    },


    /**
     * Prepare the application.
     */
    mounted() {
        this.whenReady();
    },


    methods: {
        /**
         * Finish bootstrapping the application.
         */
        whenReady() {
            // If we have user id, then get the user data
            if (this.rj.userId && this.rj.userId !== null) {
                this.refreshCurrentUser();
            }

            // If we have organization id, then get the organization data
            if (this.rj.organizationId && this.rj.organizationId !== null) {
                this.refreshOrganization(this.rj.organizationId);
            }

            // Set the client/browserTimezone in the global RJ object
            window.RJ.browserTimezone = moment.tz.guess();
        },

        refreshOrganization (organizationId) {
            this.getOrganiation(organizationId)
                .then(response => {
                    this.currentOrganization = response.data;
                });
        },

        getOrganiation(id) {
            return axios.get(`${RJ.apiBaseUrl}/organization/${id}`);
        },

        getCampaign(organizationId, id) {
            return axios.get(`${RJ.apiBaseUrl}/organization/${organizationId}/campaign/${id}`);
        },

        getRewards(organizationId, campaignId) {
            return axios.get(`${RJ.apiBaseUrl}/organization/${organizationId}/campaign/${campaignId}/reward`);
        },

        getStates(country) {
            return axios.get(`${RJ.apiBaseUrl}/states/${country}`);
        },

        getCurrencies(country) {
            return axios.get(`${RJ.apiBaseUrl}/currencies`);
        },

        getDonorQuestions(organizationId) {
            return axios.get(`${RJ.apiBaseUrl}/organization/${organizationId}/donor-question`);
        },

        addQueryToCurrentUrl (param, value) {
            if (history.pushState) {
                let newUrl = `${window.location.protocol}//${window.location.host}${window.location.pathname}?${param}=${value}`;

                window.history.pushState({path:newUrl}, '', newUrl);
            }
        },

        addMultipleQueryToCurrentUrl (params) {
            let query = '';
            let param_length = Object.keys(params).length;
            let index = 0;
            for (var i in params) {
                index++
                if (index === param_length){
                    query +=  i+"="+params[i];
                    break;
                }
                query +=  i+"="+params[i]+"&"
            }
            if (history.pushState) {
                let newUrl = `${window.location.protocol}//${window.location.host}${window.location.pathname}?${query}`;

                window.history.pushState({path:newUrl}, '', newUrl);
            }
        },

        getBrowserTzName () {
            return moment.tz(this.rj.browserTimezone).zoneName();
        },

        convertBrowserToUTC (dateString) {
            let browserMoment = moment.tz(dateString, this.rj.browserTimezone);
            let utcMoment = browserMoment.clone().tz('UTC');
            return utcMoment.format('Y-MM-DD HH:mm:ss');
        },

        convertUTCToBrowser (dateString, format = 'Y-MM-DD HH:mm:ss') {
            let utcMoment = moment.tz(dateString, 'UTC');
            let browserMoment = utcMoment.clone().tz(this.rj.browserTimezone);
            return browserMoment.format(format);
        },

       getDateDifferenceInDays (date1, date2) {
            if (date1 !== null) {
                d1 = moment(date1);
            } else {
                d1 = moment();
            }
            d2 = moment(date2);
            let remDays = d2.diff(d1, 'days');
            return remDays;
        },

        getDateDifferenceInHumanize (date1, date2) {
            if (date1 !== null) {
                d1 = moment(date1);
            } else {
                d1 = moment();
            }
            d2 = moment(date2);

            return `${moment.duration(d2.diff(d1)).humanize()}`;
        },

        getDateDifferenceHumanize (date1, date2) {
            if (date1 !== null) {
                d1 = moment(date1);
            } else {
                d1 = moment();
            }

            d2 = moment(date2);

            let suffix = 'left';
            let prefix = '';

            // If d1 is same or after d2 i.e. d1 is greater than d2
            // then it means campaign ended
            if (d1.isSameOrAfter(d2)) {
                prefix = 'ended'
                suffix = 'ago';
            }

            return `${prefix} ${moment.duration(d2.diff(d1)).humanize()} ${suffix}`;
        },

        getConnectedAccounts (organizationId) {
            return axios.get(`${RJ.apiBaseUrl}/organization/${organizationId}/connected-accounts`);
        },

        refreshCurrentUser () {
            this.getCurrentUser()
                .then(response => {
                    this.user = response.data;
                });
        },

        getCurrentUser () {
            return axios.get(`${RJ.apiBaseUrl}/me`);
        },

        getCampaigns (organizationId, page, status = null) {
            let campaignListUrl;
            if (status == 'active') {
                campaignListUrl = `${RJ.apiBaseUrl}/organization/${organizationId}/campaigns?status=active`;
            } else {
                campaignListUrl = `${RJ.apiBaseUrl}/organization/${organizationId}/campaigns`;
            }
            if (typeof page !== 'undefined' && status == 'active') {
                campaignListUrl += '&page=' + page;
            } else if (typeof page !== 'undefined' && status == null) {
                campaignListUrl += '?page=' + page;
            }

            return axios.get(campaignListUrl);
        },

        formatAmount(amount, currency = '$') {
            if (amount == null) {
                amount = 0;
            }
            const pieces = parseFloat(amount).toFixed(2).split('');
            let ii = pieces.length - 3;
            while ((ii-=3) > 0) {
              pieces.splice(ii, 0, ',');
            }
            return currency + pieces.join('');
        },

        getDonors (organizationId, queryParams) {
            let donorListUrl = `${RJ.apiBaseUrl}/organization/${organizationId}/donors`;

            return axios.get(donorListUrl, queryParams);
        },

        getDonations (organizationId, donorId) {
            return axios.get(`${RJ.apiBaseUrl}/organization/${organizationId}/donor/${donorId}/donations`);
        },

        getDonorQuestionAnswers (organizationId, donorId) {
            return axios.get(`${RJ.apiBaseUrl}/organization/${organizationId}/donor/${donorId}/donor-question-answers`);
        },

        resendInvitation (organizationId, invitationId) {
            return axios.get(`${RJ.apiBaseUrl}/organization/${organizationId}/invitation/${invitationId}/resend-email`);
        },

        deleteInvitation (organizationId, invitationId) {
            return axios.delete(`${RJ.apiBaseUrl}/organization/${organizationId}/invitation/${invitationId}`);
        },

        deleteAccountUser (organizationId, userId) {
            return axios.delete(`${RJ.apiBaseUrl}/organization/${organizationId}/account-user/${userId}`);
        },

        getUserRoles() {
            return {
                'owner': 'Account Owner - Can create and edit all users, campaigns, organization profile and billing information',
                'admin': 'Organization Admin - Can create and edit all users, campaigns and organization',
                'campaign-admin': 'Campaign Admin - Can edit assigned campaigns'
            }
        },

        getOrganizationCampaigns (organizationId) {
            return axios.get(`${RJ.apiBaseUrl}/organization/${organizationId}/campaign-list`);
        },

        getOrganizationDonations (organizationId, queryParams) {
            return axios.get(`${RJ.apiBaseUrl}/organization/${organizationId}/donations`, queryParams);
        },

        getOrganizationAccounts (organizationId) {
            return axios.get(`${RJ.apiBaseUrl}/organization/${organizationId}/account-list`);
        },

        getOrganizationPayouts (organizationId, queryParams) {
            return axios.get(`${RJ.apiBaseUrl}/organization/${organizationId}/payouts`, queryParams);
        },

        renderMd (markdownContent) {
            return md.render(markdownContent);
        },

        sMessage (message, title = null) {
            title = title || RJ.translations.success;
            this.$toastr.s(message, title);
        },

        eMessage (message, title = null) {
            title = title || RJ.translations.error;
            this.$toastr.e(message, title);
        },

        wMessage (message, title = null) {
            title = title || RJ.translations.warning;
            this.$toastr.w(message, title);
        },

        iMessage (message, title = null) {
            title = title || RJ.translations.info;
            this.$toastr.i(message, title);
        },

        greetingBaseOnTime (currentTime) {
            if (!currentTime || !currentTime.isValid()) {
                return RJ.translations.hello;
            }

            const splitAfternoon = 12; // 24hr time to split the afternoon
            const splitEvening = 17; // 24hr time to split the evening
            const currentHour = parseFloat(currentTime.format('HH'));

            if (currentHour >= splitAfternoon && currentHour < splitEvening) {
              // Between 12 PM and 5PM
              return RJ.translations.good_afternoon;
            } else if (currentHour >= splitEvening) {
              // Between 5PM and Midnight
              return RJ.translations.good_evening;
            }
            // Between dawn and noon
            return RJ.translations.good_morning;
        },

        donationMoney (amount, symbol = '') {
            // If amount is null or empty, then return 0
            if (! amount) {
                return `${symbol}0`;
            }

            let decimals = 0;
            // If there are fractional units in amount, then format
            // with 2 decimals else format with no decimals
            if (! isNaN(amount) && _.floor(amount) != amount) {
                decimals = 2;
            }

            return `${symbol}${this.$root.numberFormat(amount, decimals)}`;
        },

        numberFormat (number, decimals = 0, decPoint = '.', thousandsSep = ',') {
            // Strip all characters but numerical ones.
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');

            let n = ! isFinite(+number) ? 0 : +number,
            prec = ! isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep,
            dec = (typeof decPoint === 'undefined') ? '.' : decPoint,
            s = '',
            toFixedFix = function (n, prec) {
                let k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };

            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');

            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }

            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }

            return s.join(dec);
        },

        formatDate (dateString, format = 'Y-MM-DD HH:mm:ss') {
            return moment(dateString).format(format);
        },

    },


    computed: {
        /**
         * Access the global Spark object.
         */
        rj() {
            return window.RJ;
        }
    }
};
