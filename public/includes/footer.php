</main>

<footer class="footer">
    <div class="container">
        <p>&copy;
            <?= date('Y') ?>
            <?= APP_NAME ?> — Biblioteca Digital. Tecnología Web II.
        </p>
    </div>
</footer>

<script src="<?= BASE_URL ?>/js/app.js?v=<?= filemtime(__DIR__ . '/../js/app.js') ?>"></script>
<?php if (isset($extraScripts))
    echo $extraScripts; ?>
</body>

</html>