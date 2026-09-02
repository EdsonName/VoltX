document.addEventListener('DOMContentLoaded', () => {
    const campo = id => document.querySelector(`#${id}`);
    document.querySelectorAll('.admin-flip-card').forEach(card => {
        const abrir = card.querySelector('.admin-flip-trigger');
        const fechar = card.querySelector('.flip-back');
        const frente = card.querySelector('.service-card-front');
        const verso = card.querySelector('.service-card-back');
        const definirEstado = aberto => {
            card.classList.toggle('is-editing', aberto);
            frente?.setAttribute('aria-hidden', String(aberto));
            verso?.setAttribute('aria-hidden', String(!aberto));
            (aberto ? verso?.querySelector('input[name="nome"]') : abrir)?.focus();
        };
        abrir?.addEventListener('click', () => definirEstado(true));
        fechar?.addEventListener('click', () => definirEstado(false));
        card.addEventListener('keydown', event => {
            if (event.key === 'Escape' && card.classList.contains('is-editing')) definirEstado(false);
        });
    });

    const preview = document.querySelector('.service-preview');
    if (!preview) return;

    const atualizar = () => {
        const nome = campo('nome').value.trim() || 'Nome do serviço';
        const descricao = campo('descricao').value.trim() || 'A descrição do serviço aparecerá aqui.';
        const precoNumero = Number(campo('preco').value || 0);
        const selo = campo('selo').value.trim() || 'Profissional';
        const imagem = campo('imagem_url').value.trim();
        const beneficios = campo('beneficios').value.split(/\r?\n/).map(item => item.trim()).filter(Boolean);
        preview.querySelector('.preview-body h3 span').textContent = nome;
        preview.querySelector('.preview-body strong span').textContent = precoNumero.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        preview.querySelector('.preview-body p').textContent = descricao;
        preview.querySelector('.preview-image b').textContent = selo;
        const img = preview.querySelector('.preview-image img');
        img.src = imagem;
        img.alt = imagem ? `Prévia de ${nome}` : '';
        preview.querySelector('.preview-body ul').innerHTML = (beneficios.length ? beneficios.slice(0, 3) : ['Benefício do serviço']).map(item => `<li>✓ ${item.replace(/[<>&"']/g, '')}</li>`).join('');
    };

    ['nome','descricao','preco','selo','imagem_url','beneficios'].forEach(id => campo(id)?.addEventListener('input', atualizar));
    campo('imagem_arquivo')?.addEventListener('change', event => {
        const arquivo = event.target.files?.[0];
        if (!arquivo) return atualizar();
        const leitor = new FileReader();
        leitor.addEventListener('load', () => {
            const img = preview.querySelector('.preview-image img');
            img.src = leitor.result;
            img.alt = `Prévia de ${campo('nome').value || 'serviço'}`;
            preview.querySelector('.file-name')?.remove();
        });
        leitor.readAsDataURL(arquivo);
        const texto = document.querySelector('.file-upload strong');
        if (texto) texto.textContent = arquivo.name;
    });
    atualizar();
});
