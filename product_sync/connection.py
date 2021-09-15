import mysql.connector

procurement_db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="procurement_db"
)

ims_db_gh = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="ims_db_gh"
)

procurement_cursor = procurement_db.cursor()
ims_cursor = ims_db_gh.cursor()
