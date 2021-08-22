import { mapActions, mapGetters } from "vuex";
export default {
    data() {
        return {
            // siteUri: 'http://bookingmarket.localhost/',
            selectdata: ""
        };
    },
    mounted() {},
    computed: {
        ...mapGetters({
            authUser: "getAuthUser",
            isLoggedIn: "isLoggedIn",
            item: "getItem",
            ra: "getRA",
            districts: "getAllDistricts",
            divisions: "getAllDivisions"
        })
    },
    filters: {
        toFixedNum(num) {
            return num.toFixed(2);
        }
    },
    methods: {
        /**
         *
         * @param {*} method, call method
         * @param {*} url , api url
         * @param {*} dataObj, payload
         */
        async callApi(method, url, dataObj) {
            try {
                let data = await axios({
                    headers: {
                        Accept: "application/json",
                        "Content-Type": "application/json"
                    },
                    method: method,
                    url: "/app/" + url,
                    data: dataObj
                });
                return data;
            } catch (e) {
                return e.response;
            }
        },

        i(msg, i = "Hey!") {
            this.$Notice.info({
                title: i,
                desc: msg
            });
        },
        sir(msg, i = "Sir!") {
            this.$Notice.info({
                title: i,
                desc: msg
            });
        },
        s(msg, i = "Great!") {
            this.$Notice.success({
                title: i,
                desc: msg
            });
        },
        w(msg, i = "Hi!") {
            this.$Notice.warning({
                title: i,
                desc: msg
            });
        },
        e(msg, i = "Oops!") {
            this.$Notice.error({
                title: i,
                desc: msg
            });
        },
        swr() {
            this.$Notice.error({
                title: "Oops",
                desc: "Something went wrong, please try again later"
            });
        },
        ns(title) {
            this.$Message.success(title);
        },
        ni(title) {
            this.$Message.info(title);
        },
        nw(title) {
            this.$Message.warning(title);
        },
        ne(title) {
            this.$Message.error(title);
        },
        nswr() {
            this.$Message.error("Something went wrong, please try again later");
        },
        ls() {
            this.$Loading.start();
        },
        lf() {
            this.$Loading.finish();
        }
    }
};
