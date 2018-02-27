const form = {

    data() {
        return {
            loading: false,

            package: window._ptpkgAPIDataPreload.data,
        }
    },

    methods: {
        setLoading(value) {
            this.loading = !!value;
        }
    }
};

export default form;
