from datetime import datetime
import firebase_admin
from firebase_admin import credentials, messaging
import re
import mysql.connector

cred = credentials.Certificate('../config/sadhana-firebase.json')
firebase_admin.initialize_app(cred)

text_file = open("../config/app_local.php", "r")
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
query = "SELECT firebaseUserToken FROM users WHERE firebaseUserToken IS NOT NULL"
cursor.execute(query)

# Fetch the results
# TODO multiple users: https://firebase.google.com/docs/cloud-messaging/send-message
results = cursor.fetchall()
for row in results:
    # This registration token comes from the client FCM SDKs.
    user_registration_token = row[0]

    current_time = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    message = messaging.Message(
        notification=messaging.Notification(
            title='😱 Sadhana emlézetető',
            body=current_time + '\nMa még nem töltötted ki a sadhana infókat!',
        ),
        token=user_registration_token,
    )

    # Send the message
    response = messaging.send(message)
    print(response)

# Close the cursor and connection
cursor.close()
cnx.close()

