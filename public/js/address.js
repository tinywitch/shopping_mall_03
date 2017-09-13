const address_data = {
    'Ha Noi': [
        'Thanh Xuan',
        'Hai Ba Trung',
    ],
    'Ho Chi Minh': [
        'Quan 1',
        'Quan 9',
    ],
};

const Address = {
    data: {
        states: Object.keys(address_data),
        districts: [],

        selected_state: '',
        selected_dist: '',
    },
    methods: {
        selectState: function () {
            this.districts = address_data[this.selected_state];
            this.selected_dist = '';
        },
    },
};