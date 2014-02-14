<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<body onload="onPageLoad()">

<script type='text/javascript' src='/jwplayer/jwplayer.js'></script>

<p>Select Music File: <input type='file' id='musicfile' /></p>
<input type='button' id='selectsong' value='Change Song' onclick='changeSong()' />
<p id='timeleft'></p>

<form name='timerfm' action='self'>
Minutes:
<select id='mins'>
<?php
	foreach (range(1,30) as $n) {
		if ($n == 10)
			print "<option value='$n' selected='selected'>$n</option>";
		else
			print "<option value='$n'>$n</option>";
	}
?>
<input type='button' value='Restart Timer' onClick='restartTimer(0)' />
<input type='button' value='Stop Timer' onClick='stopTimer()' />
</form>

<p id='song'></p>
<div id='mediaplayer'></div>

<script type="text/javascript">

var timer;
var timersecs;

var counter;
var secs = 10*60;	// default 10 minutes

var resetTime = 20;	// 20 mins

var defmusic = '/hello-song.flv'	// default music

function startTimer() {
	counter = secs;
	timer = setInterval(function(){myTimer()}, 1000*10*60);
	timersecs = setInterval(function(){countdown()}, 1000);	// every second
}

function restartTimer(mins) {
	clearInterval(timer);
	clearInterval(timersecs);
	
	// convert minutes to milliseconds for timer function
	var minutes = document.timerfm.mins.value;
	if (mins > 0)
		minutes = mins;	// override select list option
	
	var interval = minutes * 60 * 1000;
	
	secs = minutes * 60;	// save seconds
	counter = secs;
	
	var m = Math.floor(counter/60);
	var s = counter%60;
	
	timeleft.innerHTML = "Time Remaining: " + m + ":" + s;
	
	resetTime = minutes;	// reset time for myTimer()
	
	//var interval = 10 * 60 * 1000;
	//alert(minutes + " minutes");
	timer = setInterval(function(){myTimer()}, interval);
	timersecs = setInterval(function(){countdown()}, 1000);	// every second
}

function changeSong() {
	var song = document.getElementById('song');
	var filename = document.getElementById('musicfile').value;
	var dir = "/";
	
	if (filename == "")
		return;
		
	filename = dir + filename;	// store in root
	
	setupPlayer(filename);
	song.innerHTML = "Song: " + filename;
}

function stopTimer() {
	clearInterval(timer);
	clearInterval(timersecs);
}

/* Should add a resume button
*/

function onPageLoad() {
	resize();
	startTimer();
	setupPlayer(defmusic);
}

function setupPlayer(filename) {

	// uses jw player for playing media files (eg. mp3,mp4,flv,etc.)
	jwplayer('mediaplayer').setup({
		'flashplayer': '/jwplayer/player.swf',
		'id': 'playerID',
		'width': '480',
		'height': '270',
		'file': filename
	});
	
	var playlist = new Array();	// create an array
	//playlist[0] = new PlaylistItem();	// create a PlaylistItem object
	playlist[0] = { file: "/media/WATCHMEN Muse 'Take a Bow'.mp4" };
	//playlist[1] = new PlaylistItem();
	playlist[1] = { file: '/media/hello-song.flv' };
	/* OR
	playlist[0] = new PlaylistItem();
	playlist[0].file = "/media/name-of-file.mp4";
	*/
	
	// Load a playlist into the player
	jwplayer('mediaplayer').load(playlist);	// pass an array of PlaylistItem objects
	/*
	jwplayer('mediaplayer').load([ {file: "/media/WATCHMEN Muse 'Take a Bow'.mp4" }, 
	                               {file: '/media/hello-song.flv'}
								 ]);*/
}
  
/* myTimer is the function that is executed once the countdown finishes.
*/
function myTimer() {
	restartTimer(resetTime);
	
	var state = jwplayer('mediaplayer').getState();
	
	if (state != "PLAYING")
		jwplayer('mediaplayer').play();
}

// update time left every second
function countdown() {
	var mins = document.timerfm.mins.value;
	
	var timeleft = document.getElementById('timeleft');
	var left = mins * 1000;
	
	counter--;
	var m = Math.floor(counter/60);
	var s = counter%60;
	
	timeleft.innerHTML = "Time Remaining: " + m + ":" + s;
}

function resize() {
	window.resizeTo(400, 500);
}

/*
function setupOption() {
	// create a Select object to display a drop-down list
	// must use createElement()
	var selectmins = document.createElement("select");
	selectmins.id = "mins";
	var opt;
	var i;
	
	var frm = document.forms[0];
	
	// add several options to the list
	for (i=0; i<20; i++) {
		opt = document.createElement("option");
		opt.value = i+1;
		opt.text = i+1;
		selectmins.add(opt);
	}
	
	// the select object is complete, add to the form
	document.timerfm.appendChild(selectmins);
}*/
</script>



</body>
</html>