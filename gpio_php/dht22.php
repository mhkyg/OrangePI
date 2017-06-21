<?

class PHP_DHT22{
  private $gpio;
	function __construct(One_Wire_Device $one_wire,$port_number){

		$this->one_wire = $one_wire;
    $this->port_number = $port_number;
	}
  
  /**
   *return array("temperature"=>float,"humidity"=>float);
   */
                                         
  function getTemperatureAndHumidity(){
    //Busy wait to wake cpu from sleep
    $ts = microtime(true);
    $i=0;
    while ($ts+1>microtime(true) ) {
    	$i++   ;
    }

    $this->one_wire->sendBytes($this->port_number,array(array("value"=>1,"delay_after_microsec"=>50000) 
                                                       ,array("value"=>0,"delay_after_microsec"=>0)
                                                       //,array("value"=>1,"delay_after_microsec"=>20000)
                                                        ));
                                                      
    $res = $this->one_wire->readBytes($this->port_number);   	
    $generated_checksum = ($res[0]+$res[1]+$res[2]+$res[3]) % 256;
    $temperature_sign = 1;
    $templerature_first_byte = $res[2];
    if ($templerature_first_byte>127) {
    	$temperature_sign = -1; 
      $templerature_first_byte-=128;     
    }
      
    if ($generated_checksum !== $res[4] ) {

    	throw new Exception("Checksum error (generated:".$generated_checksum." received:".$res[4].")");
    }


    return array("temperature"=>$temperature_sign*(($templerature_first_byte )*256+$res[3])/10,"humidity"=>($res[0]*256+$res[1])/(10));
  }
  
  
  
  
}