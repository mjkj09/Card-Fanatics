document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("wishlist-form");
    const listContainer = document.getElementById("wishlist-container");
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

        if (!cardCode || !collectionName || !playerName || !playerSurname) {
            showMessage("Please fill out card code, collection, player name, and player surname!", "error");
            return;
        }

        const formData = new FormData();
        formData.append("cardCode", cardCode);
        formData.append("collectionName", collectionName);
        formData.append("playerName", playerName);
        formData.append("playerSurname", playerSurname);
        formData.append("parallel", parallel);

        fetch("addCardToWishlist", {
            method: "POST",
            body: formData,
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.status === "success") {
                    showMessage(data.message, "success");
                    form.reset();
                    loadWishlist();
                    updateCollectionSuggestions();
                } else {
                    showMessage(data.message || "Error adding card!", "error");
                }
            })
            .catch((err) => showMessage(err, "error"));
    });

    function init() {
        loadWishlist();
        updateCollectionSuggestions();
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
        const {code, collection, parallel, player_name, player_surname} = card;
        let parallelLabel = parallel ? (`(${parallel})`) : "";
        const playerLabel = `${player_name} ${player_surname}`;

        const li = document.createElement("li");
        li.innerHTML = `
            <span>${code} - ${collection} - ${playerLabel} ${parallelLabel}</span>
            <button class="remove-button">Remove</button>
        `;

        const removeBtn = li.querySelector(".remove-button");
        removeBtn.addEventListener("click", () => {
            removeCard(code, collection, player_name, player_surname, parallel);
        });

        listContainer.appendChild(li);
    }

    function removeCard(code, collection, player_name, player_surname, parallel) {
        const formData = new FormData();
        formData.append("cardCode", code);
        formData.append("collectionName", collection);
        formData.append("playerName", player_name);
        formData.append("playerSurname", player_surname);
        formData.append("parallel", parallel);

        fetch("removeCardFromWishlist", {
            method: "POST",
            body: formData,
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.status === "success") {
                    showMessage(data.message, "success");
                    loadWishlist();
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
        messagesDiv.innerHTML = `<p style="color:${type === "error" ? "red" : "lightgreen"}">${msg}</p>`;
        setTimeout(() => {
            messagesDiv.innerHTML = "";
        }, 3000);
    }
});
