#!/usr/bin/python3
# coding=<encoding name>
import RPi.GPIO as GPIO_detector
import time
import datetime
import locale
import Adafruit_CharLCD as LCD

def file_get_contents(filename):
    with open(filename) as f:
        return f.read()

# Raspberry Pi pin configuration:
lcd_rs        = 24  # Note this might need to be changed to 21 for older revision Pi's.
lcd_en        = 23
lcd_d4        = 9
lcd_d5        = 11
lcd_d6        = 10
lcd_d7        = 18
lcd_backlight = 8

# Define LCD column and row size for 16x2 LCD.
lcd_columns = 16
lcd_rows    = 2

#locale.setlocale(locale.LC_TIME, "hu_HU") # hungarian

GPIO_detector.setmode(GPIO_detector.BCM)
PIR_PIN = 7
GPIO_detector.setup(PIR_PIN, GPIO_detector.IN)

lcd = LCD.Adafruit_CharLCD(lcd_rs, lcd_en, lcd_d4, lcd_d5, lcd_d6, lcd_d7,
                           lcd_columns, lcd_rows, lcd_backlight)
lcd.create_char(0,[	2,4,14,1,15,17,15,0]); # á
lcd.create_char(1,[	2,4,14,17,30,16,14,0]); # é
lcd.create_char(2,[	10,0,0,17,17,17,14,0]); # ü
lcd.create_char(3,[	10,0,14,17,17,17,14,0]); # ö
lcd.create_char(4,[	5,10,14,17,17,17,14,0]); # ő

def MOTION(PIR_PIN):
  het_napjai = ["Vas\x00rnap","H\x01tf\x04","Kedd","Szerda","Cs\x02t\x03rt\x03k","P\x01ntek","Szombat"]
  #het_napjai = ["Kedd","Szerda","Csütörtök","P\x01ntek","Szombat","Vas\x00rnap","\x00\x01\x02\x03\x04"]
  lcd.clear()
  #print("lite on")
  lcd.set_backlight(0)        
  kijelzo_szoveg = file_get_contents("/var/www/kijelzo.txt");
  for x in range(0, 4):
    if (x % 2) == 0 :
      ido = datetime.datetime.now().strftime('%Y-%m-%d') +' '+ datetime.datetime.now().strftime('%H:%M:%S') +'\n'+ het_napjai[int(datetime.datetime.now().strftime('%w'))] ;
      lcd.clear()
      lcd.message(ido);
      #print(ido);
    else:
      lcd.clear()                             
      lcd.message( kijelzo_szoveg);
      #print(kijelzo_szoveg);
    time.sleep(5.0);
      
  
  print(kijelzo_szoveg);
  
  #print("lite off")
  lcd.clear()
  lcd.set_backlight(1)
  
  
  
# endless loop
try:
  GPIO_detector.add_event_detect(PIR_PIN, GPIO_detector.RISING, callback=MOTION, bouncetime=300)
  print("event atached start loop");
  while 1:
    time.sleep(15)
except KeyboardInterrupt:
  print(" Quit")
  lcd.set_backlight(1)
  GPIO_detector.cleanup()
except:               
  print("Other error");
  lcd.set_backlight(1)
  GPIO_detector.cleanup()
               