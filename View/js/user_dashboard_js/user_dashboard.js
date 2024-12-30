// Example events for demonstration
const events = [
    { title: "Рожден ден на Иван", date: "2025-01-02", description: "Празнуваме рожден ден." },
    { title: "Среща с приятели", date: "2025-01-05", description: "Среща за планиране." }
];

// Render events in the list
function renderEvents() {
    const eventContainer = document.getElementById("event-container");
    eventContainer.innerHTML = ""; // Clear existing content

    events.forEach(event => {
        const eventElement = document.createElement("div");
        eventElement.classList.add("event");
        eventElement.innerHTML = `
            <h3>${event.title}</h3>
            <p><strong>Дата:</strong> ${event.date}</p>
            <p>${event.description}</p>
        `;
        eventContainer.appendChild(eventElement);
    });
}

// Render a simple calendar
function renderCalendar() {
    const calendar = document.getElementById("calendar");
    const daysInMonth = 31;

    for (let day = 1; day <= daysInMonth; day++) {
        const dayElement = document.createElement("div");
        dayElement.classList.add("day");
        dayElement.textContent = day;

        if (events.some(event => event.date.endsWith(`-${String(day).padStart(2, "0")}`))) {
            dayElement.classList.add("highlight");
        }

        calendar.appendChild(dayElement);
    }
}

// Initialize the page
document.addEventListener("DOMContentLoaded", () => {
    renderEvents();
    renderCalendar();
});
