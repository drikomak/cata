document.addEventListener('DOMContentLoaded', function() {
fetch('pays.php')
        .then(response => response.json())
        .then(data => {
            const countrySelect = document.getElementById('countrySelect');
            data.forEach(pays => {
                let option = new Option(pays.country_name, pays.country_id); 
                countrySelect.add(option);
            });
        });

    document.getElementById('countrySelect').addEventListener('change', function() {
        const countryId = this.value;
        fetch(`villes.php?country_id=${countryId}`)
            .then(response => response.json())
            .then(data => {
                const citySelect = document.getElementById('citySelect');
                citySelect.innerHTML = '<option value="">Sélectionnez une ville</option>';
                data.forEach(ville => {
                    let option = new Option(ville.city_name, ville.city_name);
                    citySelect.add(option);
                });
            }); 
    });

    document.getElementById('citySelect').addEventListener('change', function() {
        const cityName = this.value;
        fetchWeatherData(cityName); 
    });
});

function fetchWeatherData(location) {
    const apiKey = 'c1a08848aa6a49da9dd140100241704';
    const apiUrl = `http://api.weatherapi.com/v1/current.json?key=${apiKey}&q=${location}&aqi=no`;
    fetch(apiUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => updateWeatherDisplay(data))
        .catch(error => console.error('Error fetching weather data:', error));
}

function updateWeatherDisplay(data) {
    console.log(data);  
    const weather = data.current;
    const weatherHtml = `
        <h2>Weather in ${data.location.name}</h2>
        <p>Temperature: ${weather.temp_c}°C / ${weather.temp_f}°F</p>
        <p>Wind Speed: ${weather.wind_kph} km/h / ${weather.wind_mph} mph</p>
        <p>Wind Direction: ${weather.wind_degree} - ${weather.wind_dir}</p>
        <p>Pressure: ${weather.pressure_mb} mb / ${weather.pressure_in} inHg</p>
        
    `;
    document.getElementById('weatherDisplay').innerHTML = weatherHtml;
}
