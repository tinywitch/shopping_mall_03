$(function () {
    const order = new Vue({
        el: '#track-order',
        data: {
            track_form: true,
            order_id: '',
            email: '',
            order: {},
        },
        methods: {
            tracking: function () {
                let hasError = !this.order_id
                    || !this.email
                    || this.errors.has('email')
                    || this.errors.has('order id');
                if (hasError) {
                    Snackbar.pushMessage('You must write email and order id');
                } else {
                    this.fetchOrder(this.order_id);
                    this.track_form = false;
                }
            },
            continueTrack: function () {
                this.track_form = true;
            },
            fetchOrder(id) {
                let data = {
                    created_at: (new Date()).toDateString(),
                    items: [
                        {
                            id: 1,
                            name: 'Cu cai 1',
                            quantity: 1,
                            color: 'Black',
                            size: 'XL',
                            price: 40,
                            total: 40,
                        },
                        {
                            id: 2,
                            name: 'Cu cai 2',
                            quantity: 1,
                            color: 'Black',
                            size: 'XL',
                            price: 80,
                            total: 80,
                        },
                    ],
                    customer_detail: {
                        full_name: 'Nguyen Phuc Long',
                        email: 'admin@gmail.com',
                        phone_number: '0123456789',
                        address: 'No1 - Dai Co Viet - Hai Ba Trung - Ha Noi',
                    },
                    total_price: 120,
                    status: 0,
                };

                if (id < 0) {
                    data.error = 'Something went wrong :\">'
                }

                this.order = data;
            },
        },
    });
});
