// Fetch new_nodes function
function fetchNodes() {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "fetch_nodes.php", true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            document.querySelector(".container_new_nodes").innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

// Fetch nodes every 5 seconds
setInterval(fetchNodes, 5000);

// Initial fetch
fetchNodes();

// Submit form data and log response
document.querySelector("form").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent default form submission

    const formData = new FormData(event.target);
    fetch("submit_bag.php", {
        method: "POST",
        body: formData,
    })
        .then((response) => response.json())
        .then((data) => {
            console.log(data); // Log the response to the console
            if (data.success) {
                alert(data.message);
            } else {
                alert("Error: " + data.error);
            }
        })
        .catch((error) => console.error("Fetch error:", error));
});

// Add click event listener to populate form fields and highlight selected container
document.querySelector(".container_new_nodes").addEventListener("click", function (event) {
    const target = event.target.closest(".new-bag-info");
    if (target) {
        // Remove 'selected' class from previously selected container
        document.querySelectorAll(".bag-info.selected").forEach((el) => el.classList.remove("selected"));

        // Add 'selected' class to the clicked container
        target.classList.add("selected");

        // Populate form fields
        const ipAddress = target.getAttribute("data-ipaddress");
        const macAddress = target.getAttribute("data-macaddress");

        if (ipAddress && macAddress) {
            document.getElementById("ipaddress").value = ipAddress;
            document.getElementById("macaddress").value = macAddress;
        }
    }
});