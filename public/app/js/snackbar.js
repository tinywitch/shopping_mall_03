let Snackbar;

$(function () {
    Snackbar = new Vue({
        el: '#snackbar',
        data: {
            vertical: 'bottom',
            horizontal: 'center',
            duration: 4000,
            notifyMessage: '',
            notifyAction: 'Close',
        },
        methods: {
            pushMessage: function (message, action, action_fn) {
                this.notifyMessage = message;
                this.notifyAction = action ? action : 'Close';
                this.action_fn = action_fn ? action_fn : null;
                this.$refs.snackbar.open();
            },
            notifyActionAdapter: function () {
                this.action_fn ? this.action_fn() : this.$refs.snackbar.close();
            },
        },
    });
});
