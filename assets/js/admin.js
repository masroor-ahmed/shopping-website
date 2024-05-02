const addProductForm = document.getElementById('add-product-form');

addProductForm.addEventListener('submit', (e) => {
    e.preventDefault();

    const formData = new FormData(addProductForm);

    fetch('add_product.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log(data);
        // Reset the form or show a success message
        addProductForm.reset();
    })
    .catch(error => console.error('Error adding product:', error));
});