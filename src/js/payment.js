function showCardDetails() {
    document.getElementById('card-details').style.display = 'block';
}

function hideCardDetails() {
    document.getElementById('card-details').style.display = 'none';
}


document.getElementById("creditCardBtn").addEventListener("click", function() {
    document.getElementById("creditCardDetails").style.display = "block";
    document.getElementById("cashMessage").style.display = "none";
});

document.getElementById("cashBtn").addEventListener("click", function() {
    document.getElementById("creditCardDetails").style.display = "none";
    document.getElementById("cashMessage").style.display = "block";
});
