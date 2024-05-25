const alert = document.getElementById("alert");

if (alert) {
  setTimeout(function() {
    alert.style.display = 'none';
  }, 6000);
}


$(function() {
  let table1 = new DataTable('#obosTable', {
      // DataTable options for the first table
  });

  let table2 = new DataTable('#itemModalTable', {
      // DataTable options for the second table
  });

  let table3 = new DataTable('#inspectorModalTable', {
    // DataTable options for the second table
  });

  let table4 = new DataTable('#violationModalTable', {
    // DataTable options for the second table
  });
});

let printButton = document.getElementById("print-button");

if (printButton) {
  printButton.addEventListener('click', (event) => {
    // Prevent default form submission behavior
    // Initiate print dialog
    window.print();
  });
}

function carousel(carouselForm, previous = '.previous-container', next = '.next-container', submit = '.formSubmit') {

  document.addEventListener('DOMContentLoaded', function () {
    var carousel = document.getElementById(carouselForm);
    var prevBtn = document.querySelector(previous);
    var nextBtn = document.querySelector(next);
    var submitBtn = document.querySelector(submit);

    if (carousel) {
      carousel.addEventListener('slid.bs.carousel', function () {
        var currentIndex = $('.carousel-item.active').index();
   
        var totalItems = $('.carousel-item').length;
        if (currentIndex == 0) {
          prevBtn.classList.add('invisible');
          nextBtn.classList.remove('invisible');
          
        } else if (totalItems - 1 == currentIndex) {
          prevBtn.classList.remove('invisible');
          nextBtn.classList.add('invisible');
          submitBtn.classList.remove('d-none');
        } else {
          prevBtn.classList.remove('invisible');
          nextBtn.classList.remove('invisible');
          submitBtn.classList.add('d-none');
        }
      
      });
    }

  });

}

carousel('inspectionCarousel');
carousel('certificateCarousel');
carousel('scheduleCarousel');


function businessDataFetch(businessId) {
  document.addEventListener("DOMContentLoaded", function () {
    if (document.getElementById(businessId)) {
      let busId = document.getElementById(businessId);
  
      function owner () {
          let busIdValue = busId.value;
          let business = new XMLHttpRequest();
  
          let url = "./../json_response/business.php?bus_id=" + busIdValue;
      
          business.open("GET", url, true);
          business.onreadystatechange = function () {
              if (business.readyState === 4 && business.status === 200) {
                  
                  let businessDetails = JSON.parse(business.responseText);
                
                  let busImg = document.getElementById("bus-img");
                  busImg.src = `./../business/images/${businessDetails.bus_img_url??'no-image.png'}`;
              
                  if (businessId !== 'schedule-business-id') {

                    let busName = document.getElementById("bus-name");
                    busName.value = businessDetails.bus_name;
                
                    let ownerId = document.getElementById("owner-id");
                    ownerId.value = businessDetails.owner_id;
    
                    let ownerName= document.getElementById("owner-name");
                    ownerName.value = businessDetails.owner_name;

                    let busAddress = document.getElementById("bus-address");
                    busAddress.value = businessDetails.bus_address;

                    if (businessId === 'certificate-business-id') {
                      
                      let busGroup = document.getElementById("bus-group");
                      busGroup.value = businessDetails.occupancy_group;
      
                      let characterOfOccupancy = document.getElementById("character-of-occupancy");
                      characterOfOccupancy.value = businessDetails.character_of_occupancy;
                    }
                    
                  }
                 
                  let carouselItemContainer = document.querySelector(".carousel-item");
                  let hiddenElements = carouselItemContainer.querySelectorAll(".d-none");
  
                  hiddenElements.forEach(function(element) {
                      element.classList.remove("d-none");
                  });
  
                  
              }
          }
          business.send();
      }
  
      busId.addEventListener("change", owner);
    }
  });
}

businessDataFetch('certificate-business-id');
businessDataFetch('schedule-business-id');

document.addEventListener('DOMContentLoaded', function () {
  if (document.getElementById('character-of-occupancy')) {
    let characterOfOccupancy = document.getElementById('character-of-occupancy');

    characterOfOccupancy.addEventListener('change', function() {
      let characterOfOccupancyValue = characterOfOccupancy.value;

      let group = new XMLHttpRequest();
      group.open('GET', `./json_response/group.php?character_of_occupancy=${encodeURIComponent(characterOfOccupancyValue)}`, true);

      group.onreadystatechange = function() {
        if (group.readyState === 4 && group.status === 200) {
                  
          let groupDetails = JSON.parse(group.responseText);

          let occupancyGroup = document.getElementById('occupancy-group');
          let occupancyGroupContainer = document.getElementById('occupancy-group-container');
          occupancyGroupContainer.classList.remove('d-none');
          occupancyGroup.value = groupDetails.occupancy_group;

          let occupancyClassificationId = document.getElementById('occupancy-classification-id');
          occupancyClassificationId.value = groupDetails.occupancy_classification_id
        }
      }
      group.send();
    });

  }
});

