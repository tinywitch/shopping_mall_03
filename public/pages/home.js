$(document).ready(function () {

    var socket = io.connect('http://shopping.app:8056/');

    function emitMessage(message) {
        console.log(socket);
        console.log(message);
        socket.emit('new message', message);
    }

    socket.on('new message', msg => {
        app.messages.push(msg);
        console.log(msg);
    });

    var app = new Vue({
        el: '#app',
        data: {
            messages: [],
            text: '',
            user: '',
            isClosed: false,
            chatrooms: [],
            current_chatroom: '',
            current_chatting_user: 'Chat'
        },
        created: function () {
            this.getCurrentUser();
        },

        methods: {
            getMessages: function () {
                axios.get('/getmessages', {id: this.user.id})
                    .then(response => {
                        this.messages = response.data;
                        this.current_chatroom = this.messages[0].chatroom_id
                    })
            },
            getChatrooms: function () {
                axios.get('/getchatrooms')
                    .then(response => {
                        this.chatrooms = response.data;
                    })
            },
            sendMessage: function () {
                if (this.text.trim() === '') {
                    return;
                }
                message = {
                    sender_id: this.user.id,
                    content: this.text,
                    chatroom_id: this.current_chatroom
                };

                emitMessage(message);

                $.post("/sendmessage", {
                    sender_id: this.user.id,
                    content: this.text,
                    chatroom_id: this.current_chatroom
                }, function (data, status) {

                });
                this.text = '';

            },
            getCurrentUser: function () {
                axios.get('/getCurrentUser')
                    .then(response => {
                        this.user = response.data;
                        console.log(this.user);
                        if (this.user.role === "0")
                            this.getChatrooms();
                        else
                            this.getMessages();
                    });

            },

            closeChatbox: function () {
                this.isClosed = true;
            },
            openChatbox: function () {
                this.isClosed = false;
            },

            openChatroom: function (id) {
                cr = this.chatrooms.find(c => c.id === id);
                this.messages = cr.messages;
                this.current_chatroom = id;
                this.current_chatting_user = cr.user.fullname;
                this.openChatbox();
            }
        }
    });
});
