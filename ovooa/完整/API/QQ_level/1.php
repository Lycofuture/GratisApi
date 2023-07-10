<?php
require './Level.php';

$Level = new Level();

echo Level(0);

function Level(Int $Level){
    if($Level == 0){
        return '☆';
    }
    $h = Intval($Level / 64);
    $Level_h = Intval($Level - ($h * 64));
    $t = Intval($Level_h / 16);
    $Level_t = Intval($Level_h - ($t * 16));
    $y = Intval($Level_t / 4);
    $Level_y = Intval($Level_t - ($y * 4));
    $x = Intval($Level_y);
    $Level_t = Intval($Level_h - $x);
    for($i = 0 ; $i < $h ; $i++){
        $String .= '👑';
    }
    for($i = 0 ; $i < $t ; $i++){
        $String .= '☀️';
    }
    for($i = 0 ; $i < $y ; $i++){
        $String .= '🌙';
    }
    for($i = 0 ; $i < $x ; $i++){
        $String .= '⭐';
    }
    return $String;
}