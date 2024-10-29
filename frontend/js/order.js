document.addEventListener("DOMContentLoaded", function () {
    const itemCounts = document.querySelectorAll(".itemCount");
    const addButtons = document.querySelectorAll(".add");
    const subtractButtons = document.querySelectorAll(".subtract");

    addButtons.forEach((addButton, index) => {
        addButton.addEventListener("click", () => {
            let countElement = itemCounts[index];
            let currentCount = parseInt(countElement.textContent, 10);
            countElement.textContent = currentCount + 1;
        });
    });

    subtractButtons.forEach((subtractButton, index) => {
        subtractButton.addEventListener("click", () => {
            let countElement = itemCounts[index];
            let currentCount = parseInt(countElement.textContent, 10);
            if (currentCount > 0) {
                countElement.textContent = currentCount - 1;
            }
        });
    });
});
