const Address = {
    data: {
        provinces: [],
        districts: [],

        selected_state: '',
        selected_dist: '',
    },
    mounted: function () {
        this.fetchProvince();
    },
    methods: {
        fetchProvince: function () {
            axios.get('/loadprovince')
                .then(res => {
                    this.provinces = res.data;
                })
                .catch(err => {

                });
        },
        selectState: function () {
            // axios.post('/loaddistrict', {
            //     province_name: this.selected_state,
            // })
            //     .then(res => {
            //         console.log(res.data);
            //     })
            //     .catch(err => {
            //
            //     });
            $.ajax({
                type: 'POST',
                url: '/loaddistrict',
                data: {
                    'province_name': this.selected_state,
                },
                success: data => {
                    let data1 = JSON.parse(data);
                    this.districts = Object.values(data1);
                    this.selected_dist = this.districts[0];
                },
            });

        },
    },
};