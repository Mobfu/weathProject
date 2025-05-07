<template>
    <div>
      <h2>获取实时天气</h2>
      <input v-model="city" placeholder="城市名（如 paris）" />
      <button @click="fetchRealtime">获取</button>
  
      <div v-if="result">
        当前温度：{{ result.temperature }}°C，风速：{{ result.windspeed }} km/h
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import axios from 'axios'
  
  const city = ref('')
  const result = ref(null)
  
  const fetchRealtime = async () => {
    const res = await axios.get(`/api/weather/realtime/${city.value}`)
    result.value = res.data
  }
  </script>
  