// BUILDING BILLING
document.addEventListener('DOMContentLoaded', function() {
  if (document.getElementById('bldg-section')) {
    let buildingSection = document.getElementById('bldg-section');
    let propertyAttributeContainer = document.getElementById('prop-attr-container');
    buildingSection.addEventListener('change', function() {
      let buildingSectionValue = buildingSection.value;

      if (buildingSectionValue) {
        
        let propertyAttribute = document.getElementById('bldg-property-attribute');

        let bldgPropAttr = new XMLHttpRequest();
        bldgPropAttr.open("GET", `./json_response/building-property-attribute.php?bldg_section=${buildingSectionValue}`);

        bldgPropAttr.onreadystatechange = function () {
          if (bldgPropAttr.readyState === 4 && bldgPropAttr.status === 200) {
            let bldgPropAttrDetails = JSON.parse(bldgPropAttr.responseText);

            propertyAttribute.innerHTML = "";

            let propAttrDefaultOption = document.createElement("option");
            propAttrDefaultOption.value = "";
            propAttrDefaultOption.text = buildingSectionValue ? "Select" : "No Data";
            propAttrDefaultOption.selected = true;
            propAttrDefaultOption.disabled = true;
            propAttrDefaultOption.hidden = true;
            propertyAttribute.appendChild(propAttrDefaultOption);

            bldgPropAttrDetails.bldg_property_attributes.forEach(bldg_property_attribute => {
              let option = document.createElement("option");
              option.value = bldg_property_attribute;
              option.text = bldg_property_attribute;
              propertyAttribute.appendChild(option);
            });

            propertyAttributeContainer.classList.remove('d-none');
            document.getElementById("bldg-fee-container").classList.add('d-none');

          }
        }
        bldgPropAttr.send();
  
        propertyAttribute.addEventListener('change', function () {
          
          let propertyAttributeValue = propertyAttribute.value;

          let buildingBilling = new XMLHttpRequest();
          buildingBilling.open("GET", `./json_response/building-fee.php?bldg_section=${encodeURIComponent(buildingSectionValue)}&bldg_property_attribute=${encodeURIComponent(propertyAttributeValue)}`, true);

          buildingBilling.onreadystatechange = function () {
            if (buildingBilling.readyState === 4 && buildingBilling.status === 200) {
              let buildingBillingDetails = JSON.parse(buildingBilling.responseText);

              document.getElementById("bldg-fee-container").classList.remove('d-none');

              let buildingFee = document.getElementById('bldg-fee');
              buildingFee.value = buildingBillingDetails.bldg_fee;

              let bldgBillingId = document.getElementById('bldg-billing-id');
              bldgBillingId.value = buildingBillingDetails.bldg_billing_id;



            }
          }

          buildingBilling.send();
        });
        
      }

    });
  }

});

// SANITARY BILLING
if (document.getElementById("sanitary-quantity")) {
  let sanitaryFee = document.getElementById("sanitary-fee");
  let sanitaryFeeValue = sanitaryFee.value
  let sanitaryQuantity = document.getElementById("sanitary-quantity");

  // Function to calculate total fee
  function calculateTotalFee() {
    let quantity = parseFloat(sanitaryQuantity.value);
    let fee = parseFloat(sanitaryFeeValue).toFixed(2);
    if (isNaN(quantity) || isNaN(fee) || quantity === 0) {
        // If either quantity or fee is not a number, reset fee input field
        sanitaryFee.value = parseFloat(sanitaryFeeValue).toFixed(2);
    } else {
        // Calculate total fee and update fee input field
        let totalFee = quantity * fee;
        sanitaryFee.value = totalFee.toFixed(2); // assuming you want to keep it as a float with 2 decimal places
    }
  }

  sanitaryQuantity.addEventListener("input", function() {
    calculateTotalFee();
  });
  
}


