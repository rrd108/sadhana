from datetime import datetime
import firebase_admin
from firebase_admin import messaging
from firebase_admin import credentials

cred = credentials.Certificate('../config/sadhana-firebase.json')
firebase_admin.initialize_app(cred)

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