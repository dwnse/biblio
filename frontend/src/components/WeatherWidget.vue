<template>
  <div class="weather-widget" v-if="weather">
    <div class="weather-header">
      <span class="weather-city">{{ weather.name }}</span>
      <span class="weather-temp">{{ Math.round(weather.main.temp) }}°C</span>
    </div>
    <div class="weather-body">
      <img :src="weatherIconUrl" :alt="weather.weather[0].description" class="weather-icon" />
      <span class="weather-desc">{{ weather.weather[0].description }}</span>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const weather = ref(null)
const weatherIconUrl = ref('')
const API_KEY = 'f6264551a0a04578a8dbba120f56a451'
const CITY = 'Buenos Aires' // Puedes cambiar la ciudad si lo deseas

const fetchWeather = async () => {
  try {
    const response = await axios.get(
      `https://api.openweathermap.org/data/2.5/weather?q=${CITY}&appid=${API_KEY}&units=metric&lang=es`
    )
    weather.value = response.data
    weatherIconUrl.value = `https://openweathermap.org/img/wn/${weather.value.weather[0].icon}@2x.png`
  } catch (error) {
    weather.value = null
  }
}

onMounted(fetchWeather)
</script>

<style scoped>
.weather-widget {
  position: absolute;
  top: 2rem;
  right: 2rem;
  background: rgba(255,255,255,0.95);
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  padding: 1rem 1.5rem;
  min-width: 180px;
  z-index: 100;
  display: flex;
  flex-direction: column;
  align-items: center;
  font-family: 'Segoe UI', Arial, sans-serif;
}
.weather-header {
  display: flex;
  justify-content: space-between;
  width: 100%;
  font-weight: bold;
  font-size: 1.1rem;
  margin-bottom: 0.5rem;
}
.weather-body {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}
.weather-icon {
  width: 48px;
  height: 48px;
}
.weather-desc {
  text-transform: capitalize;
  font-size: 1rem;
}
</style>
