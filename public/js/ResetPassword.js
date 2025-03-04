function validateResetForm() {
    let isValid = true;
    const password = document.forms["resetForm"]["password"].value;
    const confirmPassword = document.forms["resetForm"]["password_confirmation"].value;
    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;

    document.getElementById("passwordError").textContent = "";
    document.getElementById("confirmPasswordError").textContent = "";

    if (!passwordPattern.test(password)) {
        document.getElementById("passwordError").textContent = "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one numeric digit, and one special character.";
        isValid = false;
    }

    if (password !== confirmPassword) {
        document.getElementById("confirmPasswordError").textContent = "Passwords do not match.";
        isValid = false;
    }

    return isValid;
}

function handleSuccessMessage() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('reset') === 'true') {
        document.querySelector('.success-message').textContent = 'Password reset successful! You can now log in.';
        document.querySelector('.success-message').style.display = 'block';
    }
}

window.onload = handleSuccessMessage;