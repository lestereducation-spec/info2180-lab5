const controls = document.querySelector('#controls');
const country = document.querySelector('#country');
const lookupButton = document.querySelector('#lookup');
const citiesButton = document.querySelector('#lookup-cities');
const result = document.querySelector('#result');

async function lookup(cities = false) {
    const query = new URLSearchParams({ country: country.value.trim() });

    if (cities) {
        query.set('lookup', 'cities');
    }

    lookupButton.disabled = true;
    citiesButton.disabled = true;
    result.textContent = 'Loading…';

    try {
        const response = await fetch('world.php?' + query.toString());

        if (!response.ok) {
            throw new Error('Lookup failed');
        }

        result.innerHTML = await response.text();
    } catch (error) {
        result.textContent = 'Unable to complete the lookup. Please try again.';
    } finally {
        lookupButton.disabled = false;
        citiesButton.disabled = false;
    }
}

controls.addEventListener('submit', (event) => {
    event.preventDefault();
    if (!lookupButton.disabled) {
        lookup();
    }
});

citiesButton.addEventListener('click', () => lookup(true));
