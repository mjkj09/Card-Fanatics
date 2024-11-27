document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("add-wishlist-form");
    const listContainer = document.getElementById("wishlist-container");

    form.addEventListener("submit", (event) => {
        event.preventDefault();

        const cardCode = form["card-code"].value;
        const collection = form["collection"].value;

        if (cardCode && collection) {
            const listItem = document.createElement("li");
            listItem.innerHTML = `
                <span>${cardCode} - ${collection}</span>
                <button class="remove-button">Remove</button>
            `;
            listContainer.appendChild(listItem);

            listItem.querySelector(".remove-button").addEventListener("click", () => {
                listContainer.removeChild(listItem);
            });

            form.reset();
        }
    });
});
