import RPi.GPIO as GPIO
import time
import firebase_admin
from firebase_admin import credentials, db

# Firebase initialization
cred = credentials.Certificate("serviceAccountKey.json")  # Replace with your Firebase credentials file
firebase_admin.initialize_app(cred, {
    'databaseURL': 'https://ivbag-c6fd2-default-rtdb.firebaseio.com/'  # Replace with your Firebase database URL
})

RELAY_PIN = 4  # GPIO4

GPIO.setmode(GPIO.BCM)
GPIO.setup(RELAY_PIN, GPIO.OUT)

try:
    while True:
        # Fetch data from Firebase
        bag_info = db.reference('/bag_info').get()
        for mac_address, data in bag_info.items():
            if data.get('emergencyBtn') == 1:
                print(f"Emergency detected for MAC Address: {mac_address}")
                
                # Relay control logic
                print("Relay ON (LOW)")
                GPIO.output(RELAY_PIN, GPIO.LOW)
                time.sleep(0.1)  # 200 milliseconds

                print("Relay OFF (HIGH)")
                GPIO.output(RELAY_PIN, GPIO.HIGH)
                time.sleep(0.1)

                print("Relay ON again (LOW)")
                GPIO.output(RELAY_PIN, GPIO.LOW)
                time.sleep(0.5)

                print("Relay OFF (HIGH)")
                GPIO.output(RELAY_PIN, GPIO.HIGH)
                time.sleep(0.1)

                print("Relay ON again (LOW)")
                GPIO.output(RELAY_PIN, GPIO.LOW)
                time.sleep(0.1)

        # Delay before checking again
        time.sleep(1)

except KeyboardInterrupt:
    print("Exiting program...")

finally:
    GPIO.cleanup()