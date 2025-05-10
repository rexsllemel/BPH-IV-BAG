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

    function updateContainer(data, searchTerm = "") {
        const container = document.querySelector('.info_container');
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
            console.log('Item macaddress:', item.macaddress); // Debugging: Log macaddress to verify
            let bagInfo = document.createElement('div');
            bagInfo.classList.add('info_card');
            bagInfo.setAttribute('data-id', item.macaddress); // Ensure macaddress is set as data-id
            const isActive = (item.active_status === 1) ? "Active" : "Inactive";
            const isBackflow = (item.backflow === 1) ? "Yes" : "No";
            const statusColor = (isActive === "Active") ? theme.primary : theme.error;
            bagInfo.innerHTML = `
                <div class="info_row">
                    <div class="info_column">
                        <div class="info_field">
                            <div class="info_label">Name:</div>
                            <div class="info_value">${item.name}</div>
                        </div>
                        <div class="info_field">
                            <div class="info_label">Last Name:</div>
                            <div class="info_value">${item.lastname}</div>
                        </div>
                        <div class="info_field">
                            <div class="info_label">IV Bag Level:</div>
                            <div class="info_value">${item.ivbag_level}%</div>
                        </div>
                        <div class="info_field">
                            <div class="info_label">Blood Backflow:</div>
                            <div class="info_value">${isBackflow}</div>
                        </div>
                        <div class="info_field">
                            <div class="info_label">IV Bag Type:</div>
                            <div class="info_value">${item.ivbag_type}</div>
                        </div>
                        <div class="info_field">
                            <div class="info_label">Room:</div>
                            <div class="info_value">${item.room}</div>
                        </div>
                        <div class="info_field">
                            <div class="info_label">Watcher:</div>
                            <div class="info_value">${item.watcher}</div>
                        </div>
                        <div class="info_field">
                            <div class="info_label">Contact #:</div>
                            <div class="info_value">${item.contact}</div>
                        </div>
                        <div class="info_field">
                            <div class="info_label">Illness:</div>
                            <div class="info_value">${item.illness}</div>
                        </div>
                        <div class="info_field">
                            <div class="info_label">Sensor Status:</div>
                            <div style="color: ${statusColor}">${isActive}</div>
                        </div>
                        <div class="info_field">
                            <div class="info_label">Date Admitted:</div>
                            <div class="info_value">${item.dateAdmitted}</div>
                        </div>
                        <div class="info_field">
                            <div class="info_label">Nurse Station:</div>
                            <div class="info_value">${item.station}</div>
                        </div>
                    </div>
                </div>
                <div class="info_actions">
                        <button class="qr-btn" title="Generate QR">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M80-680v-200h200v80H160v120H80Zm0 600v-200h80v120h120v80H80Zm600 0v-80h120v-120h80v200H680Zm120-600v-120H680v-80h200v200h-80ZM700-260h60v60h-60v-60Zm0-120h60v60h-60v-60Zm-60 60h60v60h-60v-60Zm-60 60h60v60h-60v-60Zm-60-60h60v60h-60v-60Zm120-120h60v60h-60v-60Zm-60 60h60v60h-60v-60Zm-60-60h60v60h-60v-60Zm240-320v240H520v-240h240ZM440-440v240H200v-240h240Zm0-320v240H200v-240h240Zm-60 500v-120H260v120h120Zm0-320v-120H260v120h120Zm320 0v-120H580v120h120Z"/></svg>
                        </button>
                        <button class="edit-btn" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/></svg>
                        </button>
                        <button class="delete-btn" title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
                        </button>
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
    setInterval(fetchUpdates, 5000);
    document.getElementById('searchInput').addEventListener('input', function() {
        const searchTerm = this.value;
        updateContainer(previousData, searchTerm); // use the latest fetched data
    });

function showQRCode(macaddress) {
    const qrModal = document.createElement('div');
    qrModal.id = 'qrModal';
    qrModal.style.position = 'fixed';
    qrModal.style.top = '0';
    qrModal.style.left = '0';
    qrModal.style.width = '100%';
    qrModal.style.height = '100%';
    qrModal.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
    qrModal.style.display = 'flex';
    qrModal.style.justifyContent = 'center';
    qrModal.style.alignItems = 'center';
    qrModal.style.zIndex = '1000';

    const qrContent = document.createElement('div');
    qrContent.style.backgroundColor = 'white';
    qrContent.style.padding = '20px';
    qrContent.style.borderRadius = '8px';
    qrContent.style.textAlign = 'center';
    qrContent.style.position = 'relative';

    const qrImage = document.createElement('img');
    qrImage.src = `https://api.qrserver.com/v1/create-qr-code/?data=https://bphmaramag.faithcloud.net/patientqr.php?mac=${macaddress}&size=200x200`;
    qrImage.alt = 'QR Code';
    qrImage.style.marginBottom = '20px'; // Add margin below the QR code

    const closeButton = document.createElement('button');
    closeButton.textContent = 'Close';
    closeButton.style.position = 'absolute';
    closeButton.style.bottom = '10px';
    closeButton.style.left = '50%';
    closeButton.style.transform = 'translateX(-50%)';
    closeButton.style.padding = '10px 20px';
    closeButton.style.border = 'none';
    closeButton.style.borderRadius = '4px';
    closeButton.style.backgroundColor = '#d32f2f';
    closeButton.style.color = 'white';
    closeButton.style.cursor = 'pointer';

    closeButton.addEventListener('click', () => {
        document.body.removeChild(qrModal);
    });

    qrModal.addEventListener('click', (event) => {
        if (event.target === qrModal) {
            document.body.removeChild(qrModal);
        }
    });

    qrContent.appendChild(qrImage);
    qrContent.appendChild(closeButton);
    qrModal.appendChild(qrContent);
    document.body.appendChild(qrModal);
}

