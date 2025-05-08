<template>
  <div>
    <h2>Fetch Real-Time Weather</h2>
    <input
      v-model="city"
      placeholder="City name (e.g. paris)"
      title="Enter the city name to get real-time weather"
    />
    <button @click="fetchRealtime" title="Get current weather from Open-Meteo">Get</button>

    <div v-if="result">
      Current temperature: {{ result.temperature }}°C,
      Wind speed: {{ result.windspeed }} km/h
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

// Reactive variables for input and result
const city = ref('')
const result = ref(null)

// Function to call the backend and fetch real-time weather
const fetchRealtime = async () => {
  const res = await axios.get(`/api/weather/realtime/${city.value}`)
  result.value = res.data
}
</script>