// SIGNAGE BILLING
document.addEventListener('DOMContentLoaded', function() {
  if (document.getElementById('display-type')) {
    let displayType = document.getElementById('display-type');
    let signTypeContainer = document.getElementById('sign-type-container');

    displayType.addEventListener('change', function() {
      let displayTypeValue = displayType.value;

      if (displayTypeValue) {
        
        let signType = document.getElementById('sign-type');

        let sign_type = new XMLHttpRequest();
        sign_type.open("GET", `./json_response/sign-type.php?display_type=${displayTypeValue}`);

        sign_type.onreadystatechange = function () {
          if (sign_type.readyState === 4 && sign_type.status === 200) {
            let sign_type_details = JSON.parse(sign_type.responseText);

            signType.innerHTML = "";

            let signTypeDefaultOption = document.createElement("option");
            signTypeDefaultOption.value = "";
            signTypeDefaultOption.text = displayTypeValue ? "Select" : "No Data";
            signTypeDefaultOption.selected = true;
            signTypeDefaultOption.disabled = true;
            signTypeDefaultOption.hidden = true;
            signType.appendChild(signTypeDefaultOption);

            sign_type_details.sign_types.forEach(sign_type => {
              let option = document.createElement("option");
              option.value = sign_type;
              option.text = sign_type;
              signType.appendChild(option);
            });

            signTypeContainer.classList.remove('d-none');
            document.getElementById("signage-fee-container").classList.add('d-none');

          }
        }
        sign_type.send();
  
        signType.addEventListener('change', function () {
          
          let signTypeValue = signType.value;

          let signageBilling = new XMLHttpRequest();
          signageBilling.open("GET", `./json_response/signage-fee.php?display_type=${encodeURIComponent(displayTypeValue)}&sign_type=${encodeURIComponent(signTypeValue)}`, true);

          signageBilling.onreadystatechange = function () {
            if (signageBilling.readyState === 4 && signageBilling.status === 200) {
              let signageBillingDetails = JSON.parse(signageBilling.responseText);

              document.getElementById("signage-fee-container").classList.remove('d-none');

              let signageId = document.getElementById('signage-id');
              signageId.value = signageBillingDetails.signage_id;

              let signageFee = document.getElementById('signage-fee');
              signageFee.value = signageBillingDetails.signage_fee;
            }
          }

          signageBilling.send();
        });
        
      }

    });
  }

});



