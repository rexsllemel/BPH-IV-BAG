const toggleButton = document.getElementsByClassName('add-bag');
const sidebar = document.getElementById('sidebar');

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
        fetch('./fetch_data.php') // Ensure the correct endpoint is used
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

    function updateContainer(data) {
        const container = document.querySelector('.container');
        container.innerHTML = ""; // Clear the container before adding new elements

        // Sort data based on priority
        data.sort((a, b) => {
            return getPriority(a) - getPriority(b);
        });

        data.forEach(item => {
            let bagInfo = document.createElement('div');
            bagInfo.classList.add('bag-info');
            bagInfo.setAttribute('data-id', item.id);

            // Check if ip_active is explicitly false then it's offline
            const boxColor = item.ip_active === false ? 'firebrick' : '';
            const circleColor = item.ip_active === false ? 'grey' : getColor(item.ivbag_level);
            const backflow_circleColor = item.ip_active === false ? 'grey' : getColor(item.backflow);
            let bflow_circleColor;

            if (item.ip_active === false) {
                bflow_circleColor = 'grey';
            }else if (item.backflow == 1) {
                bflow_circleColor = 'red';
            }else{
                bflow_circleColor = 'green';
            }

            bagInfo.style.backgroundColor = boxColor;

            bagInfo.innerHTML = `
            <p class="bag-name">${item.name}</p>
            <p class="bag-name">${item.lastname}</p>
            <br>
                <div class="bag-info-details">
                    <p>
                        <img src="iconset/iv-bag.png" alt="IV Bag Icon" style="width: 24px; height: 24px;">
                        <br>
                        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <circle class="ivbag-circle" cx="12" cy="12" r="10" stroke="black" stroke-width="2" fill="${circleColor}" />
                        </svg>
                    </p>
                    <p>
                        <img src="iconset/blood.png" alt="Blood Backflow Icon" style="width: 24px; height: 24px;">
                        <br>
                        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <circle class="backflow-circle" cx="12" cy="12" r="10" stroke="black" stroke-width="2" fill="${bflow_circleColor}" />
                        </svg>
                    </p>
                </div>
                <div>
                    
                    <p class="bag-room">Room: ${item.room}</p>
                    <p class="bag-room">Bag Type: ${item.ivbag_type}</p>
                    <p class="bag-room">${item.contact}</p>
                </div>
            `;
            container.appendChild(bagInfo);
        });
    }

    function getColor(level) {
        if (level < 15) return 'red'; // Critical level
        if (level <= 49) return 'yellow'; // Warning level
        return 'green'; // Normal level
    }

    function getPriority(item) {
        if (item.ivbag_level < 15) return 0; // Critical IV bag level (red)
        if (item.backflow == 1) return 1; // Blood backflow (red)
        if (item.ivbag_level <= 49) return 2; // Warning IV bag level (yellow)
        return 3; // Normal conditions
    }

    // Fetch updates on page load and every 5 seconds
    fetchUpdates();
    setInterval(fetchUpdates, 5000);
