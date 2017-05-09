import smbus
import time
import picamera

# Initialize the camera appropriately
camera = picamera.PiCamera()
camera.resolution = (1200, 600)
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
	 	 
######################################
##SCAN COMMAND INTERFACE##############
######################################

#Initialization
platformState = 0
ringState = 0
time.sleep(2)
flag = False

#Check if not at default position
writeNumber(5)
time.sleep(5)
	

while (flag == False):
	##camera.capture("/home/pi/scantest/Oasis" + ringState + platformState + ".jpg") 
	camera.capture ('/home/pi/scantest/pictures/Oasis{0:02}{1:02}.jpg'.format((ringState), (platformState)))
	
	##{0}{1}.format(unicode(self.ringState),  unicode(self.platformState))
	print ('/home/pi/scantest/pictures/Oasis{0:02}{1:02}.jpg'.format((ringState), (platformState)))
	##Move platform
	command = 1
	writeNumber(command)
	print "RPI -> Arduino ::", command
	# Wait for Arduino
	platformState = platformState + 1
	time.sleep(0.5)
	
	##Move ring
	if (platformState == 21):
		writeNumber(6)
		platformState = 0
		ringState = ringState + 1
		command = 3
		writeNumber(command)
		print "RPI -> Arduino ::", command
		print "ringState Number: ", ringState
		time.sleep(1)
		
	##break out when needed
	if (ringState == 10):
		flag = True
		print "Scan done!"
		camera.stop_preview()