document.addEventListener("DOMContentLoaded", function () {
  let wrapper = document.getElementById("item-list");
  let selectItemButtons = document.querySelectorAll(".select-item");
  let deleteItemButton = document.getElementById("delete-item");
  let totalItem = document.getElementById("total-item");
  let counter = parseInt(totalItem.innerText) || 0; // Initialize counter
  
  
  // Inside the loop where you're adding event listeners for select item buttons
  for (let i = 0; i < selectItemButtons.length; i++) {
    selectItemButtons[i].addEventListener("click", function (event) {
        event.preventDefault();

        let itemId = this.getAttribute("data-item-id");

        // Make an AJAX request to fetch the item details
        let item = new XMLHttpRequest();
        item.open("GET", `./json_response/item.php?item_id=${itemId}`, true);
        item.onreadystatechange = function () {
            if (item.readyState === 4 && item.status === 200) {
                var itemDetails = JSON.parse(item.responseText);

                // Increment counter for each click
                counter++;

                //Item Container
                let itemContainer = document.getElementById('item-container');

                //Item Content Container
                let itemContent = createContainerDiv('shadow bg-white rounded p-3 mb-2', `item-content-${counter}`);
                itemContainer.appendChild(itemContent);

                let itemTitle = createTitle(`Item ${counter}`, `item-title-${counter}`);
                itemContent.appendChild(itemTitle);

                // Create and append item name container div
                let itemNameContainer = createContainerDiv('col col-12 p-0 form-group mb-1');
                itemContent.appendChild(itemNameContainer);

                // Create and append item name label
                let itemNameLabel = createLabel(`Item Name`);
                itemNameContainer.appendChild(itemNameLabel);

                let itemNameInputField = createInputField('text', `item-name-${counter}`, `item_name[]`);
                itemNameContainer.appendChild(itemNameInputField);
                itemNameInputField.value = itemDetails.item_name;

                // Category Field 
                let categoryNameContainer = createContainerDiv('col col-12 p-0 form-group mb-1');
                itemContent.appendChild(categoryNameContainer);

                let categoryName = createLabel(`Category`);
                categoryNameContainer.appendChild(categoryName);

                let categoryNameInputField = createInputField('text', `category-name-${counter}`, `category_name[]`);
                categoryNameInputField.readOnly = true; // Readonly
                categoryNameContainer.appendChild(categoryNameInputField);
                categoryNameInputField.value = itemDetails.category_name;

                // Section Field
                let sectionContainer = createContainerDiv('col col-12 p-0 form-group mb-1');
                itemContent.appendChild(sectionContainer);

                let section = createLabel(`Section`);
                sectionContainer.appendChild(section);

                let sectionInputField = createInputField('text', `section-${counter}`, `section[]`);
                sectionInputField.readOnly = true; // Readonly
                sectionContainer.appendChild(sectionInputField);
                sectionInputField.value = itemDetails.section ? itemDetails.section: 'No Data';

                if (itemDetails.category_name === 'Electronics') {

                    let sectionValue = itemDetails.section;
                    let electronicsFee = new XMLHttpRequest();
                    electronicsFee.open("POST", "./json_response/electronics-fee.php", true);
                    electronicsFee.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    electronicsFee.onreadystatechange = function () {
                      if (electronicsFee.readyState === 4 && electronicsFee.status === 200) {
                        let response = JSON.parse(electronicsFee.responseText);
  
                          console.log(response);
                          // Store the initial fee value
                          originalFeeValue = parseFloat(response.fee);
  
                          // Set the fee input field value
                          feeInputField.value = parseFloat(response.fee).toFixed(2);
                          billingIdHiddenInput.value = response.billing_id;

                          $quantity = document.getElementById(`quantity-${counter}`);
                          $quantity.removeAttribute('readonly');
                        }
                      };
                      // Send selected section as parameter
                    electronicsFee.send(`section=${encodeURIComponent(sectionValue)}`);
              
                } else {
                  
                  // Capacity Field
                  let capacityContainer = createContainerDiv('form-group d-flex flex-column flex-md-grow-1');
                  itemContent.appendChild(capacityContainer);

                  let capacityLabel = createLabel(`Capacity`);
                  capacityContainer.appendChild(capacityLabel);

                  let capacityFieldContainer = createContainerDiv('d-flex align-items-center justify-content-center select-container');
                  capacityContainer.appendChild(capacityFieldContainer);

                  let capacitySelect = document.createElement('select');
                  capacitySelect.classList.add('form-control');
                  capacitySelect.classList.add('form-select');
                  capacitySelect.id = `capacity-${counter}`;
                  capacitySelect.name = 'capacity[]';
                  capacityFieldContainer.appendChild(capacitySelect);

                  // Function to update the capacities based on the selected section
                  function updateCapacities() {
                    let selectedSection = itemDetails.section;
                    // Make an AJAX request to fetch capacities
                    let xhr = new XMLHttpRequest();
                    xhr.open("POST", "./json_response/capacities.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                          let response = JSON.parse(xhr.responseText);
                          
                          capacitySelect.innerHTML = "";
                          
                          // Capacity Default Option
                          let capacityDefaultOption = document.createElement("option");
                          capacityDefaultOption.value = "";
                          capacityDefaultOption.text = response.capacities.length > 0 ? "Select" : "No Registered Equipment Billing";
                          capacityDefaultOption.selected = true;
                          capacityDefaultOption.disabled = true;
                          capacityDefaultOption.hidden = true;
                          capacitySelect.appendChild(capacityDefaultOption);
                          capacitySelect.disabled = response.capacities.length > 0 ? false : true;

                          if (response.capacities.length > 0) {
                            response.capacities.forEach(capacity => {
                              let option = document.createElement("option");
                              option.value = capacity;
                              option.text = capacity;
                              capacitySelect.appendChild(option);
                            });

                          }
                          
                        }
                    };
                    // Send selected section as parameter
                    xhr.send(`section=${encodeURIComponent(selectedSection)}`);
                  }

                  updateCapacities();
                

            
                  capacitySelect.addEventListener("change", function () {
                    let selectedCapacity = capacitySelect.value;

                    let xhr = new XMLHttpRequest();
                    xhr.open("POST", "./json_response/fee.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function () {
                      if (xhr.readyState === 4 && xhr.status === 200) {
                        let response = JSON.parse(xhr.responseText);
  
                          // Store the initial fee value
                          originalFeeValue = parseFloat(response.fee);
  
                          // Set the fee input field value
                          feeInputField.value = parseFloat(response.fee).toFixed(2);
                          billingIdHiddenInput.value = response.billing_id;

                          $quantity = document.getElementById(`quantity-${counter}`);
                          $quantity.removeAttribute('readonly');
                        }
                      };
                      // Send selected section as parameter
                    xhr.send(`capacity=${encodeURIComponent(selectedCapacity)}`);
                  });
                
                }
                

                // Quantity and Power Rating Container
                let quantityPowerContainer = createContainerDiv('d-md-flex align-items-center justify-content-center p-0');
                itemContent.appendChild(quantityPowerContainer);

                //Quantity Field
                let quantityContainer = createContainerDiv('col col-md-6 p-0 form-group mb-1 flex-md-grow-1');
                quantityPowerContainer.appendChild(quantityContainer);

                let quantityLabel = createLabel('Quantity');
                quantityContainer.appendChild(quantityLabel);

                let quantityInputField = createInputField('number', `quantity-${counter}`, `quantity[]`, true);
                quantityContainer.append(quantityInputField);

                // Power Rating
                let powerRatingContainer = createContainerDiv('col col-md-6 p-0 form-group mb-1 flex-md-grow-1');
                quantityPowerContainer.appendChild(powerRatingContainer);

                let powerRatingLabel = createLabel('Power Rating');
                powerRatingContainer.appendChild(powerRatingLabel);

                let powerRatingInputField = createInputField('number', `power-rating-${counter}`, `power_rating[]`, false);
                powerRatingContainer.appendChild(powerRatingInputField);

                // Item Fee
                let feeContainer = createContainerDiv('col col-12 p-0 form-group mb-1');
                itemContent.appendChild(feeContainer);

                let feeLabel = createLabel('Fee');
                feeContainer.appendChild(feeLabel);

                let feeInputField = createInputField('number', `fee-${counter}`, `fee[]`);
                feeContainer.appendChild(feeInputField);

                // Create and append hidden input elements with unique identifiers
                itemContent.appendChild(createHiddenInput("item_id[]", `item-id-${counter}`, true));

                let billingIdHiddenInput = createHiddenInput("billing_id[]", `billing-id-${counter}`, true)
                itemContent.appendChild(billingIdHiddenInput);

                let originalFeeValue;

                quantityInputField.addEventListener("input", function() {
                  calculateTotalFee();
                });


                // Function to calculate total fee
                function calculateTotalFee() {
                    let quantity = parseFloat(quantityInputField.value);
                    let fee = originalFeeValue.toFixed(2);
                    if (isNaN(quantity) || isNaN(fee) || quantity === 0) {
                        // If either quantity or fee is not a number, reset fee input field
                        feeInputField.value = originalFeeValue.toFixed(2);
                    } else {
                        // Calculate total fee and update fee input field
                        let totalFee = quantity * fee;
                        feeInputField.value = totalFee.toFixed(2); // assuming you want to keep it as a float with 2 decimal places
                    }
                }

                // Update input field values with unique identifiers
                document.getElementById(`item-id-${counter}`).value = itemDetails.item_id;

                // Update the displayed count of added items
                updateItemCount(counter);

                deleteItemButton.classList.remove('d-none');
                deleteItemButton.classList.add('ml-3');
                // Close the modal
                let modal = bootstrap.Modal.getInstance(wrapper);
                modal.hide();
            }
        };
        item.send();
    });
  }

  // If delete button is available, add event listener to it
  if (deleteItemButton) {
    deleteItemButton.addEventListener("click", function () {
        // Remove the last added item field
        let lastItemTitle = document.getElementById(`item-title-${counter}`);
        let lastItem = document.getElementById(`item-name-${counter}`);
        let lastCategory = document.getElementById(`category-name-${counter}`);
        let lastSection = document.getElementById(`section-${counter}`);
        let lastCapacity = document.getElementById(`capacity-${counter}`);
        let lastQuantity = document.getElementById(`quantity-${counter}`);
        let lastPowerRating = document.getElementById(`power-rating-${counter}`);
        let lastFee = document.getElementById(`fee-${counter}`);
        if (lastItem && lastCategory && lastSection && lastCapacity && lastQuantity && lastPowerRating && lastFee) {
          lastItemTitle.parentElement.remove();
          lastItem.parentElement.remove(); // Remove the container div
          lastCategory.parentElement.remove(); // Remove the container div
          lastSection.parentElement.remove(); // Remove the container div
          lastCapacity.parentElement.remove(); // Remove the container div
          lastQuantity.parentElement.remove(); // Remove the container div
          lastPowerRating.parentElement.remove(); // Remove the container div
          lastFee.parentElement.remove(); // Remove the container div
          counter--;

          if (counter === 0) {
            deleteItemButton.classList.add('d-none');
          }

          // Update the displayed count of added items
          updateItemCount(counter);
      }
    });
  }

    // Function to update the count of added items
  function updateItemCount(count) {
    if (totalItem) {
        totalItem.innerHTML = count;
    }
  }
    
});

