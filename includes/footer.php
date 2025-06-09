<footer class="footer mt-auto py-3 bg-light">
    <div class="container text-center">
        <div class="social-links mt-2">
            <a href="https://www.facebook.com" target="_blank" class="text-muted me-3" aria-label="Facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://www.twitter.com" target="_blank" class="text-muted me-3" aria-label="Twitter">
                <i class="fab fa-twitter"></i>
            </a>
            <a href="https://www.instagram.com" target="_blank" class="text-muted me-3" aria-label="Instagram">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://www.linkedin.com" target="_blank" class="text-muted" aria-label="LinkedIn">
                <i class="fab fa-linkedin-in"></i>
            </a>
        </div>
        <span class="text-muted">© 2024 - Sistema de Gestión de Documentos</span>
    </div>
</footer>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.querySelector('.sidebar');
        const toggleButton = document.querySelector('.navbar-toggler');
        if (sidebar && toggleButton) {
            toggleButton.addEventListener('click', function() {
                sidebar.classList.toggle('d-none');
            });
        }
    });
</script>
</body>
</html>
