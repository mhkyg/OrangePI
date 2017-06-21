<?

require_once 'gpio.php';

require_once 'dht22.php';


$gpio =  new PHP_GPIO("/sys/class/gpio");

//$one_wire = new One_Wire_Device($gpio);
$dht22 = new PHP_DHT22($gpio,15);

var_dump($dht22->getTemperatureAndHumidity());
