import Errors from "../../../components/Errors";

Alpine.data(
    "Addresses",
    ({ initialAddresses, initialDefaultAddress, countries }) => ({
        addresses: initialAddresses,
        defaultAddress: initialDefaultAddress,
        countries,
        formOpen: false,
        editing: false,
        loading: false,
        form: { city: "", district: "", ward: "",  },
        city: {},
        district: {},
        ward: {},
        errors: new Errors(),

        get firstcity() {
            return Object.keys(this.countries)[0];
        },

        get hasAddress() {
            return Object.keys(this.addresses).length !== 0;
        },

        get hasNoStates() {
            return Object.keys(this.states).length === 0;
        },

        init() {
            this.fetchCity();
        },

        changeDefaultAddress(address) {
            if (this.defaultAddress.address_id === address.id) return;

            this.defaultAddress.address_id = address.id;

            axios
                .post(route("account.addresses.change_default"), {
                    address_id: address.id,
                })
                .then((response) => {
                    notify(response.data);
                })
                .catch((error) => {
                    notify(error.response.data.message);
                });
        },

        changeCity(provinceId) {
            this.form.city = provinceId;
            this.form.district = "";
            this.form.ward = "";

            this.fetchCity(provinceId);
        },
        async fetchCity(provinceId) {
            try {
                const response = await axios.get(
                    route("countries.states.city")
                );
                const data = response.data; // Dữ liệu đã là object
                this.city = data.data;
                this.fetchDistricts(provinceId);
            } catch (error) {
                console.error('Lỗi fetchCity:', error);
            }
        },


        changeDistrict(districtId) {
            this.form.district = districtId;
            this.form.ward = "";
            this.fetchDistricts(this.form.city);
        },
        async fetchDistricts(provinceId) {
            if (!provinceId) {
                return;
            }
            try {
                const response = await axios.get(
                    route("countries.states.district", { code: provinceId })
                );
                const data = response.data; // Dữ liệu đã là object
                this.district = data.data;
                this.fetchWard(this.form.district);
            } catch (error) {
                console.error('Lỗi fetchDistricts:', error);
            }
        },

        changeWard(wardId) {
            this.form.ward = wardId;
            this.fetchWard(this.form.district);
        },
        async fetchWard(districtId) {
            try {
                const response = await axios.get(
                    route("countries.states.ward", { code: districtId })
                );
                const data = response.data; // Dữ liệu đã là object
                this.ward = data.data;
                console.log(this.form);
            } catch (error) {
                console.error('Lỗi fetchWard:', error);
            }
            // this.fetchDistricts(city);
            // console.log(response);
        },


        edit(address) {
            this.formOpen = true;
            this.editing = true;

            this.$nextTick(() => {
                this.form = { ...address };

                this.fetchDistricts(address.city, () => {
                    this.form.district = "";

                    this.$nextTick(() => {
                        this.form.district = address.state;
                    });
                });
            });
        },

        remove(address) {
            if (!confirm(trans("storefront::account.addresses.confirm"))) {
                return;
            }

            axios
                .delete(route("account.addresses.destroy", address.id))
                .then((response) => {
                    delete this.addresses[address.id];

                    notify(response.data.message);
                })
                .catch((error) => {
                    notify(error.response.data.message);
                });
        },

        cancel() {
            this.editing = false;
            this.formOpen = false;

            this.errors.reset();
            this.resetForm();
        },

        save() {
            this.loading = true;

            this.editing ? this.update() : this.create();
        },

        update() {
            axios
                .put(
                    route("account.addresses.update", { id: this.form.id }),
                    this.form
                )
                .then(({ data }) => {
                    this.formOpen = false;
                    this.editing = false;

                    this.addresses[this.form.id] = data.address;

                    this.resetForm();

                    notify(data.message);
                })
                .catch(({ response }) => {
                    if (response.status === 422) {
                        this.errors.record(response.data.errors);
                    }

                    notify(response.data.message);
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        create() {
            axios
                .post(route("account.addresses.store"), this.form)
                .then(({ data }) => {
                    this.formOpen = false;

                    let address = { [data.address.id]: data.address };

                    this.addresses = {
                        ...this.addresses,
                        ...address,
                    };

                    this.resetForm();

                    notify(data.message);
                })
                .catch(({ response }) => {
                    if (response.status === 422) {
                        this.errors.record(response.data.errors);
                    }

                    notify(response.data.message);
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        resetForm() {
            this.form = { state: "" };
        },
    })
);
