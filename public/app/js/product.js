$(function () {
    const product = new Vue({
        el: '#product',
        data: {
            product: {},
            selected_color: '',
            selected_size: '',
            selected_image: -1,
            quantity: 1,
            current_list_image: [],
            current_list_size: [],
            current_image_index: 0,

            is_write_review: false,
            review: {
                rate: 0,
                content: '',
            },
        },
        mounted: function () {
            this.fetchData();
        },
        computed: {
            current_image: function () {
                return this.current_list_image[this.current_image_index];
            },
            rate_avg: function () {
                return Math.round(this.product.rate_sum * 100 / this.product.rate_count) / 100;
            },
        },
        methods: {
            fetchData: function () {
                let product_data = {
                    id: 1,
                    name: 'EMBROIDERED JACQUARD SWEATSHIRT WITH THREAD DETAIL',
                    price: 40,
                    rate_sum: 160,
                    rate_count: 45,
                    review: {
                        size: 10,
                        page: 1,
                        total: this.rate_count,
                        items: [
                            {
                                id: 1,
                                rate: 5,
                                user_name: 'Thuy Ninh',
                                province: 'Ha Noi',
                                content: 'Ao rat dep, minh chon size 30 vua luon',
                                date_created: (new Date()).toDateString(),
                            },
                            {
                                id: 2,
                                rate: 4,
                                user_name: 'Truong',
                                province: 'Ha Noi',
                                content: 'Ao rat dep, minh chon size 30 vua luon',
                                date_created: (new Date()).toDateString(),
                            },
                            {
                                id: 3,
                                rate: 2,
                                user_name: 'Nguyen Trung Duc',
                                province: 'Ha Noi',
                                content: 'Ao rat dep, minh chon size 30 vua luon',
                                date_created: (new Date()).toDateString(),
                            },
                            {
                                id: 4,
                                rate: 5,
                                user_name: 'Nguyen Phuc Long',
                                province: 'Ha Noi',
                                content: 'Ao rat dep, minh chon size 30 vua luon',
                                date_created: (new Date()).toDateString(),
                            },
                            {
                                id: 5,
                                rate: 1,
                                user_name: 'Nguyen Phuc Long',
                                province: 'Ha Noi',
                                content: 'Ao rat dep, minh chon size 30 vua luon',
                                date_created: (new Date()).toDateString(),
                            },
                        ],
                    },
                    colors: ['Black', 'White', 'Blue'],
                    sizes: {
                        'Black': [28, 29, 31],
                        'White': [27, 28, 29, 30],
                        'Blue': [26, 27, 30],
                    },
                    images: {
                        'Black': [
                            'https://static.zara.net/photos///2017/I/0/1/p/7252/223/800/2/w/560/7252223800_1_1_1.jpg',
                            'https://static.zara.net/photos///2017/I/0/1/p/7252/223/800/2/w/400/7252223800_2_1_1.jpg',
                            'https://static.zara.net/photos///2017/I/0/1/p/7252/223/800/2/w/400/7252223800_2_4_1.jpg',
                            'https://static.zara.net/photos///2017/I/0/1/p/7252/223/800/2/w/560/7252223800_6_1_1.jpg',
                        ],
                        'White': [
                            'https://static.zara.net/photos///2017/I/0/1/p/7252/223/800/2/w/560/7252223800_1_1_1.jpg',
                            'https://static.zara.net/photos///2017/I/0/1/p/7252/223/800/2/w/400/7252223800_2_1_1.jpg',
                            'https://static.zara.net/photos///2017/I/0/1/p/7252/223/800/2/w/400/7252223800_2_4_1.jpg',
                        ],
                        'Blue': [
                            'https://static.zara.net/photos///2017/I/0/1/p/7252/223/800/2/w/560/7252223800_1_1_1.jpg',
                            'https://static.zara.net/photos///2017/I/0/1/p/7252/223/800/2/w/400/7252223800_2_1_1.jpg',
                        ],
                    },
                    intro: 'Jacquard sweatshirt with round neckline, long sleeves and front embroidery with long threads.',
                };

                this.product = product_data;
                this.selected_color = this.product.colors[0];
                this.current_list_size = this.product.sizes[this.selected_color];
                this.selected_size = this.current_list_size[0];
                this.selected_image = 0;
                this.updateImage();
            },
            selectSize: function (size) {
                this.selected_size = size;
            },
            selectColor: function (color) {
                this.selected_color = color;
                this.current_list_size = this.product.sizes[this.selected_color];
                this.selected_size = this.current_list_size[0];
                this.updateImage();
            },
            changeImage: function (index) {
                this.current_image_index = index;
            },
            updateImage: function () {
                this.current_list_image = this.product.images[this.selected_color];
                this.current_image_index = 0;
            },
            addToCart: function () {
                if (this.quantity < 1 || this.quantity > 100) {
                    Snackbar.pushMessage('The quantity must be between 1 and 100', 'danger');
                    return;
                }

                let item = {};
                item.real_id = this.product.id;
                item.name = this.product.name;
                item.quantity = this.quantity;
                item.img = this.product.images[this.selected_color][0];
                item.price = this.product.price;
                item.size = this.selected_size;
                item.color = this.selected_color;
                item.id = [this.product.id, item.color, item.size].join('_');

                Cart.addToCart(item);

                Snackbar.pushMessage('Add to cart success', 'success', 'Checkout', function () {
                    window.location.href = '/cart/view';
                });
            },
            page: function () {

            },
            sort_user_name: function (name) {
                let arr = name.split(' ');

                if (arr.length < 2) {
                    return name;
                }

                return arr[arr.length - 2] + ' ' + arr[arr.length - 1];
            },
            switchWriteReviewShowReview: function () {
                this.is_write_review = !this.is_write_review;
            },
            writeReview: function () {
                this.is_write_review = true;
            },
            postReview: function () {
                if (this.review.rate === 0) {
                    this.review.error = 'You must rate the product';
                    Snackbar.pushMessage(this.review.error, 'danger');
                } else {
                    // recive data
                    let recive = {
                        id: 1,
                        rate: this.review.rate,
                        user_name: 'Nguyen Phuc Long',
                        province: 'Ha Noi',
                        content: this.review.content,
                        date_created: (new Date()).toDateString(),
                    };

                    this.product.review.items.unshift(recive);

                    this.product.rate_sum += this.review.rate;
                    this.product.rate_count++;

                    Snackbar.pushMessage('Post review success', 'success');

                    this.review.rate = 0;
                    this.review.content = '';
                    this.is_write_review = false;
                }
            },
        },
    });
});
