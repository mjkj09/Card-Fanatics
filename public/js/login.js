document.addEventListener("DOMContentLoaded", function() {
    const loginPasswordInput = document.getElementById("login-password-input");
    const loginTogglePassword = document.getElementById("login-toggle-password");

    loginTogglePassword.addEventListener("click", function(e) {
        e.stopPropagation();
        const currentType = loginPasswordInput.getAttribute("type");
        if (currentType === "password") {
            loginPasswordInput.setAttribute("type", "text");
            this.classList.remove("fa-eye");
            this.classList.add("fa-eye-slash");
        } else {
            loginPasswordInput.setAttribute("type", "password");
            this.classList.remove("fa-eye-slash");
            this.classList.add("fa-eye");
        }
    });
});
