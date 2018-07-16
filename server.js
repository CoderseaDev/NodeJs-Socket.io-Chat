var mongo = require('mongodb').MongoClient,
        client = require('socket.io').listen(3000).sockets;
mongo.connect('mongodb://127.0.0.1/chat', function (err, db) {
    if (err)
        throw err;

    client.on('connection', function (socket) {

        var col = db.collection('message'),
                sendStatus = function (s) {
                    socket.emit('status', s)
                };
        // Emit all messages
        col.find().limit(100).sort({_id : 1}).toArray(function (err, res) {
            if (err)
                throw err;
            socket.emit('output',res);
        });
//        console.log('a user connected');
        //wait for input
        socket.on('input', function (data) {
//            console.log(data);
            var name = data.name,
                    message = data.message,
                    whitespacePattern = /^\s*$/;
            if (whitespacePattern.test(name) || whitespacePattern.test(message)) {
                console.log('Invalid');
                sendStatus('Name and Message is Required .');
            } else {
                col.insert({name: name, message: message}, function () {
                    console.log('inserted');
                    // Emit latest message sent
                    client.emit('output',[data]);
                    
                    sendStatus({
                        message: "Message sent",
                        clear: true
                    });
                });
            }
        });
    });
});
