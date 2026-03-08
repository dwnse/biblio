
<script setup>
import { RouterLink, RouterView } from 'vue-router'
import FooterBar from './components/FooterBar.vue'
</script>

<template>
  <div id="app">
    <header>
      <nav class="navbar">
        <div class="navbar-brand">
          <span class="logo-icon">📚</span>
          <span class="logo-text">BiblioDigital</span>
        </div>
        <div class="navbar-links">
          <RouterLink to="/">Inicio</RouterLink>
          <RouterLink to="/catalog">Catálogo</RouterLink>
          <RouterLink to="/login">Login</RouterLink>
          <RouterLink to="/register">Registro</RouterLink>
        </div>
      </nav>
    </header>

    <main>
      <RouterView />
    </main>

    <FooterBar />
  </div>
</template>

<style scoped>

body {
  font-family: 'Georgia', 'Segoe UI', 'Roboto', serif;
  background: repeating-linear-gradient(135deg, #f5f3ea 0px, #f5f3ea 40px, #f3e9d2 40px, #f3e9d2 80px);
  color: #222;
  min-height: 100vh;
}

#app {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

  background: linear-gradient(90deg, #2d2d34 80%, #bfa76a 100%);
  color: #f5f5f5;
  box-shadow: 0 2px 12px rgba(0,0,0,0.08);
  border-bottom: 3px solid #bfa76a;
}
}

.navbar {
.navbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  max-width: 1200px;
  margin: 0 auto;
  padding: 1.2rem 2.5rem 1.2rem 2.5rem;
}
  display: flex;
  align-items: center;
  gap: 0.7rem;
  font-size: 1.5rem;
  font-weight: bold;
  color: #bfa76a;
  letter-spacing: 2px;
  text-shadow: 0 2px 8px #2d2d3433;
}
  font-size: 2rem;
  filter: drop-shadow(0 1px 2px #bfa76a88);
}
.logo-text {
  letter-spacing: 1px;
}
.navbar-links {
  display: flex;
  gap: 1.5rem;
}
  color: #f5f5f5;
  text-decoration: none;
  font-size: 1.08rem;
  padding: 0.4rem 1.1rem;
  border-radius: 6px;
  transition: background 0.2s, color 0.2s, box-shadow 0.2s;
  font-family: 'Georgia', serif;
  letter-spacing: 1px;
  box-shadow: 0 1px 4px #bfa76a22;
}
  background: #bfa76a;
  color: #2d2d34;
  font-weight: bold;
  box-shadow: 0 2px 8px #bfa76a44;
}
  background: #bfa76a99;
  color: #2d2d34;
  box-shadow: 0 2px 8px #bfa76a33;
}

  flex: 1;
  max-width: 1200px;
  margin: 0 auto;
  width: 100%;
  padding: 2.5rem 1.5rem 1.5rem 1.5rem;
  background: transparent;
}
</style>