<template>
  <div>
    <h2>All Weather Records</h2>
    <ul>
      <li v-for="item in weatherList" :key="item.id">
        {{ item.city }} - {{ item.temperature }}°C - {{ item.windSpeed }} km/h
        <button @click="deleteWeather(item.id)" title="Delete this record">Delete</button>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

// Reactive list to hold weather data from backend
const weatherList = ref([])

// Function to fetch weather records from the API
const fetchData = async () => {
  const res = await axios.get('/api/weather')
  weatherList.value = res.data
}

// Function to delete a weather record by ID
const deleteWeather = async (id) => {
  await axios.delete(`/api/weather/${id}`)
  await fetchData() // Refresh the list after deletion
}

// Fetch data when the component is mounted
onMounted(fetchData)
</script>
