#!/usr/bin/python3
# Example using a character LCD connected to a Raspberry Pi or BeagleBone Black.
import time
import datetime
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

# BeagleBone Black configuration:
# lcd_rs        = 'P8_8'
# lcd_en        = 'P8_10'
# lcd_d4        = 'P8_18'
# lcd_d5        = 'P8_16'
# lcd_d6        = 'P8_14'
# lcd_d7        = 'P8_12'
# lcd_backlight = 'P8_7'

# Define LCD column and row size for 16x2 LCD.
lcd_columns = 16
lcd_rows    = 2

# Alternatively specify a 20x4 LCD.
# lcd_columns = 20
# lcd_rows    = 4

# Initialize the LCD using the pins above.
lcd = LCD.Adafruit_CharLCD(lcd_rs, lcd_en, lcd_d4, lcd_d5, lcd_d6, lcd_d7,
                           lcd_columns, lcd_rows, lcd_backlight)
datestring = datetime.datetime.now().strftime('%Y-%m-%d %H:%M')
lcd.clear()                           
lcd.message(file_get_contents("../data/lcd.txt") );