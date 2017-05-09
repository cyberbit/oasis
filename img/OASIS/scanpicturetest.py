#!/usr/bin/python3

import sys
import getopt
import smbus
import time
import picamera
import os.path


#python OasisScan.py -i /home/pi/scantest -t "very fine" 

######################################
##WORKING FUNCTIONS###################
######################################

def writeNumber(command):
	bus.write_byte(SLAVE_ADDRESS, command)
	return -1

def readNumber():
	 number = bus.read_byte(SLAVE_ADDRESS)
	 return number
	 	 
######################################
##SCAN COMMAND INTERFACE##############
######################################



# Options for the i2c connection
bus = smbus.SMBus(1)
SLAVE_ADDRESS = 0x04
COMMAND_OFFSET = 0x0

#Initialization
platformState = 0
ringState = 0
time.sleep(2)
flag = False

#Get input commandline arguments
inputPath = ''
scanType = ''

#Make sure there are 2 command line arguments
try:
	opts, args = getopt.getopt(sys.argv[1:], "i:t:",["ifile=", "stype="])
except getopt.GetoptError:
	print 'OasisScan.py -i <inputfile> -t <scan type>'
	print 'OasisScan.py -i /home/pi/pythonTests -t "very fine"'
	sys.exit(2)
	
#Put arguments into local variables
for opt, arg in opts:
	if opt == '-i':
		inputPath = arg
	elif opt == '-t':
		scanType = arg

#Assign rotational values for capture
if scanType == 'standard':
	maxPlatform = 4
	maxRing =  4
	writeNumber(100)
if scanType == 'fine':
	maxPlatform = 10
	maxRing = 5
	writeNumber(101)
if scanType == 'very fine':
	maxPlatform =  20
	maxRing = 10 
	writeNumber(102)
		
#Check if not at default position
writeNumber(5)
time.sleep(5)

loop=0

while True:
	directory = os.path.join(inputPath, 'scan_{0:03}'.format(loop))
	if not os.path.exists(directory):
	    os.makedirs(directory)
	    break
	loop = loop + 1
	
# Initialize the camera appropriately
camera = picamera.PiCamera()
camera.resolution = (640, 480)
camera.start_preview()

pictureName = os.path.join(directory, 'Oasis{0:02}_{1:02}.jpg'.format((ringState), (platformState)))
print pictureName
camera.capture(pictureName)