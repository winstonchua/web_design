function validateNameInput(e){
    var name = e.target.value;
    var errorElement = document.getElementById('nameError');
    
    // Regular expression for validation
    var regex = /^[A-Za-z\s]{3,}$/;

    if (!regex.test(name)){
        errorElement.textContent = "Invalid Name. It should contain at least 3 alphabet characters and spaces.";
        return;
    }
    errorElement.textContent = "";
}
function validateEmailInput(e){
    var email = e.target.value;
    var errorElement = document.getElementById('emailError');

    var atIndex = email.indexOf("@");
    if (atIndex === -1) {
        errorElement.textContent = "Invalid email: missing '@'.";
        return;
    } 

    var username = email.substring(0, atIndex);
    var domain = email.substring(atIndex + 1, email.length);

    // Check username
    if (!/^[a-zA-Z0-9.-]+$/.test(username)) {
        errorElement.textContent = "Invalid username in email.";
        return;
    }
/*
    // Check domain
    var domainParts = domain.split(".");
    if (domainParts.length < 2 || domainParts.length > 4) {
        errorElement.textContent = "Invalid domain in email.";
        return;
    }

    for (var i = 0; i < domainParts.length; i++) {
        if (!/^[a-zA-Z0-9]+$/.test(domainParts[i])) {
            errorElement.textContent = "Invalid domain extension in email.";
            return;
        }
        if (i === domainParts.length - 1 && domainParts[i].length !== 3) {
            errorElement.textContent = "Last domain extension should have exactly 3 alphabets.";
            return;
        }
    }
*/
    // If all checks pass
    errorElement.textContent = "";
}


function validateExperienceInput(e){
    var experience = e.target.value; // Get the input value
    var errorElement = document.getElementById('experienceError');

    // Check if the field is empty
    if (experience.trim() === "") {
        errorElement.textContent = "This field cannot be left empty.";
        return;
    }

    // Check if the input exceeds 300 characters
    if (experience.length > 300) {
        errorElement.textContent = "The input must not exceed 300 characters.";
        return;
    }

    // If no errors, clear the error message
    errorElement.textContent = "";
}

function init(){
    try {
        var nameField  = document.getElementById('jobName');
        nameField.oninput = validateNameInput;

        var emailField = document.getElementById('jobEmail');
        emailField.oninput = validateEmailInput;

        var dateField = document.getElementById('jobDate');
        dateField.oninput = validateDateInput;

        var experienceField = document.getElementById('jobExperience');
        experienceField.oninput = validateExperienceInput;

        var supportForm = document.getElementById('supportForm');
        supportForm.addEventListener('submit', function(event) {
            // Get all error elements
            var errorElements = [
                document.getElementById('nameError'),
                document.getElementById('emailError'),
                document.getElementById('dateError'),
                document.getElementById('experienceError')
            ];

            // Initialize an array to hold error messages
            var errorMessages = [];

            // Check if any error element contains a message
            for (var i = 0; i < errorElements.length; i++) {
                if (errorElements[i].textContent !== '') {
                    // Add the error message to the array
                    errorMessages.push(errorElements[i].textContent);
                }
            }

            // If there are any error messages, prevent form submission and alert the messages
            if (errorMessages.length > 0) {
                event.preventDefault();
                alert('Please correct the following errors:\n\n' + errorMessages.join('\n'));
            }
        });


    } catch (error) {
        console.error(error);
    }
}


window.onload = init;
