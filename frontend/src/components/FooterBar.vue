<template>
  <footer class="footer">
    <div class="footer-content">
      <span>BiblioDigital &copy; 2026</span>
      <span class="footer-books">Tu biblioteca digital minimalista</span>
    </div>
  </footer>
</template>

<script setup>
// Footer simple, sin lógica
</script>

<style scoped>
  background: linear-gradient(90deg, #2d2d34 80%, #bfa76a 100%);
  color: #f5f5f5;
  padding: 2rem 0 1.2rem 0;
  text-align: center;
  font-size: 1.08rem;
  border-top: 3px solid #bfa76a;
  margin-top: 3rem;
  box-shadow: 0 -2px 12px #bfa76a22;
}
.footer-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
}
.footer-books {
  font-size: 0.95rem;
  color: #bfa76a;
  letter-spacing: 1px;
}
</style>
