<?php
// includes/footer.php
// Rodapé do site
?>
    </main>
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section footer-brand">
                    <a href="/" class="logo"><img src="/assets/img/logo.svg" alt="VoltX"><h1>Volt<span>X</span></h1></a>
                    <p>Energia segura, atendimento ágil e soluções elétricas feitas para durar.</p>
                </div>
                <div class="footer-section">
                    <h3>Contato</h3>
                    <p><?php echo sanitizar(config_site('email_contato', 'contato@voltx.com')); ?><br><?php echo sanitizar(config_site('telefone_contato', '(11) 9999-9999')); ?><br><?php echo sanitizar(config_site('horario_atendimento', 'Seg–Sex, 8h às 18h')); ?></p>
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
    <?php if (($caminho_atual ?? '') === '/profissional.php' && !empty($perfil['id'])): $destino_chat_perfil = '/chat.php?profissional_id=' . (int)$perfil['id']; ?>
        <script>const whatsapp=document.querySelector('.contact-professional');if(whatsapp){const chat=document.createElement('a');chat.className='contact-professional internal-chat';chat.textContent='✉ Conversar pelo chat da VoltX';chat.href=<?php echo json_encode(usuario_autenticado() ? $destino_chat_perfil : '/login.php?redirect=' . rawurlencode($destino_chat_perfil)); ?>;whatsapp.before(chat)}</script>
    <?php endif; ?>
    <?php if (($caminho_atual ?? '') === '/profissional.php' && !empty($perfil['id']) && usuario_autenticado() && !usuario_eh_profissional() && !usuario_eh_admin()): ?>
        <script>const profileAside=document.querySelector('.public-profile>aside');if(profileAside){const rating=document.createElement('form');rating.className='profile-rating';rating.method='POST';rating.action='/feed-acao.php';rating.innerHTML=<?php echo json_encode('<input type="hidden" name="csrf_token" value="'.token_csrf().'"><input type="hidden" name="acao" value="avaliar"><input type="hidden" name="profissional_id" value="'.(int)$perfil['id'].'"><label>Recomendar este profissional</label><select name="nota"><option value="5">★★★★★ Excelente</option><option value="4">★★★★ Muito bom</option><option value="3">★★★ Bom</option><option value="2">★★ Regular</option><option value="1">★ Ruim</option></select><button>Enviar avaliação</button>'); ?>;profileAside.append(rating)}</script>
    <?php endif; ?>
</body>
</html>
