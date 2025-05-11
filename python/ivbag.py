import firebase_admin
from firebase_admin import credentials, db
import serial
import time

# --- Init Firebase ---
cred = credentials.Certificate("serviceAccountKey.json")
firebase_admin.initialize_app(cred, {
    'databaseURL': 'https://ivbag-c6fd2-default-rtdb.firebaseio.com/'  # replace with your DB URL
})

# --- Init GSM ---
gsm = serial.Serial("/dev/ttyAMA0", baudrate=115200, timeout=1)
time.sleep(2)

def send_sms(number, message):
    gsm.write(b'AT\r')
    time.sleep(0.5)
    gsm.write(b'AT+CMGF=1\r')
    time.sleep(0.5)
    gsm.write(f'AT+CMGS="{number}"\r'.encode())
    time.sleep(0.5)
    gsm.write(f"{message}\x1A".encode())  # Ctrl+Z
    time.sleep(5)
    print(f"Message sent to {number}")

# --- Fetch data from Firebase ---
ref = db.reference("bag_info")
bags = ref.get()

# Dictionary to track the last message sent time for each patient
last_sent_time = {}

# --- Monitor IV Bag Levels ---
while True:
    ref = db.reference("bag_info")
    bags = ref.get()

    for mac, data in bags.items():
        level = float(data.get("ivbag_level", 100))
        status = data.get("active_status", 0)
        contact = data.get("contact", "")
        name = data.get("name", "Patient")  # Default to 'Patient' if name is missing
        station = data.get("station", "Unknown Station")  # Default to 'Unknown Station' if missing
        room = data.get("room", "Unknown Room")  # Default to 'Unknown Room' if missing

        # Check if the level is critical and if a message was sent recently
        if level < 20:
            current_time = time.time()
            if mac not in last_sent_time or (current_time - last_sent_time[mac]) > 120:  # 120 seconds = 2 minutes
                send_sms(contact, f"Hi, {name}! Your IV Bag/Dextrose is almost empty. With {level}% left from {station} {room}.")
                last_sent_time[mac] = current_time  # Update the last sent time
            else:
                print(f"Message already sent recently to {name}, skipping.")
        else:
            print(f"No critical bag level found for {name}")

    time.sleep(5)  # Wait for 5 seconds before checking again
