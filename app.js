let openShopping = document.querySelector('.shopping');
let closeShopping = document.querySelector('.closeShopping');
let list = document.querySelector('.list');
let listCard = document.querySelector('.listCart');
let body = document.querySelector('body');
let total = document.querySelector('.total');
let quantity = document.querySelector('.quantity');

openShopping.addEventListener('click', ()=>{
    body.classList.add('active');
})
closeShopping.addEventListener('click', ()=>{
    body.classList.remove('active');
})

let products = [ 
      <?php
      $servername = "localhost";
      $username = "root";
      $password = "";
      $dbname = "ahproject";

      $con = new mysqli($servername, $username, $password, $dbname);

      if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
      }

      $sql = "SELECT * FROM cart";
      $result = $con->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
  echo "<div class='grid-item' data-product-id='" . $row['BuyerID'] . "'>";
  echo "<img src='data:image/jpeg;base64," . base64_encode($row['ProductImage']) . "' alt='" . $row['ProductName'] . "'>";
  echo "<h2>" . $row['ProductName'] . "</h2>";
  echo "<p>Rs." . $row['Price'] . "</p>";
  echo "<p>" . $row['ProductType'] . "</p>";
  echo <<<HTML
];
let listCarts  = [];
function initApp(){
    products.forEach((value, key) =>{
        let newDiv = document.createElement('div');
        newDiv.classList.add('item');
        newDiv.innerHTML = `
            <img src="image/${value.image}">
            <div class="title">${value.name}</div>
            <div class="price">${value.price.toLocaleString()}</div>
            <button onclick="addToCard(${key})">Add To Cart</button>`;
        list.appendChild(newDiv);
    })
}
initApp();
function addToCard(key){
    if(listCards[key] == null){
        listCards[key] = products[key];
        listCards[key].quantity = 1;
    }
    reloadCard();
}
function reloadCard() {
  listCard.innerHTML = '';
  let count = 0;
  let totalPrice = 0;

  for (const key in listCarts) {
    const product = listCarts[key];
    totalPrice += product.price;
    count += product.quantity;

    // Fetch product details from the server using AJAX
    fetch(`fetch_product.php?productId=${product.id}`)
      .then(response => response.json())
      .then(productDetails => {
        const newDiv = document.createElement('li');
        newDiv.innerHTML = `
          <div><img src="data:image/jpeg;base64,${productDetails.image}" /></div>
          <div>${productDetails.name}</div>
          <div>${productDetails.price.toLocaleString()}</div>
          <div>
            <button onclick="changeQuantity(${key}, ${product.quantity - 1})">-</button>
            <div class="count">${product.quantity}</div>
            <button onclick="changeQuantity(${key}, ${product.quantity + 1})">+</button>
          </div>`;
        listCard.appendChild(newDiv);
      })
      .catch(error => console.error(error));
  }

  total.innerText = totalPrice.toLocaleString();
  quantity.innerText = count;
}function changeQuantity(key, quantity){
    console.log(key, quantity);
    if(quantity == 0){
        delete listCards[key];
    }else{
        listCards[key].quantity = quantity;
        listCards[key].price = quantity * products[key].price;
    }
    reloadCard();
}