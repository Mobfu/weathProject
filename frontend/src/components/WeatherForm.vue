<template>
    <div>
      <h2>添加天气记录</h2>
      <form @submit.prevent="submitForm">
        <input v-model="city" placeholder="城市" />
        <input v-model.number="temperature" placeholder="温度" />
        <input v-model.number="windSpeed" placeholder="风速" />
        <button type="submit">添加</button>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import axios from 'axios'
  
  const city = ref('')
  const temperature = ref(0)
  const windSpeed = ref(0)
  
  const submitForm = async () => {
    await axios.post('/api/weather', {
      city: city.value,
      temperature: temperature.value,
      windSpeed: windSpeed.value,
      date: new Date().toISOString()
    })
    alert('添加成功')
    city.value = ''
    temperature.value = 0
    windSpeed.value = 0
  }
  </script>
  