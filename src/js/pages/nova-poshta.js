const API_KEY = '6baff66bad5bd1ed6daf6b6bd121da04';
const API_URL = 'https://api.novaposhta.ua/v2.0/json/';

class NovaPoshtaAPI {
    constructor() {
        this.citiesCache = new Map();
        this.warehousesCache = new Map();
    }

    // Function to clean city name
    cleanCityName(cityName) {
        // Remove everything after comma
        let cleanName = cityName.split(',')[0];
        // Remove "м." at the beginning
        cleanName = cleanName.replace(/^м\.\s*/i, '');
        // Remove "смт." at the beginning
        cleanName = cleanName.replace(/^смт\.\s*/i, '');
        // Remove "с." at the beginning
        cleanName = cleanName.replace(/^с\.\s*/i, '');
        return cleanName.trim();
    }

    async getCities(searchString) {
        try {
            const response = await fetch(API_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    apiKey: API_KEY,
                    modelName: 'Address',
                    calledMethod: 'searchSettlements',
                    methodProperties: {
                        CityName: searchString,
                        Limit: 20
                    }
                })
            });

            const data = await response.json();
            
            if (data.success) {
                const cities = data.data[0].Addresses.map(city => ({
                    name: city.Present,
                    ref: city.Ref,
                    type: city.SettlementTypeDescription,
                    mainDescription: city.MainDescription
                }));
                
                // Cache results
                this.citiesCache.set(searchString, cities);
                return cities;
            }
            
            return [];
        } catch (error) {
            console.error('Error fetching cities:', error);
            return [];
        }
    }

    async getWarehouses(cityRef, cityName, searchString = "") {
        try {
            // Check cache
            const cacheKey = `${cityRef}_${searchString}`;
            if (this.warehousesCache.has(cacheKey)) {
                return this.warehousesCache.get(cacheKey);
            }

            console.log('Getting warehouses for city:', cityName, 'search:', searchString);

            const response = await fetch(API_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    apiKey: API_KEY,
                    modelName: 'AddressGeneral',
                    calledMethod: 'getWarehouses',
                    methodProperties: {
                        FindByString: searchString,
                        CityName: cityName,
                        Page: "1",
                        Limit: "50",
                        Language: "UA"
                    }
                })
            });

            const data = await response.json();
            console.log('Warehouses API response:', data);
            
            if (data.success) {
                const warehouses = data.data.map(warehouse => ({
                    name: warehouse.Description,
                    ref: warehouse.Ref,
                    number: warehouse.Number
                }));
                
                // Cache results
                this.warehousesCache.set(cacheKey, warehouses);
                return warehouses;
            }
            
            return [];
        } catch (error) {
            console.error('Error fetching warehouses:', error);
            return [];
        }
    }
}

// Create global API instance
window.novaPoshtaAPI = new NovaPoshtaAPI();

// Function to create and manage dropdown list
function createDropdown(container, items, onSelect) {
    // Remove existing dropdown if it exists
    const existingDropdown = container.querySelector('.np-dropdown');
    if (existingDropdown) {
        existingDropdown.remove();
    }

    if (!items || !items.length) return;

    const dropdown = document.createElement('div');
    dropdown.className = 'np-dropdown';
    
    items.forEach(item => {
        const option = document.createElement('div');
        option.className = 'np-dropdown-item';
        option.textContent = item.name;
        option.addEventListener('click', (e) => {
            console.log('Item selected:', item);
            e.preventDefault();
            e.stopPropagation();
            onSelect(item);
            dropdown.remove();
            // Find input inside container and remove focus
            // const input = container.querySelector('input');
            // if (input) {
            //     setTimeout
            //     input.blur();
            // }
        });
        dropdown.appendChild(option);
    });

    container.appendChild(dropdown);
}

// Add styles for dropdown
const style = document.createElement('style');
style.textContent = `
    .np-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .np-dropdown-item {
        padding: 8px 12px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .np-dropdown-item:hover {
        background-color: #f5f5f5;
    }

    .input-container {
        position: relative;
    }
`;
document.head.appendChild(style);

// Export functions for use in other files
window.createNovaPoshtaDropdown = createDropdown; 