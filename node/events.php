<?php
if($_GET['event'])
{
    $parameter = $_GET['event'];
}else{
    $parameter = $argv[1];
}
$events = array('SPEAK_ON','SPEAK_OFF','RECORD_ON','RECORD_OFF','PROCESSING_ON','PROCESSING_OFF');
if(in_array($parameter, $events)){
    $triggered_event = $parameter;
}else{
    $triggered_event = 'INVALID';
}
?>
<html>
<body>
<h1>Avy Events</h1>
<script type="text/javascript" src="node_modules/socket.io/node_modules/socket.io-client/socket.io.js"></script>
<script>
    var socket = io.connect('http://localhost:3000');
    socket.on('connect', function () {
      socket.send("<?php echo $triggered_event; ?>");
    });
</script>
</body>
</html>