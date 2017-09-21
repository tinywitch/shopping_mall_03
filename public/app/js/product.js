$(function () {
    const product = new Vue({
        el: '#product',
        data: {
            login: true,
            user_id: 1,

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

            comment_content: '',
            replies: {},
        },
        mounted: function () {
            this.fetchData();
        },
        computed: {
            current_image: function () {
                return this.current_list_image[this.current_image_index];
            },
            rate_avg: function () {
                if (this.product.rate_count === 0) {
                    return 0;
                }
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
                    comment: {
                        size: 10,
                        page: 1,
                        total: 30,
                        items: [
                            {
                                id: 1,
                                user_id: 1,
                                user_name: 'Admin',
                                content: 'Minh nang 80 can mac mau gi thi hop nhi?',
                                replies: [
                                    {
                                        id: 30,
                                        user_id: 1,
                                        user_name: 'Admin',
                                        content: 'wtf :|',
                                    },
                                ],
                            },
                            {
                                id: 2,
                                user_id: 2,
                                user_name: 'Ninh Ninh',
                                content: 'Ao the nay ma cung ban :|',
                            },
                        ],
                    },
                    colors: ['Black', 'White', 'Blue'],
                    sizes: {
                        'Black': [1, 2, 3],
                        'White': [1, 2, 3, 4],
                        'Blue': [3, 4, 1],
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
                    intro: 'Jacquard sweatshir',
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
            tabChange: function (idx) {
                if (idx !== 1) {
                    this.is_write_review = false;
                }
            },
            postReview: function () {
                if (this.review.rate === 0) {
                    this.review.error = 'You must rate the product';
                    Snackbar.pushMessage(this.review.error, 'danger');
                } else {
                    // receive data
                    let receive = {
                        id: 1,
                        rate: this.review.rate,
                        user_name: 'Nguyen Phuc Long',
                        province: 'Ha Noi',
                        content: this.review.content,
                        date_created: (new Date()).toDateString(),
                    };

                    this.product.review.items.unshift(receive);

                    this.product.rate_sum += this.review.rate;
                    this.product.rate_count++;

                    Snackbar.pushMessage('Post review success', 'success');

                    this.review.rate = 0;
                    this.review.content = '';
                    this.is_write_review = false;
                }
            },
            postComment: function () {
                let data = {
                    content: this.comment_content,
                };
                console.log(data);

                // Post and receive data
                let receive = {
                    id: 100,
                    user_id: this.user_id,
                    user_name: 'Admin',
                    content: this.comment_content,
                };

                this.product.comment.items.unshift(receive);
                this.product.comment.total++;

                this.comment_content = '';
            },
            postReply: function (comment_id) {
                let data = {};
                data.content = this.replies[comment_id];
                data.comment_id = comment_id;
                console.log(data);

                // Post and receive data
                let receive = {
                    id: 123,
                    comment_id: comment_id,
                    user_id: this.user_id,
                    user_name: 'Admin',
                    content: this.replies[comment_id],
                };

                let items = this.product.comment.items;

                let idx = items.findIndex(item => {
                    return item.id === receive.comment_id;
                });

                if (!items[idx].replies) {
                    items[idx].replies = [];
                }

                items[idx].replies.push(receive);

                this.replies[comment_id] = '';
            },
            deleteComment: function (comment_id, parent_id) {
                let data = {
                    comment_id: comment_id,
                };

                if (parent_id) {
                    data.parent_id = parent_id;
                    data.type = 'delete_reply';
                } else {
                    data.type = 'delete_comment';
                }

                // Post and receive data
                let receive = {
                    status: 'done',
                    type: data.type,
                    comment_id: data.comment_id,
                };

                if (parent_id) {
                    receive.parent_id = parent_id;
                }

                console.log(receive);

                let comments = this.product.comment.items;

                if (receive.status === 'done') {
                    if (receive.type === 'delete_comment') {
                        let idx = comments.findIndex(item => {
                            return item.id === receive.comment_id;
                        });
                        comments.splice(idx, 1);
                    } else {
                        let idx = comments.findIndex(item => {
                            return item.id === receive.parent_id;
                        });
                        let replies = comments[idx].replies;
                        idx = replies.findIndex(item => {
                            return item.id === receive.comment_id;
                        });
                        replies.splice(idx, 1);

                        comments.push({});
                        comments.pop();
                    }
                }
            },
        },
    });
});
