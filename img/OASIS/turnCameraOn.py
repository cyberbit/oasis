import picamera
import time

camera = picamera.PiCamera()
camera.resolution = (600,400)
camera.start_preview()
time.sleep(120)
camera.stop_preview()
