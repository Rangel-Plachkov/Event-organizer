document.addEventListener("DOMContentLoaded", () => {
    const filterSelect = document.getElementById("event-filter-select");
    const allSections = document.querySelectorAll(".event-subsection");

    const hideAllSections = () => {
        allSections.forEach((section) => {
            section.style.display = "none";
        });
    };

    const showSection = (sectionClass) => {
        const section = document.querySelector(sectionClass);
        if (section) {
            section.style.display = "block";
        }
    };

    //Show only All Events section on page load
    hideAllSections();
    showSection(".all-events");

    filterSelect.addEventListener("change", (event) => {
        const selectedValue = event.target.value;

        hideAllSections();

        if (selectedValue === "all") {
            showSection(".all-events");
        } else if (selectedValue === "hidden-follower") {
            showSection(".hidden-follower-events");
        } else if (selectedValue === "participant-follower") {
            showSection(".participant-follower-events");
        } else if (selectedValue === "with-organization") {
            showSection(".organization-events");
        }
    });
});