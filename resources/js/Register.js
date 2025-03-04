function validateForm() {
    let isValid = true;
    const firstName = document.forms["registerForm"]["first_name"].value;
    const lastName = document.forms["registerForm"]["last_name"].value;
    const email = document.forms["registerForm"]["email"].value;
    const password = document.forms["registerForm"]["password"].value;
    const confirmPassword = document.forms["registerForm"]["confirm_password"].value;
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;

    document.getElementById("firstNameError").textContent = "";
    document.getElementById("lastNameError").textContent = "";
    document.getElementById("emailError").textContent = "";
    document.getElementById("passwordError").textContent = "";
    document.getElementById("confirmPasswordError").textContent = "";

    if (firstName.length < 1) {
        document.getElementById("firstNameError").textContent = "First name is required.";
        isValid = false;
    }

    if (lastName.length < 1) {
        document.getElementById("lastNameError").textContent = "Last name is required.";
        isValid = false;
    }

    if (!emailPattern.test(email)) {
        document.getElementById("emailError").textContent = "Please enter a valid email address.";
        isValid = false;
    }

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
    if (urlParams.get('registered') === 'true') {
        alert('Registration successful! You can now log in.');
        window.location.href = "{{ route('login') }}";
    }
}

window.onload = handleSuccessMessage;