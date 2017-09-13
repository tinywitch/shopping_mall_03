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
            current_image_index: 0,
        },
        mounted: function () {
            this.fetchData();
        },
        computed: {
            current_image: function () {
                return this.current_list_image[this.current_image_index];
            },
        },
        methods: {
            fetchData: function () {
                let product_data = {
                    id: 1,
                    name: 'EMBROIDERED JACQUARD SWEATSHIRT WITH THREAD DETAIL',
                    price: 40,
                    colors: ['Black', 'White', 'Blue'],
                    sizes: ['XS', 'S', 'L'],
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
                this.selected_size = this.product.sizes[0];
                this.selected_image = 0;
                this.updateImage();
            },
            selectSize: function (size) {
                this.selected_size = size;
            },
            selectColor: function (color) {
                this.selected_color = color;
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
                    Snackbar.pushMessage('The quantity must be between 1 and 100');
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

                Snackbar.pushMessage('Add to cart success', 'Checkout', function () {
                    window.location.href = '/cart/view';
                });
            },
        },
    });
});
