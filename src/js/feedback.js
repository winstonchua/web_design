document.getElementById('feedbackForm').onsubmit = function(event) {
    
    var foodItem = document.getElementById('foodItem').value;
    var rating = document.getElementById('rating').value;
  
    if(rating >= 1 && rating <= 5){
      alert('Thank you for rating ' + foodItem + ' with a ' + rating + ' star(s)!');
      // Here you would typically send the data to the server
    } else {
      alert('Please enter a valid rating between 1 and 5.');
    }
}

// Get the feedback modal
var modal = document.getElementById('feedbackModal');

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("feedback-close")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
