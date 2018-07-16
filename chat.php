<!DOCTYPE html>
<!DOCTYPE html>
<html>
    <head>
        <title>chat</title>
        <link rel="stylesheet" type="text/css" href="main.css">
    </head>
    <body>
        <div class="chat">
            <input type="text" class="chat-name" placeholder="Enter your name">
            <div class="chat-messages">
                <!-- <div class="chat-message">
                        Billy : hello
                </div> 
                <div class="chat-message">
                        Alex : hello there
                </div> -->
            </div>
            <textarea placeholder="type your message"></textarea>
            <div class="chat-status">status : <span>Idel</span></div>
        </div>
        <script src="http://node.js.chat:3000/socket.io/socket.io.js"></script>
        <script>
            (function () {
                var getNode = function (s) {
                    return document.querySelector(s);
                },
                        // get required nodes
                        messages = getNode('.chat-messages'),
                        status = getNode('.chat-status span'),
                        textarea = getNode('.chat textarea'),
                        chatName = getNode('.chat-name'),
                        statusDefault = status.textContent,
                        setStatus = function (s) {
                            status.textContent = s;
                            if (s !== statusDefault) {
                                var delay = setTimeout(function () {
                                    setStatus(statusDefault);
                                    clearInterval(delay);
                                }, 3000);
                            }
                        };
//                        setStatus('Testing');
//                        console.log(statusDefault);
                try {
                    var socket = io.connect('http://127.0.0.1:3000');
                } catch (e) {
                    //set status to warn user
                }
                if (socket !== undefined) {
                    //listen for output
                    socket.on('output', function (data) {
                        if (data.length) {
                            for (var x = 0; x < data.length; x++) {
                                var message = document.createElement('div');
                                message.setAttribute('class', 'chat-message');
                                message.textContent = data[x].name + ': ' + data[x].message;
                                //Append
                                messages.appendChild(message);
                                messages.insertBefore(message, messages.firstChild);
                            }
                        }
                    }
                    );
                    //listen for a status
                    socket.on('status', function (data) {
                        setStatus((typeof data === 'object') ? data.message : data);
                        if (data.clear === true) {
                            textarea.value = '';
                        }
                    });
                    //    console.log('OK!');
                    //listen for keydown (enter)
                    textarea.addEventListener('keydown', function () {
                        var self = this;
                        name = chatName.value;
                        //    console.log(event);
                        //    console.log(event.which);
                        if (event.which === 13 && event.shiftKey === false) {
                            console.log('send!');
                            socket.emit('input', {name: name, message: self.value});
                            event.preventDefault();
                        }
                    });
                }
            })();
        </script>

    </body>
</html>