<?

$res = `/usr/bin/python ~/OrangePIStuff/DHT22/dht22_read.py`;
require_once __DIR__.'/lcd_szoveg_generator.php';
$lcd = new LCD_Szoveg_Generator($res);
$lcd->generate(__DIR__."/../data/kijelzo.txt");