function billing(category, section1, section2, section3, capacity) {
  document.addEventListener("DOMContentLoaded", () => {
    let Category = document.getElementById(category);
    let Section1 = document.getElementById(section1);
    let Section2 = document.getElementById(section2);
    let Section3 = document.getElementById(section3);

    if (capacity) {
      var Capacity = document.getElementById(capacity);
    }
 
  
    if (Category) {
      Category.addEventListener("change", () => {
        let selectedOption = Category.options[Category.selectedIndex];
        let CategoryText = selectedOption.innerText.trim();
    
  
        if (CategoryText === 'Electrical') {
          Section1.classList.replace('d-none', 'd-flex');
          Section1.querySelector('select').removeAttribute("disabled");
          Section1.querySelector('select').setAttribute('required', 'required');
    
          Section2.classList.replace('d-flex', 'd-none');
          Section2.querySelector('select').removeAttribute("required");
          Section2.querySelector('select').setAttribute('disabled', 'disabled');
    
          Section3.classList.replace('d-flex', 'd-none');
          Section3.querySelector('select').removeAttribute("required");
          Section3.querySelector('select').setAttribute('disabled', 'disabled');

          Capacity.classList.replace('d-none', 'd-block');
          Capacity.querySelector('input').removeAttribute("disabled");
          Capacity.querySelector('input').setAttribute('required', 'required');

        } else if (CategoryText === 'Mechanical') {
          Section2.classList.replace('d-none', 'd-flex');
          Section2.querySelector('select').removeAttribute("disabled");
          Section2.querySelector('select').setAttribute('required', 'required');
    
          Section1.classList.replace('d-flex', 'd-none');
          Section1.querySelector('select').removeAttribute("required");
          Section1.querySelector('select').setAttribute('disabled', 'disabled');
    
          Section3.classList.replace('d-flex', 'd-none');
          Section3.querySelector('select').removeAttribute("required");
          Section3.querySelector('select').setAttribute('disabled', 'disabled');

          Capacity.classList.replace('d-none', 'd-block');
          Capacity.querySelector('input').removeAttribute("disabled");
          Capacity.querySelector('input').setAttribute('required', 'required');

        } else if (CategoryText === 'Electronics') {
          Section3.classList.replace('d-none', 'd-flex');
          Section3.querySelector('select').removeAttribute("disabled");
          Section3.querySelector('select').setAttribute('required', 'required');
    
          Section1.classList.replace('d-flex', 'd-none');
          Section1.querySelector('select').removeAttribute("required");
          Section1.querySelector('select').setAttribute('disabled', 'disabled');
    
          Section2.classList.replace('d-flex', 'd-none');
          Section2.querySelector('select').removeAttribute("required");
          Section2.querySelector('select').setAttribute('disabled', 'disabled');

          Capacity.classList.replace('d-block', 'd-none');
          Capacity.querySelector('input').removeAttribute('required');
          Capacity.querySelector('input').setAttribute('disabled', 'disabled');
        }
      });
    }
    
  });
}

