from datetime import datetime
import firebase_admin
from firebase_admin import auth, credentials, messaging
import re
import os
import mysql.connector

## runned by cron as export PYTHONPATH=/var/www/clients/client10/web154/home/1108_sadhana/.local/lib/python3.9/site-packages:$PYTHONPATH && /usr/bin/python3 /var/www/clients/client10/web154/home/1108_sadhana/web/api/python/reminders.py

current_file_path = os.path.realpath(__file__)
config_path = os.path.join(os.path.dirname(current_file_path), '..', 'config')

cred = credentials.Certificate(config_path + '/sadhana-firebase.json')
firebase_admin.initialize_app(cred)

text_file = open(config_path + "/app_local.php", "r")
data = text_file.read()
text_file.close()

# remove all whitespace and newlines
data = data.replace(" ", "").replace("\n", "")
match = re.search(r"'username'=>'(.*?)',\s*'password'=>'(.*?)',\s*'database'=>'(.*?)'", data)

# Extract the username and password from the match object
if match:
    username = match.group(1)
    password = match.group(2)
    database = match.group(3)

# Connect to the database
cnx = mysql.connector.connect(
    host="localhost",
    user=username,
    password=password,
    database=database
)

# Create a cursor object
cursor = cnx.cursor()

# Execute a query
query = "SELECT firebaseUserToken FROM users WHERE firebaseUserToken IS NOT NULL AND notificationTime IS NOT NULL AND notificationTime = HOUR(DATE_SUB(NOW(), INTERVAL 1 HOUR)) AND id NOT IN (SELECT user_id FROM sadhanas WHERE date = DATE_FORMAT(NOW(), '%Y-%m-%d'))"
query = "SELECT firebaseUserToken FROM users WHERE firebaseUserToken IS NOT NULL"
cursor.execute(query)
results = cursor.fetchall()

registration_tokens = []
for row in results:
    registration_tokens.append(row[0])

if len(registration_tokens) == 0:
    print('No tokens found for this hour')
    exit()


current_time = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
message = messaging.MulticastMessage(
    notification = messaging.Notification(
        title='😱 Sadhana emlékető',
        body=current_time + '\nMa még nem töltötted ki a sadhana infókat!',
    ),
    tokens=registration_tokens,
)

# Send the message to all registration tokens
batch_response = messaging.send_multicast(message)
for idx, response in enumerate(batch_response.responses):
    if response.exception:
        print(f"Message {idx} failed with error: {response.exception}")
    else:
        print(f"Message {idx} successfully sent with message ID: {response.message_id}")

# Close the cursor and connection
cursor.close()
cnx.close()

