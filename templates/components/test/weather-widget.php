<?php
$city = htmlspecialchars($props['city'] ?? 'Paris');
?>
<script defer>
    if (!customElements.get("weather-widget")) {
        class WeatherWidget extends HTMLElement {
            connectedCallback() {
                this.city = this.getAttribute("city") || "Paris";
                this.apiUrl = `https://api.open-meteo.com/v1/forecast?latitude=48.8566&longitude=2.3522&current_weather=true`;

                // Si on veut d'autres villes → latitude/longitude dynamiques
                const coords = {
                    paris: { lat: 48.8566, lon: 2.3522 },
                    lyon: { lat: 45.7578, lon: 4.832 },
                    marseille: { lat: 43.2965, lon: 5.3698 },
                    toulouse: { lat: 43.6045, lon: 1.444 },
                    bordeaux: { lat: 44.8378, lon: -0.5792 },
                    lille: { lat: 50.6292, lon: 3.0573 }
                };
                const cityKey = this.city.toLowerCase();
                if (coords[cityKey]) {
                    const { lat, lon } = coords[cityKey];
                    this.apiUrl = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current_weather=true`;
                }

                this.fetchWeather();
                this.timer = setInterval(() => this.fetchWeather(), 10000);
            }

            disconnectedCallback() {
                clearInterval(this.timer);
            }

            async fetchWeather() {
                try {
                    const res = await fetch(this.apiUrl);
                    const data = await res.json();
                    const weather = data.current_weather;
                    this.render(weather);
                } catch (err) {
                    this.querySelector(".weather").innerHTML = `<p class="error">Erreur de chargement des données météo.</p>`;
                    console.error(err);
                }
            }

            render(weather) {
                if (!weather) return;
                const { temperature, windspeed, weathercode, time } = weather;
                const icons = {
                    0: "☀️",
                    1: "🌤️",
                    2: "⛅",
                    3: "☁️",
                    45: "🌫️",
                    48: "🌫️",
                    51: "🌦️",
                    61: "🌧️",
                    71: "❄️",
                    95: "⛈️",
                };
                const icon = icons[weathercode] || "❓";
                const date = new Date(time).toLocaleTimeString();

                this.querySelector(".weather").innerHTML = `
        <div class="current">
          <div class="icon">${icon}</div>
          <div class="temp">${temperature.toFixed(1)}°C</div>
        </div>
        <p class="info">Vent : ${windspeed} km/h</p>
        <p class="time">Dernière mise à jour : ${date}</p>
      `;
            }
        }

        customElements.define("weather-widget", WeatherWidget);
    }
</script>

<style>
    .weather-widget {
        font-family: sans-serif;
        border-radius: 12px;
        padding: 1rem;
        background: linear-gradient(145deg, #0099ff, #66ccff);
        color: #fff;
        width: 220px;
        text-align: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        transition: transform .2s ease;
    }
    .weather-widget:hover {
        transform: scale(1.03);
    }
    .weather-widget .city {
        font-weight: bold;
        font-size: 1.1rem;
        margin-bottom: .5rem;
    }
    .weather-widget .current {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .5rem;
        font-size: 1.4rem;
    }
    .weather-widget .temp {
        font-size: 1.6rem;
        font-weight: bold;
    }
    .weather-widget .info {
        margin: .4rem 0;
        font-size: .9rem;
    }
    .weather-widget .time {
        font-size: .8rem;
        opacity: .8;
    }
    .error {
        font-size: .9rem;
        color: #ffdddd;
    }
</style>

<div class="weather-widget">
    <div class="city"><?= $city ?></div>
    <div class="weather">Chargement...</div>
</div>
