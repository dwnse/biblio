<template>
  <div class="register">
    <h2>Registrarse</h2>
    <form @submit.prevent="register">
      <div>
        <label for="nombre">Nombre:</label>
        <input v-model="form.nombre" type="text" id="nombre" required>
      </div>
      <div>
        <label for="apellidoP">Apellido Paterno:</label>
        <input v-model="form.apellidoP" type="text" id="apellidoP">
      </div>
      <div>
        <label for="apellidoM">Apellido Materno:</label>
        <input v-model="form.apellidoM" type="text" id="apellidoM">
      </div>
      <div>
        <label for="email">Email:</label>
        <input v-model="form.email" type="email" id="email" required>
      </div>
      <div>
        <label for="password">Contraseña:</label>
        <input v-model="form.password" type="password" id="password" required>
      </div>
      <div>
        <label for="telefono">Teléfono:</label>
        <input v-model="form.telefono" type="text" id="telefono">
      </div>
      <button type="submit" :disabled="loading">Registrarse</button>
    </form>
    <p v-if="error" class="error">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()
const form = ref({
  nombre: '',
  apellidoP: '',
  apellidoM: '',
  email: '',
  password: '',
  telefono: ''
})
const loading = ref(false)
const error = ref('')

const register = async () => {
  loading.value = true
  error.value = ''
  try {
    const response = await axios.post('/api/register', form.value)
    localStorage.setItem('token', response.data.token)
    axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`
    // Eliminada la redirección automática tras registro para mejor UX
  } catch (err) {
    error.value = err.response?.data?.message || 'Error al registrarse'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.register {
  max-width: 400px;
  margin: 2rem auto;
  padding: 2rem;
  border: 1px solid #ddd;
  border-radius: 8px;
}
form div {
  margin-bottom: 1rem;
}
label {
  display: block;
  margin-bottom: 0.5rem;
}
input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
}
button {
  width: 100%;
  padding: 0.75rem;
  background-color: #28a745;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
button:disabled {
  background-color: #ccc;
}
.error {
  color: red;
  margin-top: 1rem;
}
</style>