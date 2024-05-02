// Fetch product data from JSON file
fetch('assets/data/products.json')
    .then(response => response.json())
    .then(data => {
        const productList = document.getElementById('product-list');

        // Store the original product data for filtering
        const originalProducts = data.products;

        // Function to display products based on the provided data
        function displayProducts(products) {
            productList.innerHTML = ''; // Clear previous products

            // Loop through product data and create cards
            products.forEach(product => {
                const card = document.createElement('div');
                card.classList.add('bg-white', 'rounded-lg', 'shadow-md', 'overflow-hidden');

                card.innerHTML = `
                    <img src="uploads/product-images/${product.image}" alt="${product.name}" class="h-48 w-full object-cover">
                    <div class="p-4">
                        <h2 class="text-xl font-bold mb-2">${product.name}</h2>
                        <p class="text-gray-700 mb-4">$${product.price}</p>
                        <a href="product.php?id=${product.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            More Details
                        </a>
                    </div>
                `;

                productList.appendChild(card);
            });
        }

        // Initial display of products
        displayProducts(originalProducts);

        // Search functionality
        const searchInput = document.getElementById('searchInput');

        searchInput.addEventListener('input', () => {
            const searchQuery = searchInput.value.trim().toLowerCase();

            // Filter products based on the search query
            const filteredProducts = originalProducts.filter(product => {
                return product.name.toLowerCase().includes(searchQuery);
            });

            // Display filtered products
            displayProducts(filteredProducts);
        });
    })
    .catch(error => console.error('Error fetching product data:', error));

// Buy Now Modal
const buyModal = document.getElementById('buy-modal');
const buyButton = document.getElementById('buy-now');
const buyForm = document.getElementById('buy-form');
const closeModalButton = document.getElementById('close-modal');

buyButton.addEventListener('click', () => {
    buyModal.classList.remove('hidden');
});

closeModalButton.addEventListener('click', () => {
    buyModal.classList.add('hidden');
});

buyForm.addEventListener('submit', (e) => {
    e.preventDefault();

    const formData = new FormData(buyForm);

    fetch('send_order.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            buyModal.classList.add('hidden');
        })
        .catch(error => console.error('Error sending order:', error));
});
