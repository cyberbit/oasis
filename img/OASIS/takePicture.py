import picamera

camera = picamera.PiCamera()
# camera.resolution = (600, 400)
# camera.start_preview()
pictureName= "/home/pi/scantest/testimg.jpg"
camera.capture(pictureName)
print(pictureName)