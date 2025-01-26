document.addEventListener("DOMContentLoaded", () => {
    const joinButton = document.getElementById('join-event-btn');
    const commentForm = document.getElementById('comment-form');
    const commentsContainer = document.getElementById('comments');

    // Join Event
    if (joinButton) {
        joinButton.addEventListener('click', async () => {
            const response = await fetch('/join-event', {
                method: 'POST',
                body: JSON.stringify({ eventId: EVENT_ID, userId: USER_ID }),
                headers: { 'Content-Type': 'application/json' }
            });

            if (response.ok) {
                location.reload();
            } else {
                alert('Failed to join the event.');
            }
        });
    }

    // Submit comment
    if (commentForm) {
        commentForm.addEventListener('submit', function (event) {
            event.preventDefault(); // Stop the standart form behaviour

            const comment = document.getElementById('new-comment').value;
            const eventId = document.getElementById('eventId').value;
            const username = document.getElementById('username-comment-container').dataset.value;

            const commentsContainer = document.getElementById('comments');

            if (!commentsContainer) {
                console.error('Comments container not found!');
                return;
            }

            // AJAX querry for adding comments
            fetch('/create-comment-op', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    eventId: eventId,
                    comment: comment,
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('comment-feedback').textContent = 'Comment added successfully.';
                    document.getElementById('comment-feedback').style.color = 'green';
                    document.getElementById('new-comment').value = ''; // Clear the text field for the next comment

                    // Add the new commnt to the html
                    const commentElement = document.createElement('div');
                    commentElement.classList.add('comment');
                    commentElement.innerHTML = `<p><strong>${username}:</strong></p><p>${data.comment}</p>`;
                    commentsContainer.appendChild(commentElement);
                } else {
                    document.getElementById('comment-feedback').textContent = 'Failed to add comment.';
                    document.getElementById('comment-feedback').style.color = 'red';
                    setTimeout(() => {
                                feedbackElement.style.display = 'none';
                            }, 3000); // 3000 miliseconds = 3 sec
                }
                    
                //scroll to the bottom to show the new message
                requestAnimationFrame(() => {
                    commentsContainer.scrollTop = commentsContainer.scrollHeight;
                });
                
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('comment-feedback').textContent = 'An error occurred.';
                document.getElementById('comment-feedback').style.color = 'red';
                setTimeout(() => {
                            feedbackElement.style.display = 'none';
                        }, 3000); // 3000 milliseconds = 3 sec
            });
        });

    }

    //Adding organization
    const addOrganizationBtn = document.getElementById("add-organization-btn");
    const organizationFormContainer = document.getElementById("organization-form-container");
    const isAnonymousCheckbox = document.getElementById("is_anonymous");
    const anonymousUsersContainer = document.getElementById("anonymous-users-container");

    // Hide/reveal of the form
    addOrganizationBtn.addEventListener("click", () => {
        if (organizationFormContainer.style.display === "none") {
            organizationFormContainer.style.display = "block";
            addOrganizationBtn.style.display = "none";
        } else {
            organizationFormContainer.style.display = "none";
            addOrganizationBtn.style.display = "block";
        }
    });

    // Hide/reveal of the filed for excluded users
    isAnonymousCheckbox.addEventListener("change", () => {
        if (isAnonymousCheckbox.checked) {
            anonymousUsersContainer.style.display = "block";
        } else {
            anonymousUsersContainer.style.display = "none";
        }
    });
        
    // Add organization form 
    const addOrganizationForm = document.getElementById("add-organization-form");
    addOrganizationForm.addEventListener("submit", (e) => {
        e.preventDefault(); // Stop the standard form behaviour and page reloading

        // Manually extract data from the form
        const eventId = document.getElementById("eventId").value; 
        const user_id = document.getElementById("user-id").value; 
        const organizerPaymentDetails = document.getElementById("organizer_payment_details").value || null;
        const placeAddress = document.getElementById("place_address").value;
        const isAnonymous = document.getElementById("is_anonymous").checked;
        const excludedUserName = document.getElementById("excluded_user")?.value || null;
        
        const data = {
            eventId: eventId,
            organizer_payment_details: organizerPaymentDetails,
            place_address: placeAddress,
            is_anonymous: isAnonymous,
            excluded_user_name: excludedUserName,
            user_id: user_id
        };
        
        console.log("Hello world");


        // get the querry through fetch
        fetch("/add-organization-op", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        })
            .then((response) => response.json())
            .then((data) => {
                const feedback = document.getElementById("organization-feedback");
                if (data.status === "success") {
                    feedback.textContent = "Organization added successfully!";
                    feedback.style.color = "green";

                    //Reaload the page using the reload form
                    const reloadForm = document.getElementById("reload-form");
                    reloadForm.querySelector('input[name="eventId"]').value = eventId;

                //  send the post querry to refresh the page
                reloadForm.submit();
                } else {
                    feedback.textContent = `Failed to add organization: ${data.message}`;
                    feedback.style.color = "red";
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                const feedback = document.getElementById("organization-feedback");
                feedback.textContent = "An error occurred while adding the organization.";
                feedback.style.color = "red";
            });
    });
});

