/**
 * Created by isling on 12/09/2017.
 */
const address = window.address_data;

$(function () {
    let config = {
        el: '#checkout',
        data: {
            // states: Object.keys(address),
            // districts: [],

            login: false,

            is_phone_number: true,
            // selected_state: '',
            // selected_dist: '',

            ship_add: {},

            sub_total: Cart.cart.subTotalPrice,
            ship_tax: 20,

            success: false,
            order_id: '',
        },
        mounted: function () {
            this.fetchShipAddress();
            this.selected_state = this.ship_add.state;
            this.selectState();
            this.selected_dist = this.ship_add.dist;
        },
        methods: {
            fetchShipAddress: function () {
                let add = {
                    full_name: 'Nguyen Phuc Long',
                    phone_number: '012345678',
                    email: 'admin@gmail.com',
                    state: 'Ha Noi',
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
                let data = {
                    ship_add: this.ship_add,
                    sub_total: this.sub_total,
                    ship_tax: this.ship_tax,
                };
                console.log(data);

                // post data and receive order_id

                // show success page
                this.success = true;
                this.order_id = '123456';
            },
        },
    };

    Object.assign(config.data, Address.data);
    Object.assign(config.methods, Address.methods);

    const checkout = new Vue(config);
});
