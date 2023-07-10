<?php
$d = $_GET['songId'];
$gurl="http://music.163.com/song/media/outer/url?id=$d";
die(header("Location: $gurl"));

?>