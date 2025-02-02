document.addEventListener("DOMContentLoaded", function() {
    const passwordInput = document.getElementById("password-input");
    const togglePassword = document.getElementById("toggle-password");
    const form = document.querySelector(".login-box__form");
    const checklist = {
        length: document.getElementById("length"),
        uppercase: document.getElementById("uppercase"),
        lowercase: document.getElementById("lowercase"),
        number: document.getElementById("number"),
        special: document.getElementById("special")
    };

    document.getElementById("password-checklist").style.display = "none";

    togglePassword.addEventListener("click", function(e) {
        e.stopPropagation();
        const currentType = passwordInput.getAttribute("type");
        if (currentType === "password") {
            passwordInput.setAttribute("type", "text");
            this.classList.remove("fa-eye");
            this.classList.add("fa-eye-slash");
        } else {
            passwordInput.setAttribute("type", "password");
            this.classList.remove("fa-eye-slash");
            this.classList.add("fa-eye");
        }
    });

    function isPasswordValid(value) {
        return value.length >= 8 &&
            /[A-Z]/.test(value) &&
            /[a-z]/.test(value) &&
            /[0-9]/.test(value) &&
            /[^A-Za-z0-9]/.test(value);
    }

    passwordInput.addEventListener("input", function() {
        const value = passwordInput.value;

        if (value.length > 0) {
            document.getElementById("password-checklist").style.display = "block";
        } else {
            document.getElementById("password-checklist").style.display = "none";
        }

        checklist.length.innerHTML = value.length >= 8 ?
            '<i class="fa-solid fa-check-circle" style="color: #23DC3D;"></i> At least 8 characters' :
            '<i class="fa-solid fa-times-circle" style="color: #f6fcdf;"></i> At least 8 characters';
        checklist.uppercase.innerHTML = /[A-Z]/.test(value) ?
            '<i class="fa-solid fa-check-circle" style="color: #23DC3D;"></i> At least one uppercase letter' :
            '<i class="fa-solid fa-times-circle" style="color: #f6fcdf;"></i> At least one uppercase letter';
        checklist.lowercase.innerHTML = /[a-z]/.test(value) ?
            '<i class="fa-solid fa-check-circle" style="color: #23DC3D;"></i> At least one lowercase letter' :
            '<i class="fa-solid fa-times-circle" style="color: #f6fcdf;"></i> At least one lowercase letter';
        checklist.number.innerHTML = /[0-9]/.test(value) ?
            '<i class="fa-solid fa-check-circle" style="color: #23DC3D;"></i> At least one number' :
            '<i class="fa-solid fa-times-circle" style="color: #f6fcdf;"></i> At least one number';
        checklist.special.innerHTML = /[^A-Za-z0-9]/.test(value) ?
            '<i class="fa-solid fa-check-circle" style="color: #23DC3D;"></i> At least one special character' :
            '<i class="fa-solid fa-times-circle" style="color: #f6fcdf;"></i> At least one special character';
    });


    form.addEventListener("submit", function(e) {
        if (!isPasswordValid(passwordInput.value)) {
            e.preventDefault();
            alert("Please enter a valid password meeting all the requirements.");
        }
    });
});
