<template>
  <div>
    <h2>Add Weather Record</h2>
    <form @submit.prevent="submitForm">
      <input
        v-model="city"
        placeholder="City name (e.g. Paris)"
        title="Enter the name of the city"
      />
      <input
        v-model.number="temperature"
        placeholder="Temperature in °C"
        title="Enter the current temperature in Celsius"
      />
      <input
        v-model.number="windSpeed"
        placeholder="Wind speed in km/h"
        title="Enter the wind speed in kilometers per hour"
      />
      <button type="submit" title="Submit the weather data">Submit</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

// Reactive form fields
const city = ref('')
const temperature = ref(0)
const windSpeed = ref(0)

// Submit form data to the backend
const submitForm = async () => {
  await axios.post('/api/weather', {
    city: city.value,
    temperature: temperature.value,
    windSpeed: windSpeed.value,
    date: new Date().toISOString()
  })

  alert('Weather record successfully added!')

  // Clear input fields
  city.value = ''
  temperature.value = 0
  windSpeed.value = 0
}
</script>
