#!/usr/bin/python
import RPi.GPIO as GPIO

#set gpio pin
pin = 10
GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM)

GPIO.setup(pin, GPIO.OUT)
GPIO.output(pin, GPIO.HIGH)
#GPIO.cleanup()