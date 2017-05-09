import picamera

camera = picamera.PiCamera()
camera.resolution = (600, 400)
camera.start_preview()
pictureName= "/home/pi/scantest/Oasis_2.jpg"
camera.capture(pictureName)