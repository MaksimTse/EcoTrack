const token = '2701d75dedf0221c9c00d132963e523aa8d1061d';
const locationIQToken = 'pk.5ab5cc1654c32f44a88470d21764fc02';
const mapboxToken = 'sk.eyJ1Ijoib3Zlcm93bCIsImEiOiJjbTd5dXNydm4wYm5iMmlyNnQzM29zODhyIn0.a0-LkE_EuopN6DFKM9UCyA';
const map = L.map('map').setView([59.437, 24.753], 6);

L.tileLayer(`https://api.mapbox.com/styles/v1/mapbox/streets-v11/tiles/{z}/{x}/{y}?access_token=${mapboxToken}&language=et`, {
    attribution: '&copy; Mapbox',
    tileSize: 512,
    zoomOffset: -1
}).addTo(map);

let currentMarker = null;

const customIcon = L.icon({
    iconUrl: './assets/gps.png',
    iconSize: [38, 38],
    iconAnchor: [19, 38],
    popupAnchor: [0, -35]
});

async function getCountryName(lat, lon) {
    const url = `https://eu1.locationiq.com/v1/reverse?key=${locationIQToken}&lat=${lat}&lon=${lon}&format=json`;
    const res = await fetch(url);
    const data = await res.json();
    return data.address.country;
}

async function getAllStations() {
    const res = await fetch(`https://api.waqi.info/map/bounds/?latlng=-90,-180,90,180&token=${token}`);
    const data = await res.json();
    return data.data.filter(s => s.aqi !== '-' && !isNaN(s.aqi));
}

map.on('click', async function (e) {
    const lat = e.latlng.lat;
    const lon = e.latlng.lng;
    const infoBox = document.getElementById('info');
    infoBox.innerHTML = '⏳ Laen andmeid...';

    if (currentMarker) {
        map.removeLayer(currentMarker);
    }

    try {
        const locationRes = await fetch(`https://api.waqi.info/feed/geo:${lat};${lon}/?token=${token}`);
        const locationData = await locationRes.json();

        if (locationData.status === 'ok') {
            const aqi = parseInt(locationData.data.aqi);
            const location = locationData.data.city?.name || 'Tundmatu asukoht';
            const dominant = locationData.data.dominentpol || 'Puudub info';
            const updated = locationData.data.time?.s || 'Teadmata aeg';
            const country = await getCountryName(lat, lon);

            let quality = '';
            if (aqi <= 50) quality = 'Väga hea';
            else if (aqi <= 100) quality = 'Rahuldav';
            else if (aqi <= 150) quality = 'Ebatervislik tundlikule rühmale';
            else if (aqi <= 200) quality = 'Ebatervislik';
            else if (aqi <= 300) quality = 'Väga ebatervislik';
            else quality = 'Ohtlik';

            const stations = await getAllStations();
            const sortedByAQI = stations.slice().sort((a, b) => parseInt(a.aqi) - parseInt(b.aqi));

            const cleaner = sortedByAQI.filter(s => parseInt(s.aqi) < aqi).slice(-5).reverse();
            const dirtier = sortedByAQI.filter(s => parseInt(s.aqi) > aqi).slice(0, 5);
            const rank = sortedByAQI.findIndex(s => parseInt(s.aqi) === aqi) + 1;

            const listHTML = arr => arr.map(s => `<p>${s.station.name} – AQI ${s.aqi}</p>`).join('');
            const popupContent = `
                    <div style="text-align: center; font-size: 16px;">
                        <b>📍 ${location}</b><br>
                        🌫️ AQI: <span style="color: ${aqi > 100 ? 'red' : 'green'}; font-weight: bold;">${aqi}</span>
                    </div>
                `;

            infoBox.innerHTML = `
                    <strong>📍 Asukoht:</strong> ${location} (${country})<br>
                    <strong>🌫️ AQI:</strong> ${aqi} (${quality})<br>
                    <strong>💨 Peamine saasteallikas:</strong> ${dominant}<br>
                    <strong>📅 Viimane uuendus:</strong> ${updated}<br><br>

                    <strong>📊 AQI reiting maailmas:</strong> ${rank}/${sortedByAQI.length}<br><br>
                    <strong>⬇️ 5 puhtamat kohta AQI järgi:</strong>${listHTML(cleaner)}<br>
                    <strong>🎯 Valitud punkt:</strong>${location} (${country}) – AQI ${aqi}<br><br>
                    <strong>⬆️ 5 rohkem saastatud kohta AQI järgi:</strong>${listHTML(dirtier)}
                `;

            currentMarker = L.marker([lat, lon], { icon: customIcon }).addTo(map);
            currentMarker.bindPopup(popupContent).openPopup();
        } else {
            infoBox.innerHTML = '⚠️ Andmete laadimine ebaõnnestus.';
        }
    } catch (error) {
        console.error(error);
        infoBox.innerHTML = '❌ Viga andmete laadimisel.';
    }
});