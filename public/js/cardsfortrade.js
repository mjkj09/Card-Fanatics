document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("trade-form");
    const listContainer = document.getElementById("trade-list");
    const messagesDiv = document.getElementById("messages");

    const collectionInput = document.getElementById("collectionName");
    const dataList = document.createElement("datalist");
    dataList.id = "collectionSuggestions";
    collectionInput.setAttribute("list", "collectionSuggestions");
    collectionInput.parentNode.appendChild(dataList);

    init();

    form.addEventListener("submit", (event) => {
        event.preventDefault();

        const cardCode = form["cardCode"].value.trim();
        const collectionName = form["collectionName"].value.trim();
        const playerName = form["playerName"].value.trim();
        const playerSurname = form["playerSurname"].value.trim();
        const parallel = form["parallel"].value.trim();
        const quantity = form["quantity"].value.trim();

        if (!cardCode || !collectionName || !playerName || !playerSurname || !quantity) {
            showMessage("Please fill out code, collection, player name, player surname, and quantity!", "error");
            return;
        }

        const formData = new FormData();
        formData.append("cardCode", cardCode);
        formData.append("collectionName", collectionName);
        formData.append("playerName", playerName);
        formData.append("playerSurname", playerSurname);
        formData.append("parallel", parallel);
        formData.append("quantity", quantity);

        fetch("addCardForTrade", {
            method: "POST",
            body: formData,
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.status === "success") {
                    showMessage(data.message, "success");
                    form.reset();
                    loadTradeCards();
                    updateCollectionSuggestions();
                } else {
                    showMessage(data.message || "Error adding card!", "error");
                }
            })
            .catch((err) => showMessage(err, "error"));
    });

    function init() {
        loadTradeCards();
        updateCollectionSuggestions();
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
        const {code, collection, parallel, player_name, player_surname, quantity} = card;
        const qtyNum = parseInt(quantity, 10);
        let parallelLabel = parallel ? (`(${parallel})`) : "";
        const playerLabel = `${player_name} ${player_surname}`;

        const li = document.createElement("li");
        li.innerHTML = `
            <span>${code} - ${collection} - ${playerLabel} ${parallelLabel} x${qtyNum}</span>
            <button class="qty-down">-</button>
            <button class="qty-up">+</button>
            <button class="remove-button">Remove</button>
        `;

        const downBtn = li.querySelector(".qty-down");
        const upBtn = li.querySelector(".qty-up");
        const removeBtn = li.querySelector(".remove-button");

        downBtn.addEventListener("click", () => {
            if (qtyNum === 1) {
                removeCard(code, collection, player_name, player_surname, parallel);
            } else {
                updateQuantity(code, collection, player_name, player_surname, parallel, qtyNum - 1);
            }
        });

        upBtn.addEventListener("click", () => {
            updateQuantity(code, collection, player_name, player_surname, parallel, qtyNum + 1);
        });

        removeBtn.addEventListener("click", () => {
            removeCard(code, collection, player_name, player_surname, parallel);
        });

        listContainer.appendChild(li);
    }

    function updateQuantity(code, collection, player_name, player_surname, parallel, newQuantity) {
        const formData = new FormData();
        formData.append("cardCode", code);
        formData.append("collectionName", collection);
        formData.append("playerName", player_name);
        formData.append("playerSurname", player_surname);
        formData.append("parallel", parallel);
        formData.append("newQuantity", newQuantity);

        fetch("updateTradeQuantity", {
            method: "POST",
            body: formData,
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

    function removeCard(code, collection, player_name, player_surname, parallel) {
        const formData = new FormData();
        formData.append("cardCode", code);
        formData.append("collectionName", collection);
        formData.append("playerName", player_name);
        formData.append("playerSurname", player_surname);
        formData.append("parallel", parallel);

        fetch("removeCardForTrade", {
            method: "POST",
            body: formData,
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.status === "success") {
                    showMessage(data.message, "success");
                    loadTradeCards();
                    updateCollectionSuggestions();
                } else {
                    showMessage(data.message || "Error removing card!", "error");
                }
            })
            .catch((err) => showMessage(err, "error"));
    }

    function updateCollectionSuggestions() {
        fetch("getUserCollections")
            .then((res) => res.json())
            .then((data) => {
                if (data.status === "success") {
                    const dataList = document.getElementById("collectionSuggestions");
                    dataList.innerHTML = "";
                    data.collections.forEach((collection) => {
                        const option = document.createElement("option");
                        option.value = collection;
                        dataList.appendChild(option);
                    });
                }
            })
            .catch((err) => console.error("Error updating suggestions:", err));
    }

    function showMessage(msg, type) {
        messagesDiv.innerHTML = `<p style="color: ${type === "error" ? "red" : "lightgreen"}">${msg}</p>`;
        setTimeout(() => {
            messagesDiv.innerHTML = "";
        }, 3000);
    }
});
