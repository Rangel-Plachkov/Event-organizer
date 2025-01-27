document.addEventListener("DOMContentLoaded", () => {
    const filterSelect = document.getElementById("event-filter-select");
    const allSections = document.querySelectorAll(".event-subsection");

    filterSelect.addEventListener("change", (event) => {
        const selectedValue = event.target.value;

        allSections.forEach((section) => {
            section.style.display = "none";
        });

        if (selectedValue === "all") {
            allSections.forEach((section) => {
                section.style.display = "block";
            });
        } else if (selectedValue === "hidden-follower") {
            document.querySelector(".hidden-follower-events").style.display = "block";
        } else if (selectedValue === "participant-follower") {
            document.querySelector(".participant-follower-events").style.display = "block";
        } else if (selectedValue === "with-organization") {
            document.querySelector(".organization-events").style.display = "block";
        } else if (selectedValue === "other") {
            document.querySelector(".other-events").style.display = "block";
        }
    });
});