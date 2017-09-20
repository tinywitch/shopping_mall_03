/**
 * Created by isling on 12/09/2017.
 */
const address = window.address_data;

$(function () {
    let config = {
        el: '#checkout',
        data: {
            login: false,

            is_phone_number: true,

            ship_add: {},

            sub_total: Cart.cart.subTotalPrice,
            ship_tax: 20,

            success: false,
            order_id: '',
        },
        mounted: function () {
            this.fetchShipAddress();
            this.selected_state = this.ship_add.province;
            this.selectState();
            this.selected_dist = this.ship_add.dist;
        },
        methods: {
            fetchShipAddress: function () {
                let add = {
                    full_name: 'Nguyen Phuc Long',
                    phone_number: '012345678',
                    email: 'admin@gmail.com',
                    province: 'Ha Noi',
                    dist: 'Hai Ba Trung',
                    address: 'No1 - Dai Co Viet',
                };
                this.login = true;
                this.ship_add = add;
            },
            // selectState: function () {
            //     this.districts = address[this.selected_state];
            // },
            checkout: function () {
                this.ship_add.province = this.selected_state;
                this.ship_add.dist = this.selected_dist;

                let data = {
                    ship_add: this.ship_add,
                    sub_total: this.sub_total,
                    ship_tax: this.ship_tax,
                };
                console.log(data);

                if (!this.validateData(data.ship_add)) {
                    Snackbar.pushMessage('You must fill all information.', 'warning');
                    return;
                }

                // post data and receive order_id
                axios.post('/cart/checkout', data)
                    .then(res => {
                        console.log(res.data);
                        // show success page
                        if (res.data.status === 'ok') {
                            this.success = true;
                            this.order_id = res.data.order_id;
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            validateData: function (data) {
                let valid = {
                    full_name: 'required',
                    phone_number: 'required',
                    email: 'required',
                    province: 'required',
                    dist: 'required',
                    address: 'required',
                };

                let result = true;

                Object.keys(valid).forEach(key => {
                    if (valid[key] === 'required') {
                        if (!data[key] || data[key] === '') {
                            result = false;
                        }
                    }
                });

                return result;
            },
        },
    };

    Object.assign(config.data, Address.data);
    Object.assign(config.methods, Address.methods);

    const checkout = new Vue(config);
});
