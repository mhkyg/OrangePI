<?

class One_Wire_Device {
  private $gpio;
	function __construct(PHP_GPIO $gpio){

		$this->gpio = $gpio;
  
	}
  
  /**
   * $values_with_timeings = array(array("value"=>1 or 0,"delay_after_microsec"=>microseconds) );
   */
  function sendBytes($port,$values_with_timeings){
    
    foreach ($values_with_timeings as $data) {
      
    	$this->gpio->setGPIOvalue($port,$data["value"]);
      usleep($data["delay_after_microsec"]);
    }
    
  }
  
  /**
   *Reads until maximum of 100 iterations without change
   */
  
  function readBytes($port){
   
    
    $last_gpio_state = 0;
    $data = array();
    $index = 0;
    $ts = microtime(true);
    $max_unchanged = 100;
    /*
    while ($unchanged < $max_unchanged) {
      $current = $this->gpio->getGPIOvalue($port);
      $read++;
      if ($last_gpio_state == $current) {
        if ($current == 1 ) {
          $data[$index]++;	
        }            
      	$unchanged++;        
      }else{
        $index++;        
        $unchanged = 0;
        $last_gpio_state =  $current;
      }
    }
    */
    $raw_data = array();
    $read = 0;
    while ($unchanged < $max_unchanged) {
      $raw_data[$read] = $current = $this->gpio->getGPIOvalue($port);      
      $read++;
      if ($last_gpio_state == $current) {
        $unchanged++;
      }else{
        $unchanged = 0;
        $last_gpio_state =  $current;
        
      }
    }
    $total_time = (microtime(true)-$ts);
    $raw_data = array_slice($max_unchanged, 0, -100,true);
    
    echo "<pre>";
    var_dump($raw_data,$total_time,count($raw_data),$total_time/count($raw_data) );
    echo "</pre>";
    
    
    
    //unset($data[$index]);

    //Data should contain only odd numbers without missing
    end($data);
    /*if ((count($data)*2)+1 != key($data) ) {
      throw new Exception("missed a bit (Received bits: ".count($data)." last_key:".key($data));    	
    }   */
 
    $lowest = min($data);
    $highest = max($data);
    $middle = ($highest+ $lowest)/2; 
    
    $bit_array = array();
    foreach ($data as $key=>$value) {
      $bit_array[$key] = ($value>=$middle)?(1):(0);	
    }

    //Convert bits to bytes
    return $this->convertBitsToBytes($bit_array);
  }
  
  protected function convertBitsToBytes($bit_array){
    $bit_string = implode("", $bit_array);
    for ($i=0;$i<(count($bit_array)/8 ); $i++) {
      $byte_as_bin = substr($bit_string, $i*8, 8);
      //debug
      
      var_dump($byte_as_bin);
    
      $result[] = bindec($byte_as_bin) ;	
    }
    
    return $result;
  }
  
}
