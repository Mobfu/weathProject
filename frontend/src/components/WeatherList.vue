<template>
    <div>
      <h2>所有天气记录</h2>
      <ul>
        <li v-for="item in weatherList" :key="item.id">
          {{ item.city }} - {{ item.temperature }}°C - {{ item.windSpeed }} km/h
          <button @click="deleteWeather(item.id)">删除</button>
        </li>
      </ul>
    </div>
  </template>
  
  <script setup>
  import { onMounted, ref } from 'vue'
  import axios from 'axios'
  
  const weatherList = ref([])
  
  const fetchData = async () => {
    const res = await axios.get('/api/weather')
    weatherList.value = res.data
  }
  
  const deleteWeather = async (id) => {
    await axios.delete(`/api/weather/${id}`)
    await fetchData()
  }
  
  onMounted(fetchData)
  </script>
  