<template>
  <div class="catalog">
    <WeatherWidget />
    <h2>Catálogo de Libros</h2>
    <div class="search">
      <input v-model="searchQuery" @input="searchBooks" placeholder="Buscar por título o ISBN" type="text">
      <select v-model="selectedCategory" @change="filterByCategory">
        <option value="">Todas las categorías</option>
        <option v-for="category in categories" :key="category.id_categoria" :value="category.id_categoria">
          {{ category.nombre }}
        </option>
      </select>
    </div>
    <div class="books">
      <div v-for="book in books" :key="book.id_libro" class="book-card">
        <img :src="book.portada_url" :alt="book.titulo" class="book-cover">
        <h3>{{ book.titulo }}</h3>
        <p>Autores: {{ book.autores.map(a => a.nombres + ' ' + a.apellidos).join(', ') }}</p>
        <p>Calificación: {{ book.average_rating || 'N/A' }}</p>
        <button @click="viewBook(book.id_libro)">Ver Detalles</button>
      </div>
    </div>
    <div v-if="loading" class="loading">Cargando...</div>
  </div>
</template>


<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import WeatherWidget from '../components/WeatherWidget.vue'

const books = ref([])
const categories = ref([])
const searchQuery = ref('')
const selectedCategory = ref('')
const loading = ref(false)

const fetchBooks = async () => {
  loading.value = true
  try {
    const params = {}
    if (searchQuery.value) params.q = searchQuery.value
    if (selectedCategory.value) params.categoria = selectedCategory.value
    const response = await axios.get('/api/books', { params })
    books.value = response.data
  } catch (error) {
    console.error('Error fetching books:', error)
  } finally {
    loading.value = false
  }
}

const fetchCategories = async () => {
  try {
    const response = await axios.get('/api/categories')
    categories.value = response.data
  } catch (error) {
    console.error('Error fetching categories:', error)
  }
}

const searchBooks = () => {
  fetchBooks()
}

const filterByCategory = () => {
  fetchBooks()
}

const viewBook = (id) => {
  // Navigate to book detail
}

onMounted(() => {
  fetchBooks()
  fetchCategories()
})
</script>

<style scoped>
 .catalog {
   position: relative;
   padding: 2.5rem 0 2.5rem 0;
   background: transparent;
   min-height: 80vh;
   border-radius: 18px;
   box-shadow: 0 4px 32px #bfa76a22, 0 1.5px 0 #bfa76a33;
   margin-bottom: 2.5rem;
 }
.catalog h2 {
  font-family: 'Georgia', serif;
  color: #bfa76a;
  font-size: 2.1rem;
  margin-bottom: 1.5rem;
  letter-spacing: 1px;
  text-shadow: 0 2px 8px #bfa76a22;
}
.search {
  margin-bottom: 2rem;
  display: flex;
  gap: 1rem;
  align-items: center;
}
.search input, .search select {
  margin-right: 0.5rem;
  padding: 0.5rem 1rem;
  border: 1px solid #bfa76a;
  border-radius: 6px;
  background: #fffbe9;
  font-size: 1rem;
  color: #222;
  outline: none;
  transition: border 0.2s;
}
.search input:focus, .search select:focus {
  border: 1.5px solid #bfa76a;
}
.books {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 2rem;
}
.book-card {
  background: linear-gradient(135deg, #fffbe9 80%, #f3e9d2 100%);
  border: 2px solid #bfa76a;
  border-radius: 18px;
  box-shadow: 0 4px 24px #bfa76a22, 0 1.5px 0 #bfa76a33;
  padding: 2rem 1.2rem 1.5rem 1.2rem;
  text-align: center;
  transition: box-shadow 0.2s, border 0.2s, transform 0.2s;
  display: flex;
  flex-direction: column;
  align-items: center;
  position: relative;
}
.book-card:hover {
  box-shadow: 0 8px 32px #bfa76a44, 0 2px 0 #bfa76a55;
  border: 2px solid #a88c3d;
  transform: translateY(-4px) scale(1.025);
}
.book-cover {
  max-width: 100%;
  height: 210px;
  object-fit: cover;
  margin-bottom: 1.2rem;
  border-radius: 10px;
  box-shadow: 0 2px 12px #bfa76a33;
  background: #f5f3ea url('https://cdn.pixabay.com/photo/2017/01/31/13/14/book-2022464_1280.png') center/40px no-repeat;
}
.book-card h3 {
  color: #2d2d34;
  font-size: 1.22rem;
  margin: 0.7rem 0 0.2rem 0;
  font-family: 'Georgia', serif;
  letter-spacing: 0.5px;
}
.book-card p {
  color: #6b5e3c;
  font-size: 1.01rem;
  margin: 0.1rem 0;
  font-family: 'Georgia', serif;
}
.book-card button {
  margin-top: 0.9rem;
  background: linear-gradient(90deg, #bfa76a 80%, #a88c3d 100%);
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 0.6rem 1.5rem;
  font-size: 1.08rem;
  cursor: pointer;
  transition: background 0.2s, box-shadow 0.2s;
  font-family: 'Georgia', serif;
  box-shadow: 0 1px 4px #bfa76a33;
}
.book-card button:hover {
  background: linear-gradient(90deg, #a88c3d 80%, #bfa76a 100%);
  box-shadow: 0 2px 8px #bfa76a44;
}
.loading {
  text-align: center;
  padding: 2rem;
  color: #bfa76a;
  font-size: 1.2rem;
  font-family: 'Georgia', serif;
}
</style>