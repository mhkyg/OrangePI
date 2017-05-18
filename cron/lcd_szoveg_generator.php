<?

class LCD_Szoveg_Generator{
  private $dht22kimenet;
	function __construct($dht22kimenet){
		$this->dht22kimenet = $dht22kimenet;
	}
  
  function generate($kijelzo_file_name){
    $data =  explode("\n",$this->dht22kimenet);
    $exp = function($item){
      $parts = explode(";",$item);
      
      return $parts;
    };  
    $data = array_map($exp, $data);
    //Ha nincs hiba
    //$kijelzo_file_name = __DIR__."/kijelzo.txt";
    $kijelzo_max_hossz = 16;  
    /*$elso_str = "ki:".str_pad($data[1][1],5," ",STR_PAD_LEFT)."C";
    $masodik_str = $data[1][2]."%";   
    $kijelzo_string = $elso_str.str_pad($masodik_str,$kijelzo_max_hossz-strlen($elso_str)," " ,STR_PAD_LEFT );
    */
    $elso_str = "be:".str_pad($data[0][0],5," ",STR_PAD_LEFT)."C";
    $masodik_str = $data[0][1]."%";
    
    
    $kijelzo_string.= "\n".$elso_str.str_pad($masodik_str,$kijelzo_max_hossz-strlen($elso_str)," " ,STR_PAD_LEFT );
    //$kijelzo_string.= "\n".date("m-d H:i");
    file_put_contents($kijelzo_file_name, $kijelzo_string );   
  //$lcd = `/usr/bin/python3 /var/www/lcd_update.py`;  
  }
  
}
