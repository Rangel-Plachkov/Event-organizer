document.addEventListener("DOMContentLoaded", () => {
    const addGiftForm = document.getElementById("add-gift-form");
    const giftList = document.getElementById("gift-list");
    const feedback = document.getElementById("add-gift-feedback");

    // Add gift
    addGiftForm.addEventListener("submit", (e) => {
        e.preventDefault();
        const formData = new FormData(addGiftForm);

        fetch("add-gift", {
            method: "POST",
            body: formData,
        })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((data) => {
            if (data.status === "success") {
                feedback.textContent = data.message;
                feedback.style.color = "green";
                console.log(data); 

                const { id, gift_name, gift_price } = data.gift;

                // Dynamically add the new gift to the gift list
                const newGift = document.createElement("li");
                newGift.setAttribute("data-gift-id", id);
                newGift.innerHTML = `
                    <strong>${gift_name}:</strong> $${gift_price}
                    <button class="vote-button">Vote</button>
                `;

                giftList.appendChild(newGift);

                // Clear the form fields
                addGiftForm.reset();
            } else {
                feedback.textContent = `Failed to add gift: ${data.message}`;
                feedback.style.color = "red";
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            feedback.textContent = `An error occurred: ${error.message}`;
            feedback.style.color = "red";
        });
    });

        // Vote for a gift
    giftList.addEventListener("click", (e) => {
        if (e.target.classList.contains("vote-button")) {
            const giftId = e.target.closest("li").dataset.giftId;
            console.log("Send gift id: " + giftId);

            fetch("vote-gift", {
                method: "POST",
                body: JSON.stringify({ giftId }),
                headers: { "Content-Type": "application/json" },
            })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then((data) => {
                if (data.status === "success") {
                    // Refresh teh buttons
                    const allVoteButtons = document.querySelectorAll(".vote-button");
                    allVoteButtons.forEach(button => {
                        button.textContent = "Vote";
                        button.disabled = false;
                    });

                    // Deactivate the current button
                    e.target.textContent = "Voted";
                    e.target.disabled = true;
                } else {
                    alert(`Error: ${data.message}`);
                }
            })
            .catch((error) => {
                console.error("Error:", error);
            });
        }
    });

});

//Poll
document.addEventListener("DOMContentLoaded", () => {
    const createPollBtn = document.getElementById("create-poll-btn");
    const giftPollSection = document.querySelector(".gift-poll");
    const endPollButton = document.getElementById("end-poll-btn");

    if (createPollBtn) {
        createPollBtn.addEventListener("click", () => {
            const duration = 10;
            const eventId = document.getElementById('eventId').value;

            fetch("create-poll", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ eventId: eventId, duration: duration }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    createPollBtn.style.display = "none";
                    giftPollSection.style.display = "block";
                    endPollButton.style.display = "block";
                } else {
                    alert(`Error creating poll: ${data.message}`);
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
        });
    }
});

//End poll
document.addEventListener("DOMContentLoaded", () => {
    const endPollBtn = document.getElementById("end-poll-btn");
    const giftPollSection = document.querySelector(".gift-poll");
    const winnerSection = document.querySelector(".winner-section");
    const winningGiftMessage = document.getElementById("winning-gift-message");

    if (endPollBtn) {
        endPollBtn.addEventListener("click", () => {
            if (!confirm("Are you sure you want to end the poll?")) {
                return;
            }

            const eventId = document.getElementById('eventId').value;

            fetch("end-poll", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ eventId: eventId }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    // Hide sections
                    giftPollSection.style.display = "none";
                    endPollBtn.style.display = "none";
                    console.log(data);
                    // Show winner
                    winnerSection.style.display = "block";
                    const { gift_name, gift_price, vote_count } = data.winner;
                    winningGiftMessage.textContent = `
                        Winning gift: ${gift_name}, Price: $${gift_price}, Votes: ${vote_count}
                    `;
                } else {
                    alert(`Error ending poll: ${data.message}`);
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
        });
    }
});


