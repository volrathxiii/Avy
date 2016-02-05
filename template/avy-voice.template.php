        <div class="avy-voice">
            <ul id="VoiceView" style="display: none;"></ul>
            <script>
                var SpeakIntervalId = 0;
                var socket = io.connect('http://<?php echo trim($_SERVER['SERVER_ADDR']); ?>:3000');
                socket.on('connect', function () {
                    socket.on('event', function (msg) {

                        if (msg.speak === 'SPEAK_ON')
                        {
                            $('#mainCanvas').trigger('mousedown');
                        } else
                        if (msg.speak === 'SPEAK_OFF')
                        {
                            $('#mainCanvas').trigger('mouseup');
                        }

                        if (msg.speak === 'RECORD_ON')
                        {
                            $('.recording-spinner').removeClass('inactive');
                            $('.recording-spinner').addClass('active');
                        } else
                        if (msg.speak === 'RECORD_OFF')
                        {
                            $('.recording-spinner').removeClass('active');
                            $('.recording-spinner').addClass('inactive');
                        }

                        $('#VoiceView').html('<li>' + msg.speak + '</li>');
                    });
                });
            </script>
            <!-- VOICE VISUALIZATION -->
            <div id="outer">
                <div id="canvasContainer">
                    <canvas id="mainCanvas" width="1000" height="560"></canvas>
                    <div id="output"></div>
                </div>
            </div>
        </div>