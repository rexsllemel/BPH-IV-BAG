import cv2
import numpy as np
import time
import matplotlib.pyplot as plt
from collections import deque

cap = cv2.VideoCapture(0)
if not cap.isOpened():
    print("Webcam not detected.")
    exit()

pixels_per_mm = 10  # Calibration value
prev_center = None
prev_time = time.time()

# Create a window for calibration
cv2.namedWindow("Calibration")

# Callback function for the trackbars (does nothing but required by OpenCV)
def nothing(x):
    pass

# Add trackbars for calibration
cv2.createTrackbar("Pixels per mm", "Calibration", 10, 100, nothing)  # Default 10, max 100
cv2.createTrackbar("Kernel Size", "Calibration", 5, 20, nothing)  # Default 5, max 20

# Initialize background subtractor
bg_subtractor = cv2.createBackgroundSubtractorMOG2(history=500, varThreshold=50, detectShadows=False)

# Initialize graph data
interval_history = deque(maxlen=100)  # Store the last 100 intervals
time_history = deque(maxlen=100)  # Store the corresponding time values

fig, ax = plt.subplots()
line, = ax.plot([], [], label="Interval (s)")
ax.set_xlim(0, 10)  # Initial x-axis range
ax.set_ylim(0, 5)  # Initial y-axis range
ax.set_xlabel("Time (s)")
ax.set_ylabel("Interval (s)")
ax.legend()

start_time = time.time()

frame_skip = 2  # Process every 2nd frame to increase speed
frame_count = 0

while True:
    ret, frame = cap.read()
    if not ret:
        break

    frame_count += 1
    if frame_count % frame_skip != 0:
        continue  # Skip frames to increase processing speed

    frame = cv2.resize(frame, (320, 240))  # Reduce frame size for faster processing

    # Crop the middle region of the frame to reduce edge noise
    h, w, _ = frame.shape
    crop_size = 150  # Smaller crop size for faster detection
    cx, cy = w // 2, h // 2
    x1, y1 = cx - crop_size // 2, cy - crop_size // 2
    x2, y2 = cx + crop_size // 2, cy + crop_size // 2
    cropped_frame = frame[y1:y2, x1:x2]

    # Update calibration parameters from the trackbars
    pixels_per_mm = cv2.getTrackbarPos("Pixels per mm", "Calibration")
    kernel_size = cv2.getTrackbarPos("Kernel Size", "Calibration")
    kernel_size = max(1, kernel_size | 1)  # Ensure kernel size is odd and at least 1

    # Apply background subtraction to the cropped frame
    fg_mask = bg_subtractor.apply(cropped_frame)

    # Morphological noise reduction using the calibrated kernel size
    kernel = np.ones((kernel_size, kernel_size), np.uint8)
    fg_mask = cv2.erode(fg_mask, kernel, iterations=1)
    fg_mask = cv2.dilate(fg_mask, kernel, iterations=2)

    # Contour detection
    contours, _ = cv2.findContours(fg_mask, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

    for cnt in contours:
        area = cv2.contourArea(cnt)
        if 10 < area < 150:  # Adjusted area range to filter out smaller and larger noise
            x, y, w_rect, h_rect = cv2.boundingRect(cnt)
            center = (x + w_rect // 2, y + h_rect // 2)

            # Draw detection
            cv2.circle(cropped_frame, center, 5, (0, 255, 255), -1)

            # Interval detection
            if prev_center is not None:
                current_time = time.time()
                dt = current_time - prev_time
                prev_time = current_time
            else:
                dt = 0.0  # Set interval to 0 if no previous center exists

            prev_center = center
            break
    else:
        # If no contours are detected, set interval to 0
        dt = 0.0

    # Update graph data
    elapsed_time = time.time() - start_time
    time_history.append(elapsed_time)
    interval_history.append(dt)

    # Update and redraw the graph dynamically
    line.set_xdata(list(time_history))  # Convert deque to list for Matplotlib
    line.set_ydata(list(interval_history))  # Convert deque to list for Matplotlib
    ax.set_xlim(0, max(time_history) if time_history else 10)
    ax.set_ylim(0, max(interval_history) + 1 if interval_history else 5)
    ax.relim()  # Recalculate limits
    ax.autoscale_view()  # Autoscale the view
    plt.draw()
    plt.pause(0.01)  # Pause to allow the graph to update

    # Always display interval text
    cv2.putText(cropped_frame, f"Interval: {dt:.2f} s", (10, 25),
                cv2.FONT_HERSHEY_SIMPLEX, 0.6, (0, 255, 0), 2)

    # Display results
    cv2.imshow("Droplet Detection", cropped_frame)
    cv2.imshow("Foreground Mask", fg_mask)

    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()

# Display the final graph after the loop ends
plt.show()
