var io = require('socket.io-client');

var socket = io.connect('http://localhost:3000');
    socket.on('connect', function () {
      socket.send("SPEAK_OFF");
      //process.exit();
        setTimeout(
          function(){ 
              process.exit();
          }
        , 500);
    });
