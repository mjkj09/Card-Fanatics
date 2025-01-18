document.addEventListener("DOMContentLoaded", () => {
    const searchForm     = document.getElementById("search-form");
    const searchInput    = document.getElementById("search-input");
    const searchIcon     = document.getElementById("search-icon");
    const resultsContainer = document.getElementById("results-container");

    searchForm.addEventListener("submit", (event) => {
        event.preventDefault();
        doSearch();
    });

    searchIcon.addEventListener("click", (event) => {
        event.preventDefault();
        doSearch();
    });

    doSearch();

    function doSearch() {
        const query = searchInput.value.trim();
        fetch(`searchTradeCardsAllFields?query=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    displayResults(data.cards);
                } else {
                    resultsContainer.innerHTML = "<p>Error from server</p>";
                }
            })
            .catch(err => {
                console.error("Search error:", err);
                resultsContainer.innerHTML = "<p>Error during search</p>";
            });
    }

    function displayResults(cards) {
        resultsContainer.innerHTML = "";
        if (!cards.length) {
            resultsContainer.innerHTML = "<p>No results found</p>";
            return;
        }
        cards.forEach(card => {
            const item = document.createElement("div");
            item.classList.add("result-item");
            item.innerHTML = `
                <div class="result-item__details">
                    <p>Code: ${card.code}</p>
                    <p>Collection: ${card.collection}</p>
                    <p>Parallel: ${card.parallel || '-'}</p>
                    <p>Quantity: ${card.quantity}</p>
                </div>
            `;
            resultsContainer.appendChild(item);
        });
    }
});