// Update the QR button click handler
document.addEventListener('click', function (event) {
    const qrButton = event.target.closest('.qr-btn');
    if (qrButton) {
        console.log('QR Button clicked:', qrButton); // Debugging: Log the clicked QR button
        const infoCard = qrButton.closest('.info_card'); // Ensure we get the correct parent element
        console.log('Parent info_card element:', infoCard); // Debugging: Log the parent info_card element
        const macaddress = infoCard ? infoCard.getAttribute('data-id') : null; // Retrieve data-id
        console.log('Retrieved macaddress:', macaddress); // Debugging: Log the retrieved macaddress
        if (macaddress) {
            showQRCode(macaddress);
        } else {
            console.error('Failed to retrieve macaddress for QR code generation.');
        }
    }
});

// Function to show edit form
function showEditForm(item) {
    const editModal = document.createElement('div');
    editModal.id = 'editModal';
    editModal.style.position = 'fixed';
    editModal.style.top = '0';
    editModal.style.left = '0';
    editModal.style.width = '100%';
    editModal.style.height = '100%';
    editModal.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
    editModal.style.display = 'flex';
    editModal.style.justifyContent = 'center';
    editModal.style.alignItems = 'center';
    editModal.style.zIndex = '1000';

    const editContent = document.createElement('div');
    editContent.style.backgroundColor = 'white';
    editContent.style.padding = '20px';
    editContent.style.borderRadius = '8px';
    editContent.style.width = '80%';
    editContent.style.maxWidth = '600px';
    editContent.style.maxHeight = '80vh';
    editContent.style.overflowY = 'auto';
    editContent.style.position = 'relative';

    // Create station options with the current station selected
    const stationOptions = ['Station 1', 'Station 2', 'Station 3']
        .map(station => `<option value="${station}" ${item.station === station ? 'selected' : ''}>${station}</option>`)
        .join('');

    editContent.innerHTML = `
        <h3 style="margin-bottom: 20px; text-align: center;">Edit Patient Information</h3>
        <form id="editForm">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <label for="editName">Name:</label>
                    <input type="text" id="editName" value="${item.name || ''}" required>
                </div>
                <div>
                    <label for="editLastname">Last Name:</label>
                    <input type="text" id="editLastname" value="${item.lastname || ''}" required>
                </div>
                <div>
                    <label for="editRoom">Room:</label>
                    <input type="text" id="editRoom" value="${item.room || ''}" required>
                </div>
                <div>
                    <label for="editWatcher">Watcher:</label>
                    <input type="text" id="editWatcher" value="${item.watcher || ''}">
                </div>
                <div>
                    <label for="editContact">Contact #:</label>
                    <input type="text" id="editContact" value="${item.contact || ''}">
                </div>
                <div>
                    <label for="editIllness">Illness:</label>
                    <input type="text" id="editIllness" value="${item.illness || ''}">
                </div>
                <div>
                    <label for="editDateAdmitted">Date Admitted:</label>
                    <input type="date" id="editDateAdmitted" value="${item.dateAdmitted || ''}">
                </div>
                <div>
                    <label for="editStation">Station:</label>
                    <select id="editStation" required>
                        <option value="">--Select Station--</option>
                        ${stationOptions}
                    </select>
                </div>
                <div>
                    <label for="editIvbagType">IV Bag Type:</label>
                    <input type="text" id="editIvbagType" value="${item.ivbag_type || ''}">
                </div>
            </div>
            <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                <button type="button" id="cancelEdit" style="padding: 10px 20px; background: #d32f2f; color: white; border: none; border-radius: 4px; cursor: pointer;">Cancel</button>
                <button type="submit" style="padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">Save Changes</button>
            </div>
        </form>
    `;

    editModal.appendChild(editContent);
    document.body.appendChild(editModal);

    // Handle form submission
    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        updatePatientData(item.macaddress);
    });

    // Handle cancel button
    document.getElementById('cancelEdit').addEventListener('click', function() {
        document.body.removeChild(editModal);
    });

    // Close modal when clicking outside
    editModal.addEventListener('click', (event) => {
        if (event.target === editModal) {
            document.body.removeChild(editModal);
        }
    });
}
// Function to update IV bag type based on selected station
// function updateIVBagTypeBasedOnStation() {
//     const stationSelect = document.getElementById('editStation');
//     const ivBagTypeInput = document.getElementById('editIvbagType');
    
