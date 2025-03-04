function toggleTeacherFields() {
        const role = document.getElementById('type').value;
        const teacherFields = document.getElementById('teacher-fields');
        if (role === 'teacher') {
            teacherFields.style.display = 'block';
        } else {
            teacherFields.style.display = 'none';
        }
    }

document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
    const firstName = document.getElementById("first_name");
    const lastName = document.getElementById("last_name");
    const email = document.getElementById("email");
    const type = document.getElementById("type");

    form.addEventListener("submit", (event) => {
        let errors = [];

        if (!firstName.value.trim()) {
            errors.push("First Name is required.");
        }

        if (!lastName.value.trim()) {
            errors.push("Last Name is required.");
        }

        if (!email.value.trim()) {
            errors.push("Email is required.");
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
            errors.push("Please enter a valid email address.");
        }

        if (!type.value) {
            errors.push("Please select a role.");
        }

        if (errors.length > 0) {
            event.preventDefault();
            alert(errors.join("\n"));
        }
    });
});
