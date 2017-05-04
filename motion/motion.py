#!/usr/bin/python3
# coding=UTF-8
#import RPi.GPIO as GPIO_detector #raspberry version
from pyA20.gpio import gpio
import time
import datetime
import locale
from char_lcd2 import OrangePiZero_CharLCD as LCD

def file_get_contents(filename):
    with open(filename) as f:
        return f.read()

# Orange Pi pin configuration:
lcd_rs        = 14  
lcd_en        = 10
lcd_d4        = 12
lcd_d5        = 11
lcd_d6        = 6
lcd_d7        = 0
lcd_backlight = 13

# Define LCD column and row size for 16x2 LCD.
lcd_columns = 16
lcd_rows    = 2



#GPIO_detector.setmode(GPIO_detector.BCM)
PIR_PIN = 7
#GPIO_detector.setup(PIR_PIN, GPIO_detector.IN)

lcd = LCD.OrangePiZero_CharLCD(lcd_rs, lcd_en, lcd_d4, lcd_d5, lcd_d6, lcd_d7,
                           lcd_columns, lcd_rows, lcd_backlight)
lcd.create_char(0,[	2,4,14,1,15,17,15,0]); # á
lcd.create_char(1,[	2,4,14,17,30,16,14,0]); # é
lcd.create_char(2,[	10,0,0,17,17,17,14,0]); # ü
lcd.create_char(3,[	10,0,14,17,17,17,14,0]); # ö
lcd.create_char(4,[	5,10,14,17,17,17,14,0]); # ő

def MOTION():
  week_days = ["Vas\x00rnap","H\x01tf\x04","Kedd","Szerda","Cs\x02t\x03rt\x03k","P\x01ntek","Szombat"]
  #het_napjai = ["Kedd","Szerda","Csütörtök","P\x01ntek","Szombat","Vas\x00rnap","\x00\x01\x02\x03\x04"]
  lcd.clear()
  #print("lite on")
  lcd.set_backlight(0)        
  lcd_text = file_get_contents("../data/kijelzo.txt");
  for x in range(0, 4):
    if (x % 2) == 0 :
      time = datetime.datetime.now().strftime('%Y-%m-%d') +' '+ datetime.datetime.now().strftime('%H:%M:%S') +'\n'+ week_days[int(datetime.datetime.now().strftime('%w'))] ;
      lcd.clear()
      lcd.message(time);
      #print(ido);
    else:
      lcd.clear()                             
      lcd.message( lcd_text);
      #print(kijelzo_szoveg);
    time.sleep(5.0);
      
  
  print(lcd_text);
  

  lcd.clear()
  lcd.set_backlight(1)
  
  
gpio.setcfg(PIR_PIN, gpio.INPUT) 
gpio.pullup(PIR_PIN, gpio.PULLUP) 
# endless loop
try:
  #GPIO_detector.add_event_detect(PIR_PIN, GPIO_detector.RISING, callback=MOTION, bouncetime=300)
  
  print("event atached start loop");
  while 1:
    if (gpio.input(PIR_PIN)===1):
       MOTION()
    else:
      time.sleep(0.1)
    
except KeyboardInterrupt:
  print(" Quit")
  lcd.set_backlight(1)
  #GPIO_detector.cleanup()
except:               
  print("Other error");
  lcd.set_backlight(1)
  #GPIO_detector.cleanup()
               