const swipeInput = document.getElementById("card-swipe-input");
const cardDisplay = document.getElementById("card-display");
const cardIdHiddenInput = document.getElementById("card_id");

if (swipeInput) {
    swipeInput.addEventListener("keyup", function (e) {
        if (e.key === "Enter") {
            e.preventDefault();

            processSwipeData(this.value.trim());
        }
    });

    swipeInput.addEventListener("input", function () {
        cardIdHiddenInput.value = this.value.trim();

        swipeTimeout = setTimeout(() => {
            processSwipeData(this.value.trim());
        }, 1000);
    });

    swipeInput.addEventListener("focus", function () {
        this.type = "text";
        this.value = "";
        cardIdHiddenInput.value = "";
    });

    swipeInput.addEventListener("blur", function () {
        this.type = "password";
    });
}

function processSwipeData(rawData) {
    if (!rawData) return;

    cardDisplay.textContent = `Card Assigned: ${rawData}`;
    cardIdHiddenInput.value = rawData;

    console.log("Card Processed:", rawData);
}
