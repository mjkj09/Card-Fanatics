document.addEventListener("DOMContentLoaded", () => {
    const banButtons = document.querySelectorAll(".ban-btn");
    const banPopup = document.getElementById("ban-popup");
    const banUserIdInput = document.getElementById("banUserId");
    const banReasonInput = document.getElementById("banReason");
    const banConfirmBtn = document.getElementById("banConfirmBtn");
    const banCancelBtn = document.getElementById("banCancelBtn");

    banButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            const userId = btn.dataset.userId;
            banUserIdInput.value = userId;
            banReasonInput.value = "";
            banPopup.style.display = "block";
        });
    });

    banConfirmBtn.addEventListener("click", () => {
        const userId = banUserIdInput.value;
        const reason = banReasonInput.value.trim();

        // Wyślij request do banUser (POST)
        const formData = new FormData();
        formData.append("banUserId", userId);
        formData.append("banReason", reason);

        fetch("banUser", {
            method: "POST",
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                console.log("Ban user response:", data);
                if (data.status === "success") {
                    alert("User banned successfully!");
                    // prosty sposób: odśwież
                    window.location.reload();
                } else {
                    alert("Ban failed: " + data.message);
                }
            })
            .catch(err => {
                console.error("Ban error:", err);
            })
            .finally(() => {
                banPopup.style.display = "none";
            });
    });

    banCancelBtn.addEventListener("click", () => {
        banPopup.style.display = "none";
    });
});
