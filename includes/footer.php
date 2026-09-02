<?php
// includes/footer.php
// Rodapé do site
?>
    </main>
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section footer-brand">
                    <a href="/" class="logo"><img src="/assets/img/logo.svg" alt=""><h1>Vol<span>X</span></h1></a>
                    <p>Energia segura, atendimento ágil e soluções elétricas feitas para durar.</p>
                </div>
                <div class="footer-section">
                    <h3>Contato</h3>
                    <p>contato@volx.com<br>(11) 9999-9999<br>Seg–Sex, 8h às 18h</p>
                </div>
                <div class="footer-section">
                    <h3>Links</h3>
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="/servicos.php">Serviços</a></li>
                        <li><a href="/blog/">Blog</a></li>
                        <li><a href="/sobre.php">Sobre</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> VoltX. Todos os direitos reservados.</p>
                <span>Energia que move você.</span>
            </div>
        </div>
    </footer>
    <script src="/assets/js/main.js"></script>
    <?php foreach (($scripts_pagina ?? []) as $script): ?>
        <script src="<?php echo htmlspecialchars($script, ENT_QUOTES, 'UTF-8'); ?>"></script>
    <?php endforeach; ?>
</body>
</html>
