</main>

<footer class="footer">
    <div class="container footer-grid">
        <div class="footer-col" style="padding-right: 1rem;">
            <h3 class="footer-brand"><?= APP_NAME ?></h3>
            <p>Portal web para <strong>descargar libros gratis</strong> de forma legal. Libros en dominio público o bajo licencias abiertas.</p>
            <ul class="footer-links" style="display:flex; gap:1rem; flex-wrap:wrap; margin-top: 0.5rem;">
                <li><a href="#">Conócenos</a></li>
                <li><a href="#">Términos y Condiciones</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h3 class="footer-title">DESCUBRIR</h3>
            <div class="footer-2-cols">
                <ul class="footer-links">
                    <li><a href="<?= BASE_URL ?>/catalogo.php">Catálogo completo</a></li>
                    <li><a href="<?= BASE_URL ?>/catalogo.php">Buscar por categoría</a></li>
                </ul>
                <ul class="footer-links">
                    <li><a href="<?= BASE_URL ?>/catalogo.php?sort=downloads">Más descargados</a></li>
                    <li><a href="<?= BASE_URL ?>/catalogo.php?sort=new">Últimos añadidos</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-col">
            <h3 class="footer-title">CONTACTO</h3>
            <a href="#" class="telegram-link">
                <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.96-.64-.34-1 .22-1.58.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.33-.01-.98-.19-1.46-.35-.59-.19-1.05-.29-1-.61.02-.17.26-.34.71-.53 2.78-1.21 4.64-1.98 5.58-2.37 2.65-1.1 3.2-1.3 3.56-1.3.08 0 .25.02.34.1.07.06.09.15.1.24.01.07.01.16 0 .24z"/>
                </svg>
                Canal de Telegram
            </a>
            <p class="footer-email" style="margin-top:0.25rem;">hola@bibliodigital.com</p>
            
            <div class="footer-socials" style="margin-top: 0.5rem; margin-bottom: 0;">
                <a href="#" class="social-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"/></svg>
                </a>
                <a href="#" class="social-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/></svg>
                </a>
                <a href="#" class="social-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                </a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> <?= APP_NAME ?> — Biblioteca Digital. Tecnología Web II.</p>
    </div>
</footer>

<script src="<?= BASE_URL ?>/js/app.js?v=<?= filemtime(__DIR__ . '/../js/app.js') ?>"></script>
<?php if (isset($extraScripts))
    echo $extraScripts; ?>

<!-- Weather Widget -->
<button class="weather-fab" id="weatherFab" aria-label="Ver clima">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z"/>
    </svg>
</button>

<div class="weather-panel" id="weatherPanel">
    <div class="weather-loading" id="weatherLoading">
        <div class="weather-loading-spinner"></div>
        Cargando clima...
    </div>
    <div id="weatherContent" style="display:none;"></div>
    <div class="weather-error" id="weatherError" style="display:none;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        <span>No se pudo obtener el clima</span>
    </div>
</div>

<script>
(function() {
    const WEATHER_API_KEY = 'f6264551a0a04578a8dbba120f56a451';
    const DEFAULT_LAT = -16.5;
    const DEFAULT_LON = -68.15;

    const fab = document.getElementById('weatherFab');
    const panel = document.getElementById('weatherPanel');
    const loading = document.getElementById('weatherLoading');
    const content = document.getElementById('weatherContent');
    const error = document.getElementById('weatherError');

    let weatherLoaded = false;
    let isOpen = false;

    fab.addEventListener('click', () => {
        isOpen = !isOpen;
        fab.classList.toggle('active', isOpen);
        panel.classList.toggle('open', isOpen);

        if (!weatherLoaded) {
            weatherLoaded = true;
            fetchWeather();
        }
    });

    // Close when clicking outside
    document.addEventListener('click', (e) => {
        if (isOpen && !panel.contains(e.target) && !fab.contains(e.target)) {
            isOpen = false;
            fab.classList.remove('active');
            panel.classList.remove('open');
        }
    });

    function fetchWeather() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (pos) => loadWeather(pos.coords.latitude, pos.coords.longitude),
                () => loadWeather(DEFAULT_LAT, DEFAULT_LON),
                { timeout: 5000 }
            );
        } else {
            loadWeather(DEFAULT_LAT, DEFAULT_LON);
        }
    }

    async function loadWeather(lat, lon) {
        try {
            const url = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&units=metric&lang=es&appid=${WEATHER_API_KEY}`;
            const res = await fetch(url);
            if (!res.ok) throw new Error('API error');
            const data = await res.json();
            renderWeather(data);
        } catch (err) {
            loading.style.display = 'none';
            error.style.display = 'flex';
        }
    }

    function renderWeather(data) {
        const temp = Math.round(data.main.temp);
        const feelsLike = Math.round(data.main.feels_like);
        const humidity = data.main.humidity;
        const wind = (data.wind.speed * 3.6).toFixed(1);
        const desc = data.weather[0].description;
        const icon = data.weather[0].icon;
        const city = data.name;

        content.innerHTML = `
            <div class="weather-panel-header">
                <div class="weather-city">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    ${city}
                </div>
                <div class="weather-main">
                    <div class="weather-icon-big">
                        <img src="https://openweathermap.org/img/wn/${icon}@2x.png" alt="${desc}">
                    </div>
                    <div class="weather-temp">${temp}<span>°C</span></div>
                </div>
                <div class="weather-desc">${desc}</div>
            </div>
            <div class="weather-panel-body">
                <div class="weather-details">
                    <div class="weather-detail-item">
                        <div class="weather-detail-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 14.76V3.5a2.5 2.5 0 0 0-5 0v11.26a4.5 4.5 0 1 0 5 0z"/></svg>
                        </div>
                        <div class="weather-detail-info">
                            <span class="weather-detail-value">${feelsLike}°C</span>
                            <span class="weather-detail-label">Sensación</span>
                        </div>
                    </div>
                    <div class="weather-detail-item">
                        <div class="weather-detail-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/></svg>
                        </div>
                        <div class="weather-detail-info">
                            <span class="weather-detail-value">${humidity}%</span>
                            <span class="weather-detail-label">Humedad</span>
                        </div>
                    </div>
                    <div class="weather-detail-item">
                        <div class="weather-detail-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9.59 4.59A2 2 0 1 1 11 8H2m10.59 11.41A2 2 0 1 0 14 16H2m15.73-8.27A2.5 2.5 0 1 1 19.5 12H2"/></svg>
                        </div>
                        <div class="weather-detail-info">
                            <span class="weather-detail-value">${wind} km/h</span>
                            <span class="weather-detail-label">Viento</span>
                        </div>
                    </div>
                    <div class="weather-detail-item">
                        <div class="weather-detail-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                        </div>
                        <div class="weather-detail-info">
                            <span class="weather-detail-value">${temp > 0 ? temp + '°' : '—'}</span>
                            <span class="weather-detail-label">Máxima</span>
                        </div>
                    </div>
                </div>
            </div>
        `;

        loading.style.display = 'none';
        content.style.display = 'block';
    }
})();
</script>

</body>

</html>