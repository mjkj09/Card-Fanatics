document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("wishlist-form");
    const listContainer = document.getElementById("wishlist-container");
    const messagesDiv = document.getElementById("messages");

    init();

    form.addEventListener("submit", (event) => {
        event.preventDefault();

        const cardCode       = form["cardCode"].value.trim();
        const collectionName = form["collectionName"].value.trim();
        const parallel       = form["parallel"].value.trim();

        if (!cardCode || !collectionName) {
            showMessage("Please fill out card code and collection!", "error");
            return;
        }

        const formData = new FormData();
        formData.append("cardCode", cardCode);
        formData.append("collectionName", collectionName);
        formData.append("parallel", parallel);

        fetch("addCardToWishlist", {
            method: "POST",
            body: formData
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.status === "success") {
                    showMessage(data.message, "success");
                    form.reset();
                    loadWishlist();
                } else {
                    // błąd (np. 'This card is already in your wishlist!')
                    showMessage(data.message || "Error adding card!", "error");
                }
            })
            .catch((err) => showMessage(err, "error"));
    });

    function init() {
        loadWishlist();
    }

    function loadWishlist() {
        fetch("getWishlistCards")
            .then((res) => res.json())
            .then((data) => {
                if (data.status === "success") {
                    listContainer.innerHTML = "";
                    data.cards.forEach((card) => {
                        addCardToList(card);
                    });
                } else {
                    showMessage("Error loading wishlist", "error");
                }
            })
            .catch((err) => showMessage(err, "error"));
    }

    function addCardToList(card) {
        const { code, collection, parallel } = card;
        let parallelLabel = parallel ? `(${parallel}) ` : "";

        const li = document.createElement("li");
        li.innerHTML = `
      <span>${code} - ${collection} ${parallelLabel}</span>
      <button class="remove-button">Remove</button>
    `;

        const removeBtn = li.querySelector(".remove-button");
        removeBtn.addEventListener("click", () => {
            removeCard(code, collection, parallel);
        });

        listContainer.appendChild(li);
    }

    function removeCard(code, collection, parallel) {
        const formData = new FormData();
        formData.append("cardCode", code);
        formData.append("collectionName", collection);
        formData.append("parallel", parallel);

        fetch("removeCardFromWishlist", {
            method: "POST",
            body: formData
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.status === "success") {
                    showMessage(data.message, "success");
                    loadWishlist();
                } else {
                    showMessage(data.message || "Error removing card!", "error");
                }
            })
            .catch((err) => showMessage(err, "error"));
    }

    function showMessage(msg, type) {
        messagesDiv.innerHTML = `<p style="color:${type === "error" ? "red" : "lightgreen"}">${msg}</p>`;
        setTimeout(() => {
            messagesDiv.innerHTML = "";
        }, 3000);
    }
});
