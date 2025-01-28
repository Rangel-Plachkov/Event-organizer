function toggleCustomTypeField() {
    const typeSelect = document.getElementById('type');
    const customTypeContainer = document.getElementById('customTypeContainer');

    if (typeSelect.value === 'Other') {
        customTypeContainer.style.display = 'block';
    } else {
        customTypeContainer.style.display = 'none';
        document.getElementById('customType').value = '';
    }
}

// Handle the create event form submission
document.getElementById('create-event-form').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent the default form submission

    const formData = new FormData(this);

    fetch('/create-event-op', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        const feedback = document.getElementById('create-event-feedback');
        if (data.status === 'success') {
            feedback.textContent = 'Event created successfully!';
            feedback.style.color = 'green';
            // Optionally reload the page or dynamically add the new event to the list
            location.reload();
        } else {
            feedback.textContent = 'Failed to create event: ' + data.message;
            feedback.style.color = 'red';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const feedback = document.getElementById('create-event-feedback');
        feedback.textContent = 'An error occurred while creating the event.' + error;
        feedback.style.color = 'red';

        // Wait 5 seconds (5000 milliseconds) before reloading the page
        setTimeout(() => {
            location.reload();
        }, 5000);
    });
});