billing('category-id', 'electrical-section', 'mechanical-section', 'electronics-section', 'capacity-container');



function inspector(inspectorContainers, selectInspector) {
  document.addEventListener("DOMContentLoaded", function () {
    let wrapper = document.getElementById("inspector-list");
    let selectInspectorButtons = document.querySelectorAll(selectInspector);
    let deleteInspectorButton = document.getElementById("delete-inspector");
    let totalInspector = document.getElementById("total-inspector");
    let counter = parseInt(totalInspector.innerText) || 0; // Initialize counter
  
    // Inside the loop where you're adding event listeners for select inspector buttons
    for (let i = 0; i < selectInspectorButtons.length; i++) {
      selectInspectorButtons[i].addEventListener("click", function (event) {
          event.preventDefault();
  
          let inspectorId = this.getAttribute("data-inspector-id");
  
          let url = `./../json_response/inspector.php?inspector_id=${inspectorId}`;

          if (inspectorContainers === 'inspector-certificate-container') {
            let url = `./json_response/inspector.php?inspector_id=${inspectorId}`;
          }
          // Make an AJAX request to fetch the inspector details
          let inspector = new XMLHttpRequest();
          inspector.open("GET", url, true);
          inspector.onreadystatechange = function () {
              if (inspector.readyState === 4 && inspector.status === 200) {
                  let inspectorDetails = JSON.parse(inspector.responseText);
  
                  // Increment counter for each click
                  counter++;
  
                  //Inspector Container
                  let inspectorContainer = document.getElementById(inspectorContainers);

                  //Inspector Content Container
                  let inspectorContent = createContainerDiv('shadow bg-white rounded p-3 mb-2', `inspector-content-${counter}`);
                  inspectorContainer.appendChild(inspectorContent);
  
                  let inspectorTitle = createTitle(`Inspector ${counter}`, `inspector-title-${counter}`);
                  inspectorContent.appendChild(inspectorTitle);
  
                  // Create and append inspector name container div
                  let inspectorNameContainer = createContainerDiv('col col-12 p-0 form-group mb-1');
                  inspectorContent.appendChild(inspectorNameContainer);
  
                  // Create and append inspector name label
                  let inspectorNameLabel = createLabel(`Inspector Name`);
                  inspectorNameContainer.appendChild(inspectorNameLabel);
  
                  let inspectorNameInputField = createInputField('text', `inspector-name-${counter}`, `inspector_name[]`);
                  inspectorNameContainer.appendChild(inspectorNameInputField);
                  inspectorNameInputField.value = inspectorDetails.inspector_name;

              
                  // Update input field values with unique identifiers
                  inspectorContent.appendChild(createHiddenInput("inspector_id[]", `inspector-id-${counter}`, true));
                  document.getElementById(`inspector-id-${counter}`).value = inspectorDetails.inspector_id;
  
                  if (inspectorContainers === 'inspector-certificate-container') {
                    // Inspector Name ABBR
                    inspector_abbr = createHiddenInput("inspector_abbr[]", `inspector-abbr-${counter}`, true)
                    inspectorContent.appendChild(inspector_abbr);
                    inspector_abbr.value = inspectorDetails.inspector_abbr

                    // Inspector Lastname
                    inspectorLastname = createHiddenInput("inspector_lastname[]", `inspector-lastname-${counter}`, true)
                    inspectorContent.appendChild(inspectorLastname);
                    inspectorLastname.value = inspectorDetails.inspector_lastname

                    // Category Field
                    let categoryContainer = createContainerDiv('form-group flex-column flex-md-grow-1');
                    inspectorContent.appendChild(categoryContainer);

                    let categoryLabel = createLabel(`Category`);
                    categoryContainer.appendChild(categoryLabel);

                    let categoryFieldContainer = createContainerDiv('d-flex align-items-center justify-content-center select-container');
                    categoryContainer.appendChild(categoryFieldContainer);

                    let categorySelect = document.createElement('select');
                    categorySelect.classList.add('form-control');
                    categorySelect.classList.add('form-select');
                    categorySelect.id = `category-${counter}`;
                    categorySelect.name = 'category[]';
                    categoryFieldContainer.appendChild(categorySelect);

                    function createOption(value = "", text = "Select", selected = false, disabled = false, hidden = false) {
                      let Option = document.createElement("option");
                      Option.value = value;
                      Option.text = text;
                      Option.selected = selected;
                      Option.disabled = disabled;
                      Option.hidden = hidden;
                      categorySelect.appendChild(Option);
                    }

                    let defaultOption = createOption("", "Select", selected = true, disabled = true, hidden = true);
                    let Option1 = createOption('Locational/Zoning of land Use', 'Locational/Zoning of land Use');
                    let option2 = createOption('Line and Grade (Geodetic)', 'Line and Grade (Geodetic)');
                    let option3 = createOption('Architectural', 'Architectural');
                    let option4 = createOption('Civil/ Structural', 'Civil/ Structural');
                    let option5 = createOption('Electrical', 'Electrical');
                    let option6 = createOption('Mechanical', 'Mechanical');
                    let option7 = createOption('Sanitary', 'Sanitary');
                    let option8 = createOption('Plumbing', 'Plumbing');
                    let option9 = createOption('Electronics', 'Electronics');
                    let option10 = createOption('Interior', 'Interior');
                    let option11 = createOption('Accessibility', 'Accessibility');
                    let option12 = createOption('Fire', 'Fire');
                    let option13 = createOption('Others (Specify)', 'Others (Specify)');

                    // Date Signed
                    let dateSignedContainer = createContainerDiv('col col-12 p-0 form-group mb-1');
                    inspectorContent.appendChild(dateSignedContainer);

                    let dateSignedLabel = createLabel('Date Signed');
                    dateSignedContainer.appendChild(dateSignedLabel);

                    let dateSignedInputField = createInputField('date', `date-signed-${counter}`, `date_signed[]`, false);
                    dateSignedContainer.appendChild(dateSignedInputField);

                    let timeInOutContainer = createContainerDiv('d-md-flex align-items-center justify-content-center p-0');
                    inspectorContent.appendChild(timeInOutContainer);
                    
                     // Time In
                     let timeInContainer = createContainerDiv('col col-md-6 p-0 form-group mb-1 flex-md-grow-1');
                     timeInOutContainer.appendChild(timeInContainer);
 
                     let timeInLabel = createLabel('Time In');
                     timeInContainer.appendChild(timeInLabel);
 
                     let timeInInputField = createInputField('time', `time-in-${counter}`, `time_in[]`, false);
                     timeInContainer.appendChild(timeInInputField);

                     // Time Out
                     let timeOutContainer = createContainerDiv('col col-md-6 p-0 form-group mb-1 flex-md-grow-1');
                     timeInOutContainer.appendChild(timeOutContainer);
 
                     let timeOutLabel = createLabel('Time Out');
                     timeOutContainer.appendChild(timeOutLabel);
 
                     let timeOutInputField = createInputField('time', `time-out-${counter}`, `time_out[]`, false);
                     timeOutContainer.appendChild(timeOutInputField);
                  }
                  // Update the displayed count of added inspectors
                  updateInspectorCount(counter);
  
                  deleteInspectorButton.classList.remove('d-none');
                  deleteInspectorButton.classList.add('ml-3');

                  // Close the modal
                  let modal = bootstrap.Modal.getInstance(wrapper);
                  modal.hide();
              }
          };
          inspector.send();
      });
    }
  
    // If delete button is available, add event listener to it
    if (deleteInspectorButton) {
      deleteInspectorButton.addEventListener("click", function () {
        // Remove the last added inspector field
        let lastInspectorTitle = document.getElementById(`inspector-title-${counter}`);
        let lastInspector = document.getElementById(`inspector-name-${counter}`);
          
        if (lastInspector) {
          lastInspectorTitle.parentElement.remove();
            counter--;

            if (counter === 0) {
              deleteInspectorButton.classList.add('d-none');
            }
          // Update the displayed count of added inspector
          updateInspectorCount(counter);
        }
      });
    }
  
      // Function to update the count of added inspector
    function updateInspectorCount(count) {
      if (totalInspector) {
          totalInspector.innerHTML = count;
      }
    }
      
  });
  
}
inspector('inspector-certificate-container', '.select-certificate-inspector');
inspector('inspector-container', '.select-inspector');

