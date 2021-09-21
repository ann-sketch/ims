from connection import ims_cursor, ims_db_gh
from utils import out
from time import sleep
import requests

# while True:
#     sleep(6)


def send_sms(api_key, phone, message, sender_id):
    # parameters to send SMS
    params = {"key": api_key, "to": phone, "msg": message, "sender_id": sender_id}

    url = 'http://goldsms.smsalertgh.com/smsapi?' 

    res = requests.post(url, params=params)

    print(res.json())

# Defining variables to be used inside function
api_key = '00c44cf39580579e337c'  # API Key generated from your mNotify account
phone = '0207133523'  # SMS recepient's phone number
sender_id = 'ADONKO LTD'  # Sender id for the message

# Calling function that was created to send sms

ims_cursor.execute(f"SELECT name, qty FROM products WHERE qty < 11")
for i in ims_cursor:
    message = f"{i[0]} is low in stock. \n\nOnly a quantity of '{i[1]}' left."  
    send_sms(api_key, phone, message, sender_id)
    # print(i[0])