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
	print 'OasisScan.py -i /home/pi/scantest -t "very fine"'
	sys.exit(2)
	
#Put arguments into local variables
for opt, arg in opts:
	if opt == '-i':
		inputPath = arg
	elif opt == '-t':
		scanType = arg

#Assign rotational values for capture
if scanType == 'standard':
	platformDelay = 1.2
	maxPlatform = 5
	maxRing =  5
	writeNumber(100)
elif scanType == 'fine':
	platformDelay = 0.5;
	maxPlatform = 10
	maxRing = 5
	writeNumber(101)
elif scanType == 'very fine':
	platformDelay = 0;
	maxPlatform =  20
	maxRing = 5
	writeNumber(102)
		
#Check if not at default position
writeNumber(5)
time.sleep(15)

#get directory name
loop = 1
while True:
	directory = os.path.join(inputPath, 'scan_{0:03}'.format(loop))
	if not os.path.exists(directory):
	    os.makedirs(directory)
	    print directory
	    break
	loop = loop + 1
	
# Initialize the camera appropriately
camera = picamera.PiCamera()
camera.resolution = (600, 400)
	
#Camera and capture loop	
while (flag == False):
	##Take picture
	camera.start_preview()
	##Move platform
	command = 7
	writeNumber(command)
	
	#While platform still moving
	while (platformState != maxPlatform):
		pictureName = os.path.join(directory, 'Oasis{0:02}_{1:02}.jpg'.format((ringState), (platformState)))
		print pictureName
		camera.capture(pictureName)
		platformState = platformState + 1
		time.sleep(platformDelay)

	platformState = 0
	
	##break out when needed
	if (ringState == maxRing):
		flag = True
		print "Scan done!"
		camera.stop_preview()
		command = 5
		writeNumber(command)
		time.sleep(10)
	else:
		##Move ring
		ringState = ringState + 1
		command = 3
		writeNumber(command)
		print "ringState Number: ", ringState
		time.sleep(1)