document.addEventListener("DOMContentLoaded", function () {
  let wrapper = document.getElementById("violation-list");
  let selectViolationButtons = document.querySelectorAll(".select-violation");
  let deleteViolationButton = document.getElementById("delete-violation");
  let totalViolation = document.getElementById("total-violation");
  let counter = parseInt(totalViolation.innerText) || 0; // Initialize counter

  // Inside the loop where you're adding event listeners for select violation buttons
  for (let i = 0; i < selectViolationButtons.length; i++) {
    selectViolationButtons[i].addEventListener("click", function (event) {
        event.preventDefault();

        let violationId = this.getAttribute("data-violation-id");

        // Make an AJAX request to fetch the violation details
        let violation = new XMLHttpRequest();
        violation.open("GET", `./json_response/violation.php?violation_id=${violationId}`, true);
        violation.onreadystatechange = function () {
            if (violation.readyState === 4 && violation.status === 200) {
                let violationDetails = JSON.parse(violation.responseText);

                // Increment counter for each click
                counter++;

                //Violation Container
                let violationContainer = document.getElementById('violation-container');

                //Violation Content Container
                let violationContent = createContainerDiv('shadow bg-white rounded p-3 mb-2', `violation-content-${counter}`);
                violationContainer.appendChild(violationContent);

                let violationTitle = createTitle(`Violation ${counter}`, `violation-title-${counter}`);
                violationContent.appendChild(violationTitle);

                // Create and append violation name container div
                let descriptionContainer = createContainerDiv('col col-12 p-0 form-group mb-1');
                violationContent.appendChild(descriptionContainer);

                // Create and append violation name label
                let descriptionLabel = createLabel(`Description`);
                descriptionContainer.appendChild(descriptionLabel);

                let descriptionInputField = createInputField('text', `description-${counter}`, `description[]`);
                descriptionContainer.appendChild(descriptionInputField);
                descriptionInputField.value = violationDetails.description;

                
                // Update input field values with unique identifiers
                violationContent.appendChild(createHiddenInput("violation_id[]", `violation-id-${counter}`, true));
                document.getElementById(`violation-id-${counter}`).value = violationDetails.violation_id;

                // Update the displayed count of added violations
                updateViolationCount(counter);

                deleteViolationButton.classList.remove('d-none');
                deleteViolationButton.classList.add('ml-3');

                // Close the modal
                let modal = bootstrap.Modal.getInstance(wrapper);
                modal.hide();
            }
        };
        violation.send();
    });
  }

  // If delete button is available, add event listener to it
  if (deleteViolationButton) {
    deleteViolationButton.addEventListener("click", function () {
        // Remove the last added violation field
        let lastViolationTitle = document.getElementById(`violation-title-${counter}`);
        let lastViolation = document.getElementById(`description-${counter}`);
        
        if (lastViolation) {
          lastViolationTitle.parentElement.remove();
            counter--;

            if (counter === 0) {
              deleteViolationButton.classList.add('d-none');
            }
          // Update the displayed count of added violation
          updateViolationCount(counter);
      }
    });
  }

    // Function to update the count of added inspector
  function updateViolationCount(count) {
    if (totalViolation) {
        totalViolation.innerHTML = count;
    }
  }
    
});


