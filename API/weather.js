function fetchWeatherData(city) {
    const apiKey = 'c1a08848aa6a49da9dd140100241704';
    const apiUrl = `http://api.weatherapi.com/v1/current.json?key=${apiKey}&q=${city}&aqi=no`;

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
    console.log(data);  // Debug: log data to console
    const weather = data.current;
    const weatherHtml = `
        <h2>Weather in ${data.location.name}</h2>
        <p>Wind Speed: ${weather.wind_kph} km/h</p>
        <p>Wind Direction: ${weather.wind_degree} ° ${weather.wind_dir} </p>
        <p>Pressure: ${weather.pressure_mb} mb</p>
    `;
    document.getElementById('weatherDisplay').innerHTML = weatherHtml;
}
