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
});