function createContainerDiv(className, id = "") {
  const div = document.createElement('div');
  div.className = className;
  div.id = id
  return div;
}

function createLabel(text) {
  const label = document.createElement('label');
  label.textContent = text;
  label.innerHTML += ' <strong style="color:red;">*</strong>';
  return label;
}

function createInputField(type, id, name, readOnly = true, required = true) {
  const input = document.createElement('input');
  input.type = type;
  input.name = name;
  input.id = id;
  input.className = 'form-control p-4';
  input.readOnly = readOnly;
  input.required = required;

  if (type === "number") {
    if (name === "quantity[]") {
      input.value = 1;
      input.step = 1;
    } else if (name === "fee[]") {
      input.value = parseFloat(0.00).toFixed(2);
      input.step = 0.01;
    } else if (name === "power_rating[]") {
      input.value = parseFloat(0.00).toFixed(2);
      input.step = 0.01;

    }
  
  } else if (type === "date") {
    let currentDate = new Date().toISOString().split('T')[0];
    input.max = currentDate;

  }
  return input;
}

function createTitle(itemTitle, id) {
  const title = document.createElement("a");
  title.innerHTML = itemTitle;
  title.id = id;
  title.className = "text text-decoration-none"; // class assignment
  title.style.cursor = "pointer";
  title.style.textDecorationStyle = "none";
  title.style.fontWeight = 700;
  return title;
}

function createHiddenInput(name, id, required = false) {
  const input = document.createElement('input');
  input.type = 'hidden';
  input.name = name;
  input.id = id;
  if (required) {
    input.required = true;
  }
  return input;
}

