from pyA20.gpio import gpio
from pyA20.gpio import port

#import RPi.GPIO as GPIO
import dht22
import time
import datetime

# initialize GPIO
#gpio.setwarnings(False)
#gpio.setmode(GPIO.BCM)
PIN2 = 15
gpio.init()
#gpio.cleanup()


# read data using pin 14
instance = dht22.DHT22(pin=PIN2)

count = 0
valid = 0
while (not valid or count > 5):
  count += 1
  result = instance.read()
  valid = result.is_valid()
  if result.is_valid():
    print("%.1f;%.1f" % (result.temperature,result.humidity))
  else:
    time.sleep(5)