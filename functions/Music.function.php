<?php

/**
 * Pattern or Procedure
 * Check if song/playlist is in the media folder -
 * If media folder is not set this is not set Avy will ask for folder
 */
class Music extends UserFunctions {

    public $Name = "Music";
    public $primarytriggers = array(
        array('music'),
        array('musics'),
        array('song'),
        array('songs'),
        array('playlist'),
        array('playlists'),
    );
    public $triggers = array(
        'playsong' => array(
            array('play'),
        ),
        'playsongs' => array(
            array('play', 'random'),
            array('play', 'some'),
            array('playlist'),
        ),
    );

    public function playsongs() {
        //echo "Playing some songs!";
        TTS::Speak("Playing some songs!");
    }

    public function playsong() {
        TTS::Speak("Playing a song");
    }

    public function fallback() {
        $response = "Cannot find any songs matching " . implode(" ", $parameters) . ".";
    }

}
