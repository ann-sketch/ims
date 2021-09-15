from connection import procurement_cursor, ims_cursor, procurement_db, ims_db_gh
from utils import out
from time import sleep

while True:
    sleep(5)

    # get id and request_name of status 13 rows(completed procurement process)  FROM `request_list`    
    procurement_cursor.execute("SELECT id, request_name FROM request_list WHERE status = 13")

    for p in procurement_cursor:
        # request_name is used to check so that imprest is excluded
        # id will be used to get product_name, quantity, unit_price FROM `po_items`
        id, request_name = p
        # out(id, request_name)

        # procurement_cursor.rowcount returns the number of rows with status 13, if rowcount == 0, it'll skip to end
        if request_name != 'imprest' and procurement_cursor.rowcount:
            procurement_cursor.execute(f"SELECT product_name, quantity, unit_price FROM `po_items` WHERE id = '{id}'")
            # these product_name, quantity, unit_price are inserted/updated in `products`
            product_name, quantity, unit_price = procurement_cursor.fetchone()
            
            # check if product_name exist, if true ims_cursor.fetchall() will return true
            ims_cursor.execute(f"SELECT * FROM products WHERE name = '{product_name}'")
            # out(ims_cursor.fetchall())
            if ims_cursor.fetchall(): 
                out("first")
                ims_cursor.execute(f"UPDATE `products` SET name = '{product_name}', qty = {quantity}, price = {unit_price} WHERE name = '{product_name}'")
            else:
                out("second")
                ims_cursor.execute(f"INSERT INTO `products` (`name`, `price`, `qty`, `image`, `description`, `store_id`, `availability`) VALUES ('{product_name}', '{unit_price}', '{quantity}', '<p>You did not select a file to upload.</p>', '', '7', '1')")
            ims_db_gh.commit()

    out("done...")