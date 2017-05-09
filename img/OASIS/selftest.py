import sys
import smbus
import time
import picamera
import os.path

# Initialize the camera appropriately
camera = picamera.PiCamera()
camera.resolution = (600, 400)
directory = "/home/pi/scantest"

# Options for the i2c connection
bus = smbus.SMBus(1)
SLAVE_ADDRESS = 0x04
COMMAND_OFFSET = 0x0

# returned number
retVal = 0;

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
##TEST1###############################
######################################

print "Test 1 start - move the platform"
writeNumber(105)
print "RPI -> Arduino ::", 105
time.sleep(11)

retVal = readNumber()
print "Arduino: I have given you the value ", retVal
print

if (retVal == 105):
	print "Test 1 sucessful!"
else:
	print "Test 1 unsuccessful"
	
######################################
##TEST2###############################
######################################

print "Test 2 start - move the ring"
writeNumber(106)
print "RPI -> Arduino ::", 106
time.sleep(17)

retVal = readNumber()
print "Arduino: I have given you the value ", retVal
print

if (retVal == 106):
	print "Test 2 sucessful!"
else:
	print "Test 2 unsuccessful"

######################################
##TEST3###############################
######################################
camera.start_preview()

print "Test 3 start - Take picture and make sure it exists"
testNum1 = 10
testNum2 = 5
fullPath = os.path.join(directory, 'Oasis{0:02}_{1:02}.jpg'.format((testNum1), (testNum2)))
print "The full path is as follows"
print fullPath
camera.capture(fullPath)
time.sleep(1)

if not os.path.exists(fullPath):
	print "Test 3 unsucessful!"
else:
	print "Test 3 successful"