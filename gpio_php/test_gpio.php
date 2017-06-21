<?
require_once 'gpio.php';
$gpio =  new PHP_GPIO("/sys/class/gpio");
for ($i=0;$i<5 ;$i++ ) {
  $gpio->setGPIOvalue(13,$i % 2);
  sleep(1);	
}


