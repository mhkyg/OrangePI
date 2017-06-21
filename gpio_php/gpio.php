<?

class PHP_GPIO{
  private $basepath,$port_direction_cache =array(),$export_cache=array(),$file_handler_cache =array();
  
  /**
   * $basepath
   */                              
	function __construct($basepath){
		$this->basepath = rtrim($basepath, "/") ;

	}
  /**
   * $port_number: 0-16
   *
   */
   
  
  private function initGPIO($port_number){
  	//only exportit if it's already not exported
    //if (!file_exists($this->basepath."/gpio".$port_number)) {
    /*
    if (empty($this->export_cache[$port_number])) {
    	file_put_contents($this->basepath."/export", $port_number)  ;
      $this->export_cache[$port_number] = true;
    }
    */
    if (empty($this->file_handler_cache[$port_number]) ) {
      file_put_contents($this->basepath."/export", $port_number)  ;
    	$this->file_handler_cache[$port_number] = fopen($this->basepath."/gpio".$port_number."/value", "r+");
    }
    
  }
  
  /**
   * $value = bool
   * $port_number: 0-16
   */
  function setGPIOvalue($port_number,$value) {    
    $this->initGPIO($port_number);
    if ($this->port_direction_cache[$port_number]!=="out") {
      file_put_contents($this->basepath."/gpio".$port_number."/direction", "out");
      //echo "\nsetting_out:".$port_number;
      $this->port_direction_cache[$port_number]="out";	
    }
    fseek($this->file_handler_cache[$port_number], 0);
    fwrite($this->file_handler_cache[$port_number],(int)(bool)$value, 1);
    //file_put_contents($this->basepath."/gpio".$port_number."/value", (int)((bool)$value ) );
    
    //echo "\nout: ".$port_number."->".$value;
        
  }
  /**
   * $value = bool
   * $port_number: 0-16
   */  
  function getGPIOvalue($port_number){
    $this->initGPIO($port_number);
  	
    if ($this->port_direction_cache[$port_number]!=="in") {
      file_put_contents($this->basepath."/gpio".$port_number."/direction", "in");
      $this->port_direction_cache[$port_number]="in";
      //echo "\nseting_in:".$port_number;
    } 
    fseek($this->file_handler_cache[$port_number], 0);
    return (bool)fread($this->file_handler_cache[$port_number],1);  
    //return (bool)(file_get_contents($this->basepath."/gpio".$port_number."/value"));
  }
  /**
   * delay = delay between reads in microsec
   * $port_number: 0-16
   * return (int) 0-255 
   */    
  function getGPIOBits($port_number,$delay){
    $res = 0;
    //$pow_2 = array(0,1,2,4,8,16,32,64,128);
    for ($i=0;$i<8 ;$i++ ) {
    	$res = $pow_2[$i] * $this->getGPIOvalue($port_number);
      usleep($delay);
    }
    return $res;  	
  }
  
  /**
   * $port_number: 0-16
   * return array("results"=>array of bool or null,"total_time_sec"=>float);
   * 
   **/
   
  
  function readContinous($port_number,$max_unchanged = 100){
  	$last_gpio_state = 0;
    $data = array();
    $index = 0;
    $ts = microtime(true);
    
    
    $raw_data = array();
    $read = 0;
    while ($unchanged < $max_unchanged) {
      $raw_data[$read] = $current = $this->getGPIOvalue($port_number);      
      $read++;
      if ($last_gpio_state == $current) {
        $unchanged++;
      }else{
        $unchanged = 0;
        $last_gpio_state =  $current;
        
      }
    }
    $total_time = (microtime(true)-$ts);
    //$raw_data = array_slice($max_unchanged, 0, -$max_unchanged,true);
    
    return  array("results"=>$raw_data,"total_time_sec"=>$total_time);
    
  }
  
  
  /**
   * $values_with_timeings = array(array("value"=>1 or 0,"delay_after_microsec"=>microseconds:int) );
   */
  function sendBits($port,$values_with_timeings){
    
    foreach ($values_with_timeings as $data) {
      
    	$this->setGPIOvalue($port,$data["value"]);
      usleep($data["delay_after_microsec"]);
    }
    
  }  
  
  
}