//     if (stationSelect.value) {
//         // You can customize this mapping based on your needs
//         const typeMapping = {
//             'Station 1': 'Type A',
//             'Station 2': 'Type B',
//             'Station 3': 'Type C'
//         };
        
//         ivBagTypeInput.value = typeMapping[stationSelect.value] || '';
//     } else {
//         ivBagTypeInput.value = '';
//     }
// }

// Function to update patient data in Firebase
function updatePatientData(macaddress) {
    const updatedData = {
        name: document.getElementById('editName').value,
        lastname: document.getElementById('editLastname').value,
        room: document.getElementById('editRoom').value,
        watcher: document.getElementById('editWatcher').value,
        contact: document.getElementById('editContact').value,
        illness: document.getElementById('editIllness').value,
        dateAdmitted: document.getElementById('editDateAdmitted').value,
        station: document.getElementById('editStation').value,
        ivbag_type: document.getElementById('editIvbagType').value
    };

    fetch(`https://ivbag-c6fd2-default-rtdb.firebaseio.com/bag_info/${macaddress}.json`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(updatedData)
    })
    .then(response => {
        if (response.ok) {
            alert("Patient information updated successfully!");
            document.body.removeChild(document.getElementById('editModal'));
            fetchUpdates(); // Refresh the data
        } else {
            throw new Error('Failed to update patient information');
        }
    })
    .catch(error => {
        console.error('Error updating patient:', error);
        alert("An error occurred while updating patient information.");
    });
}

// Add event listener for edit buttons
document.addEventListener('click', function (event) {
    const editButton = event.target.closest('.edit-btn');
    if (editButton) {
        const infoCard = editButton.closest('.info_card');
        const macaddress = infoCard ? infoCard.getAttribute('data-id') : null;
        
        if (macaddress) {
            // Find the patient data in previousData
            const patientData = previousData.find(item => item.macaddress === macaddress);
            if (patientData) {
                showEditForm(patientData);
            } else {
                console.error('Patient data not found for macaddress:', macaddress);
            }
        } else {
            console.error('Failed to retrieve macaddress for editing.');
        }
    }
});

function deleteItem(macaddress) {
    if (confirm("Are you sure you want to delete this item?")) {
        fetch(`https://ivbag-c6fd2-default-rtdb.firebaseio.com/bag_info/${macaddress}.json`, {
            method: 'DELETE',
        })
        .then(response => {
            if (response.ok) {
                alert("Item deleted successfully.");
                fetchUpdates(); // Refresh the data after deletion
            } else {
                alert("Failed to delete the item.");
            }
        })
        .catch(error => {
            console.error("Error deleting item:", error);
            alert("An error occurred while deleting the item.");
        });
    }
}

// Update the delete button click handler
document.addEventListener('click', function (event) {
    const deleteButton = event.target.closest('.delete-btn');
    if (deleteButton) {
        const infoCard = deleteButton.closest('.info_card'); // Ensure we get the correct parent element
        const macaddress = infoCard ? infoCard.getAttribute('data-id') : null; // Retrieve data-id
        console.log('Delete Button clicked, macaddress:', macaddress); // Debugging: Log macaddress
        if (macaddress) {
            deleteItem(macaddress);
        } else {
            console.error('Failed to retrieve macaddress for deletion.');
        }
    }
});
