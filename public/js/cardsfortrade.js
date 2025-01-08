document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("trade-form");
    const listContainer = document.getElementById("trade-list");
    const messagesDiv = document.getElementById("messages");

    init();

    form.addEventListener("submit", (event) => {
        event.preventDefault();

        const cardCode = form["cardCode"].value.trim();
        const collectionName = form["collectionName"].value.trim();
        const parallel = form["parallel"].value.trim(); // optional
        const quantity = form["quantity"].value.trim();

        if (!cardCode || !collectionName || !quantity) {
            showMessage("Please fill out code, collection, and quantity!", "error");
            return;
        }

        const formData = new FormData();
        formData.append("cardCode", cardCode);
        formData.append("collectionName", collectionName);
        formData.append("parallel", parallel);
        formData.append("quantity", quantity);

        fetch("addCardForTrade", {
            method: "POST",
            body: formData
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.status === "success") {
                    showMessage(data.message, "success");
                    form.reset();
                    loadTradeCards();
                } else {
                    showMessage(data.message || "Error adding card!", "error");
                }
            })
            .catch((err) => showMessage(err, "error"));
    });

    function init() {
        loadTradeCards();
    }

    function loadTradeCards() {
        fetch("getTradeCards")
            .then((res) => res.json())
            .then((data) => {
                if (data.status === "success") {
                    listContainer.innerHTML = "";
                    data.cards.forEach((card) => {
                        addCardToList(card);
                    });
                } else {
                    showMessage("Error loading trade cards", "error");
                }
            })
            .catch((err) => showMessage(err, "error"));
    }

    function addCardToList(card) {
        const {code, collection, parallel, quantity} = card;
        const qtyNum = parseInt(quantity, 10);

        let parallelLabel = parallel ? `(${parallel}) ` : "";

        const li = document.createElement("li");
        li.innerHTML = `
    <span>${code} - ${collection} ${parallelLabel} x${qtyNum}</span>
    <button class="qty-down">-</button>
    <button class="qty-up">+</button>
    <button class="remove-button">Remove</button>
  `;

        const downBtn = li.querySelector(".qty-down");
        const upBtn = li.querySelector(".qty-up");
        const removeBtn = li.querySelector(".remove-button");

        downBtn.addEventListener("click", () => {
            if (qtyNum === 1) {
                removeCard(code, collection, parallel);
            } else {
                updateQuantity(code, collection, parallel, qtyNum - 1);
            }
        });

        upBtn.addEventListener("click", () => {
            updateQuantity(code, collection, parallel, qtyNum + 1);
        });

        removeBtn.addEventListener("click", () => {
            removeCard(code, collection, parallel);
        });

        listContainer.appendChild(li);
    }

    function updateQuantity(code, collection, parallel, newQuantity) {
        const formData = new FormData();
        formData.append("cardCode", code);
        formData.append("collectionName", collection);
        formData.append("parallel", parallel);
        formData.append("newQuantity", newQuantity);

        fetch("updateTradeQuantity", {
            method: "POST",
            body: formData
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.status === "success") {
                    showMessage(data.message, "success");
                    loadTradeCards();
                } else {
                    showMessage(data.message || "Error updating quantity!", "error");
                }
            })
            .catch((err) => showMessage(err, "error"));
    }

    function removeCard(code, collection, parallel) {
        const formData = new FormData();
        formData.append("cardCode", code);
        formData.append("collectionName", collection);
        formData.append("parallel", parallel);

        fetch("removeCardForTrade", {
            method: "POST",
            body: formData
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.status === "success") {
                    showMessage(data.message, "success");
                    loadTradeCards();
                } else {
                    showMessage(data.message || "Error removing card!", "error");
                }
            })
            .catch((err) => showMessage(err, "error"));
    }

    function showMessage(msg, type) {
        messagesDiv.innerHTML = `<p style="color: ${type === "error" ? "red" : "lightgreen"}">${msg}</p>`;
        setTimeout(() => {
            messagesDiv.innerHTML = "";
        }, 1000);
    }
});
