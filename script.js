
const products = [];

const productForm = document.getElementById("productForm");
const productName = document.getElementById("productName");
const productPrice = document.getElementById("productPrice");
const productList = document.getElementById("productList");
const totalPriceElement = document.getElementById("totalPrice");

productForm.addEventListener("submit", function (event) {
    event.preventDefault();

    const name = productName.value.trim();
    const price = Number(productPrice.value);

    if (!name || !Number.isFinite(price) || price <= 0) {
        alert("Please enter a valid product name and price.");
        return;
    }

    // Store product details as an object in an array.
    const product = {
        name: name,
        price: price
    };

    products.push(product);

    displayProducts();

    productForm.reset();
    productName.focus();
});

function displayProducts() {
    productList.replaceChildren();

    let total = 0;

    products.forEach(function (product) {
        const row = document.createElement("tr");
        const nameCell = document.createElement("td");
        const priceCell = document.createElement("td");

        nameCell.textContent = product.name;
        priceCell.textContent = `₹${product.price.toFixed(2)}`;

        row.append(nameCell, priceCell);
        productList.appendChild(row);

        total += product.price;
    });

    totalPriceElement.textContent = `₹${total.toFixed(2)}`;
}

