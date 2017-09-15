let Order;
$(function () {
    Order = new Vue({
        el: '#order',
        data: {
            orders: [],
        },
        mounted: function () {
            this.fetchOrderList();
        },
        methods: {
            fetchOrderList: function () {
                let data = [
                    {
                        id: '1234564789',
                        created_at: (new Date()).toDateString(),
                        items: [
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
                    },
                    {
                        id: '1324658878',
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
                        status: 2,
                    },
                ];
                this.orders = data;
            },
        },
    });
});
