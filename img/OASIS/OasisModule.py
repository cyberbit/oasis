import smbus
import time
import picamera

# Initialize the camera appropriately
camera = picamera.PiCamera()
camera.resolution = (3280, 2464)
camera.start_preview()

# Options for the i2c connection
bus = smbus.SMBus(1)
SLAVE_ADDRESS = 0x04
COMMAND_OFFSET = 0x0

######################################
##WORKING FUNCTIONS###################
######################################

def writeNumber(command):
	bus.write_byte(SLAVE_ADDRESS, command)
	return -1

def readNumber():
	 number = bus.read_byte(SLAVE_ADDRESS)
	 return number
	 
def instructions():
	print "1: platform forward"
	print "2: platform backward"
	print "3: ring forward"
	print "4: ring backward"
	print "5: reset system"
	print "6: platformStateReset"
	print "7: slowTurn"
	print "100: standard movement"
	print "101: fine movement"
	print "102: very fine movement"
	print "200: test1"
	print "201: test2"
	print "202: test3"
	print "254: WARNING: CLEAR EEPROM"
	
	 
while True:
	instructions()
	command = input("Commands range from 0-254: ")
	if not command:
		continue
		
	writeNumber(command)
	print "RPI -> Arduino ::", command
	time.sleep(1)
	if (command == 5):
		time.sleep(14)