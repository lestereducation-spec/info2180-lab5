const controls = document.querySelector('#controls');
const country = document.querySelector('#country');
const result = document.querySelector('#result');

async function lookup() {
    const query = new URLSearchParams({ country: country.value.trim() });
    result.textContent = 'Loading…';

    try {
        const response = await fetch('world.php?' + query.toString());

        if (!response.ok) {
            throw new Error('Lookup failed');
        }

        result.innerHTML = await response.text();
    } catch (error) {
        result.textContent = 'Unable to complete the lookup. Please try again.';
    }
}

controls.addEventListener('submit', (event) => {
    event.preventDefault();
    lookup();
});
