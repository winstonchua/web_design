function toggleModal(modalId) {
    // Close any open modals first
    const modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
        modal.style.display = "none";
    });

    // Now, open the requested modal
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = "block";
    }
}

// Function to close modal if clicked outside of modal content
function closeModalOnOutsideClick(event) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
}

// Close the modals when clicking outside of them
window.addEventListener('click', closeModalOnOutsideClick);
function closeAndRedirect() {
    toggleModal('successModal');
    window.location.href = "../php/clear_response.php";
}



//SIGN UP MODAL CONTENTS CHECKER
document.addEventListener("DOMContentLoaded", function() {
    const nameInput = document.getElementById("signup-name");
    const emailInput = document.getElementById("signup-email");
    const passwordInput = document.getElementById("signup-password");
    const cardNumberInput = document.getElementById("card-number");
    const expiryDateInput = document.getElementById("expiry-date");
    const cvvInput = document.getElementById("cvv");
    const signUpForm = document.getElementById("signup-form");

    // Function to check if a field is empty
    function checkEmpty(input) {
        if (input.value.trim() === "") {
            input.setCustomValidity(input.getAttribute('name') + " should not be empty.");
        } else {
            input.setCustomValidity("");
        }
    }

    nameInput.addEventListener("input", function() {
        checkEmpty(this);
        if (!/^\w+$/.test(this.value)) {
            this.setCustomValidity("Name should only contain word characters.");
        }
    });
/*
    emailInput.addEventListener("input", function() {
        checkEmpty(this);
        if (!/^.+@.+\..+$/.test(this.value)) {
            this.setCustomValidity("Email should contain an '@' and a domain.");
        }
    });
*/
    passwordInput.addEventListener("input", function() {
        checkEmpty(this);
        if (this.value.length <= 6) {
            this.setCustomValidity("Password should contain more than 6 characters.");
        }
    });

    cardNumberInput.addEventListener("input", function() {
        checkEmpty(this);
        if (!/^\d{16}$/.test(this.value)) {
            this.setCustomValidity("Card number should be 16 digits.");
        }
    });

    expiryDateInput.addEventListener("input", function() {
        checkEmpty(this);
        if (this.value.length === 2 && !this.value.includes('/')) {
            this.value += '/';
        }
        
        const [month, year] = this.value.split('/');
        const currentYear = new Date().getFullYear() % 100; // Get last two digits of year

        if (!/^([0-9]{2})\/([0-9]{2})$/.test(this.value)) {
            this.setCustomValidity("Expiry date should be in MM/YY format.");
        } else if (parseInt(month) < 1 || parseInt(month) > 12) {
            this.setCustomValidity("Month should be between 01 and 12.");
        } else if (parseInt(year) < currentYear || parseInt(year) > currentYear + 10) {
            this.setCustomValidity("Year should be within the next 10 years.");
        }
    });

    cvvInput.addEventListener("input", function() {
        checkEmpty(this);
        if (!/^\d{3}$/.test(this.value)) {
            this.setCustomValidity("CVV should be 3 digits.");
        }
    });
});
