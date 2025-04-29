from flask import Flask, jsonify, request
import pymysql
from ping3 import ping

app = Flask(__name__)

# Database connection details
DB_CONFIG = {
    "host": "localhost",
    "user": "root",
    "password": "",
    "database": "ivbag_data"
}

# Function to ping an IP address
def is_ip_active(ip):
    try:
        response = ping(ip, timeout=1)  # Ping with a 1-second timeout
        return response is not None
    except Exception as e:
        app.logger.error(f"Error pinging IP {ip}: {e}")
        return False

@app.route('/fetch_data', methods=['GET'])
def fetch_data():
    fetch_updates = request.args.get('fetch_updates', default=False, type=bool)
    connection = pymysql.connect(**DB_CONFIG)
    cursor = connection.cursor(pymysql.cursors.DictCursor)

    try:
        query = "SELECT id, name, active_status, ivbag_level, backflow, room, ipaddress FROM bag_info"
        cursor.execute(query)
        rows = cursor.fetchall()

        data = []
        for row in rows:
            row['ip_active'] = is_ip_active(row['ipaddress'])
            data.append(row)

        return jsonify(data)
    except Exception as e:
        app.logger.error(f"Error fetching data: {e}")
        return jsonify({"error": "Failed to fetch data"}), 500
    finally:
        cursor.close()
        connection.close()

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)
