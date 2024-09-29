
document.getElementById('fetchCategories').addEventListener('click', async () => {
    try {
        const response = await fetch('http://localhost:8000/backend/api.php', {
            method: 'GET',
            headers: {
                'API_KEY': 'My-api-key' // Your API key
            }
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const categories = await response.json();
        displayCategories(categories);
    } catch (error) {
        console.error('An error occurred while fetching data:', error);
    }
});

function displayCategories(categories) {
    const tableBody = document.getElementById('categoriesTable').getElementsByTagName('tbody')[0];
    tableBody.innerHTML = ''; // Clear existing entries

    if (Array.isArray(categories) && categories.length > 0) {
        categories.forEach(category => {
            const row = tableBody.insertRow();
            const cell = row.insertCell(0);
            cell.textContent = category; // Populate the category
        });
    } else {
        const row = tableBody.insertRow();
        const cell = row.insertCell(0);
        cell.textContent = 'No categories found';
    }
}
