document.addEventListener('DOMContentLoaded', () => {
    const escapar = texto => texto.replace(/[&<>"']/g, caractere => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[caractere]));
    const inline = texto => escapar(texto).replace(/`([^`]+)`/g, '<code>$1</code>').replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>').replace(/(^|[^*])\*([^*]+)\*(?!\*)/g, '$1<em>$2</em>');
    const markdown = texto => {
        let html = '', lista = false;
        texto.split(/\r?\n/).forEach(linhaOriginal => {
            const linha = linhaOriginal.trim();
            const item = linha.match(/^[-*]\s+(.+)$/);
            if (item) { if (!lista) { html += '<ul>'; lista = true; } html += `<li>${inline(item[1])}</li>`; return; }
            if (lista) { html += '</ul>'; lista = false; }
            if (!linha) return;
            const titulo = linha.match(/^(#{1,3})\s+(.+)$/);
            html += titulo ? `<h${titulo[1].length + 1}>${inline(titulo[2])}</h${titulo[1].length + 1}>` : `<p>${inline(linha)}</p>`;
        });
        return html + (lista ? '</ul>' : '');
    };

    document.querySelectorAll('.markdown-field').forEach(campo => {
        const textarea = campo.querySelector('textarea');
        const preview = campo.querySelector('.markdown-preview');
        const atualizar = () => preview.innerHTML = markdown(textarea.value);
        campo.querySelectorAll('[data-md]').forEach(botao => botao.addEventListener('click', () => {
            const inicio = textarea.selectionStart, fim = textarea.selectionEnd;
            const selecao = textarea.value.slice(inicio, fim) || 'texto';
            const modelos = {bold:`**${selecao}**`, italic:`*${selecao}*`, heading:`## ${selecao}`, list:selecao.split('\n').map(item => `- ${item}`).join('\n')};
            textarea.setRangeText(modelos[botao.dataset.md], inicio, fim, 'select');
            textarea.focus(); atualizar();
        }));
        textarea.addEventListener('input', atualizar); atualizar();
    });

    document.querySelector('#fotos_sobre')?.addEventListener('change', event => {
        const destino = document.querySelector('.new-photo-preview');
        destino.innerHTML = '';
        [...event.target.files].forEach(arquivo => {
            const figure = document.createElement('figure');
            const img = document.createElement('img');
            const caption = document.createElement('figcaption');
            img.src = URL.createObjectURL(arquivo); img.alt = '';
            caption.textContent = arquivo.name;
            figure.append(img, caption); destino.append(figure);
        });
    });
});
