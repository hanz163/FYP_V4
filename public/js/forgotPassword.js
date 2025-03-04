function validateForm() {
    const emailInput = document.querySelector('input[name="email"]');
    const error = document.querySelector('.error');

    if (!emailInput.value) {
        error.textContent = 'Email address is required.';
        return false;
    }

    if (!emailInput.checkValidity()) {
        error.textContent = 'Please enter a valid email address.';
        return false;
    }

    error.textContent = '';
    return true;
}