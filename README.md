# WEBBSHOP

I denna projektet så finns det tre olika pages. I index.php sida kan man navigera till home page och cart page genom att skicka $_GET request via länken. I home page kan man se produkter samt kunna lägga till produkter i $_SESSION['cart'] lista så att användaren ska kunna se den i cart page. När man klickar på ordet cart i navbaren så navigerar användaren  till cart page för att beställa produkter och mäta in användarens information via ett formulär. 

När man klickar på checkout så skickas det INSERT_frågor till databas så att kunden information skickas till users och customers table produkt information skickas till Orders och Order_lines table. Om användaren är inloggad så skickas det produkt infomation till databas så användaren behöver inte mäta in information igen. Om man loggar in som 'customer' så kommer man att se beställningar och tidigare beställningar. Om man loggar in som 'admin' så kommer man att se alla beställningar och uppdatera satus på orders.
