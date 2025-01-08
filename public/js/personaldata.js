document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("personal-data-form");
    const messagesDiv = document.getElementById("messages");

    // 1. Po załadowaniu – pobierz dane usera
    fetch("getUserData")
        .then((res) => res.json())
        .then((data) => {
            if (data.status === "success") {
                const user = data.data;
                form["name"].value      = user.name;
                form["surname"].value   = user.surname;
                form["email"].value     = user.email; // read-only
                form["phone"].value     = user.phone || "";
                form["instagram"].value = user.instagram || "";
            } else {
                showMessage("Cannot load user data", "error");
            }
        })
        .catch((err) => {
            console.error(err);
            showMessage("Error loading user data", "error");
        });

    // 2. Zapis danych
    form.addEventListener("submit", (event) => {
        event.preventDefault();

        const formData = new FormData(form);
        // email jest read-only, ale mamy go w polu, więc w sumie i tak się go nie zaktualizuje.

        fetch("updatePersonalData", {
            method: "POST",
            body: formData
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.status === "success") {
                    showMessage("Personal data updated!", "success");
                } else {
                    showMessage(data.message || "Error updating data", "error");
                }
            })
            .catch((err) => {
                console.error(err);
                showMessage("Error updating data", "error");
            });
    });

    function showMessage(msg, type) {
        messagesDiv.innerHTML = `<p style="color:${type === "error" ? "red" : "lightgreen"}">${msg}</p>`;
        setTimeout(() => {
            messagesDiv.innerHTML = "";
        }, 3000);
    }
});
