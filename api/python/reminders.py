from datetime import datetime
import firebase_admin
from firebase_admin import messaging
import os

os.environ["GOOGLE_APPLICATION_CREDENTIALS"] = "../config/sadhana-d0c2c-firebase-adminsdk-d5iaz-922236c4a7.json"

default_app = firebase_admin.initialize_app()

# This registration token comes from the client FCM SDKs.
registration_token = 'USER_REG_TOKEN'

# Get the current time
current_time = datetime.now().strftime('%Y-%m-%d %H:%M:%S')

message = messaging.Message(
    notification=messaging.Notification(
        title='😱 Sadhana emlézetető',
        body=current_time + '\nMa még nem töltötted ki a sadhana infókat!',
    ),
    token=registration_token,
)

# Send the message
response = messaging.send(message)