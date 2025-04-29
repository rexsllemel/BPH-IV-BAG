const toggleButton = document.getElementsByClassName('add-bag');
const sidebar = document.getElementById('sidebar');
const theme = {
    primary: "#1976d2", // blue color
    error: "#d32f2f"    // red color
  };
  
  

function toggleSidebar(){
    sidebar.classList.toggle('close');
    toggleButton.classList.toggle('rotate');

    closeAllSubMenus();
}

function toggleSubMenu(button){

    if(!button.nextElementSibling.classList.contains('show')){

        closeAllSubMenus();
    }    

    button.nextElementSibling.classList.toggle('show');
    button.classList.toggle('rotate');

    if(sidebar.classList.contains('close')){
        sidebar.classList.toggle('close');
        toggleButton.classList.toggle('rotate');
    }
}

function closeAllSubMenus(){
    Array.from(sidebar.getElementsByClassName('show')).forEach(ul => {
        ul.classList.remove('show');
        ul.previousElementSibling.classList.remove('rotate');

    }
    );
}


let previousData = [];

    function fetchUpdates() {
        fetch('./fetch_info.php') // Ensure the correct endpoint is used
            .then(response => response.json())
            .then(data => {
                if (JSON.stringify(previousData) !== JSON.stringify(data)) { // Check if data has changed
                    // console.log('Data updated, refreshing container...');
                    updateContainer(data);
                    previousData = data; // Update stored data
                }
            })
            .catch(error => console.error('Error fetching updates:', error));
    }

    function updateContainer(data, searchTerm = "") {
        const container = document.querySelector('.container');
        container.innerHTML = ""; // Clear the container before adding new elements

        // Sort data based on priority
        data.sort((a, b) => {
            return getPriority(a) - getPriority(b);
        });

        const filteredData = data.filter(item => {
            const searchIn = `
                ${item.name || ""} 
                ${item.lastname || ""} 
                ${item.room || ""}
                ${item.contact || ""}
                ${item.watcher || ""}
                ${item.illness || ""}
                ${item.dateAdmitted || ""}
            `.toLowerCase();
            return searchIn.includes(searchTerm.toLowerCase());
        });

        filteredData.forEach(item => {
            let bagInfo = document.createElement('div');
            bagInfo.setAttribute('data-id', item.id);
            const isActive = (item.active_status === 1) ? "Active" : "Inactive";
            const statusColor = (isActive === "Active") ? theme.primary : theme.error;
            bagInfo.innerHTML = `
                <div class="card">
                    <div class="row">
                        <div class="column">
                        <div class="field">
                            <div class="label">Name:</div>
                            <div class="value">${item.name}</div>
                        </div>
                        <div class="field">
                            <div class="label">Last Name:</div>
                            <div class="value">${item.lastname}</div>
                        </div>
                        <div class="field">
                            <div class="label">IV Bag Level:</div>
                            <div class="value">${item.ivbag_level}</div>
                        </div>
                        <div class="field">
                            <div class="label">IV Bag Type:</div>
                            <div class="value">${item.ivbag_type}</div>
                        </div>
                        <div class="field">
                            <div class="label">Room:</div>
                            <div class="value">${item.room}</div>
                        </div>
                        <div class="field">
                            <div class="label">Contact #:</div>
                            <div class="value">${item.contact}</div>
                        </div>
                        <div class="field">
                            <div class="label">Watcher:</div>
                            <div class="value">${item.watcher}</div>
                        </div>
                        <div class="field">
                            <div class="label">Illness:</div>
                            <div class="value">${item.illness}</div>
                        </div>
                        <div class="field">
                            <div class="label">Sensor Status:</div>
                            <div style="color: ${statusColor}">${isActive}</div>
                        </div>
                        <div class="field">
                            <div class="label">Date Admitted:</div>
                            <div class="value">${item.dateAdmitted}</div>
                        </div>
                        </div>
                    </div>
                    </div>
            `;
            container.appendChild(bagInfo);
        });
    }

    function getPriority(item) {
        if (item.active_status  == 1) return 0; 
        return; 
    }
    fetchUpdates();
    document.getElementById('searchInput').addEventListener('input', function() {
        const searchTerm = this.value;
        updateContainer(previousData, searchTerm); // use the latest fetched data
    });
    