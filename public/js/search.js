document.addEventListener("DOMContentLoaded", () => {
    const searchForm       = document.getElementById("search-form");
    const searchInput      = document.getElementById("search-input");
    const searchIcon       = document.getElementById("search-icon");
    const resultsContainer = document.getElementById("results-container");

    const showFiltersBtn   = document.getElementById("show-filters-btn");
    const filtersPanel     = document.getElementById("filters-panel");
    const filtersContainer = document.getElementById("filters-container");

    let allCards = [];
    let uniqueCollections = [];
    let uniqueParallels   = [];

    searchForm.addEventListener("submit", (event) => {
        event.preventDefault();
        doSearch();
    });

    searchIcon.addEventListener("click", (event) => {
        event.preventDefault();
        doSearch();
    });

    doSearch();

    showFiltersBtn.addEventListener("click", () => {
        if (filtersPanel.style.display === "none") {
            filtersPanel.style.display = "block";
        } else {
            filtersPanel.style.display = "none";
        }
    });

    function doSearch() {
        const query = searchInput.value.trim();
        fetch(`searchTradeCardsAllFields?query=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    allCards = data.cards;
                    displayResults(allCards);

                    buildFilters(allCards);
                } else {
                    resultsContainer.innerHTML = "<p>Error from server</p>";
                }
            })
            .catch(err => {
                console.error("Search error:", err);
                resultsContainer.innerHTML = "<p>Error during search</p>";
            });
    }

    function buildFilters(cards) {
        filtersContainer.innerHTML = "";

        if (!cards.length) {
            return;
        }

        uniqueCollections = [...new Set(cards.map(c => c.collection))];
        uniqueParallels   = [...new Set(cards.map(c => c.parallel))];

        const colH3 = document.createElement("h3");
        colH3.textContent = "Collections";
        filtersContainer.appendChild(colH3);

        uniqueCollections.forEach(col => {
            const label = document.createElement("label");
            const cb = document.createElement("input");
            cb.type = "checkbox";
            cb.value = col;
            cb.checked = true;
            label.appendChild(cb);
            label.appendChild(document.createTextNode(" " + col));
            filtersContainer.appendChild(label);
            filtersContainer.appendChild(document.createElement("br"));
        });

        const parH3 = document.createElement("h3");
        parH3.textContent = "Parallels";
        filtersContainer.appendChild(parH3);

        uniqueParallels.forEach(par => {
            const label = document.createElement("label");
            const cb = document.createElement("input");
            cb.type = "checkbox";
            cb.value = par;
            cb.checked = true;
            label.appendChild(cb);
            label.appendChild(document.createTextNode(" " + (par || "None")));
            filtersContainer.appendChild(label);
            filtersContainer.appendChild(document.createElement("br"));
        });

        const applyBtn = document.createElement("button");
        applyBtn.textContent = "Apply filters";
        filtersContainer.appendChild(applyBtn);

        applyBtn.addEventListener("click", () => {
            applyFilters();
        });
    }

    function applyFilters() {
        const checkedCollections = [];
        uniqueCollections.forEach(col => {
            const cb = filtersContainer.querySelector(`input[type="checkbox"][value="${col}"]`);
            if (cb && cb.checked) {
                checkedCollections.push(col);
            }
        });

        const checkedParallels = [];
        uniqueParallels.forEach(par => {
            const cb = filtersContainer.querySelector(`input[type="checkbox"][value="${par}"]`);
            if (cb && cb.checked) {
                checkedParallels.push(par);
            }
        });

        const filteredCards = allCards.filter(card => {
            const matchCol = checkedCollections.includes(card.collection);
            const matchPar = checkedParallels.includes(card.parallel);
            return matchCol && matchPar;
        });

        displayResults(filteredCards);
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
