document.addEventListener('DOMContentLoaded', () => {
    const abrirWhatsapp = (telefone, mensagem) => {
        window.open(`https://wa.me/${telefone}?text=${encodeURIComponent(mensagem)}`, '_blank', 'noopener,noreferrer');
    };

    document.querySelector('[data-whatsapp-estimate]')?.addEventListener('click', event => {
        const cidade = document.querySelector('#calc-cidade')?.value;
        const servico = document.querySelector('#calc-servico')?.value;
        abrirWhatsapp(event.currentTarget.dataset.phone, `Olá Edson! Fiz uma simulação no site VoltX.\n\nRegião: ${cidade}\nServiço: ${servico}\n\nGostaria de receber uma estimativa.`);
    });

    document.querySelector('[data-whatsapp-urgent]')?.addEventListener('click', event => {
        const sintoma = document.querySelector('#diag-sintoma')?.value;
        abrirWhatsapp(event.currentTarget.dataset.phone, `ALERTA URGENTE - VOLTX\n\nOlá Edson, estou com o seguinte problema: ${sintoma}. Pode me atender agora?`);
    });

    document.querySelectorAll('.service-flip-card').forEach(card => {
        const frente = card.querySelector('.service-card-front');
        const verso = card.querySelector('.service-card-back');
        const abrir = card.querySelector('.flip-trigger');
        const fechar = card.querySelector('.flip-back');
        const definirEstado = aberto => {
            card.classList.toggle('is-flipped', aberto);
            abrir?.setAttribute('aria-expanded', String(aberto));
            frente?.setAttribute('aria-hidden', String(aberto));
            verso?.setAttribute('aria-hidden', String(!aberto));
            (aberto ? fechar : abrir)?.focus();
        };
        abrir?.addEventListener('click', () => definirEstado(true));
        fechar?.addEventListener('click', () => definirEstado(false));
        card.addEventListener('click', event => {
            if (card.classList.contains('is-paused')) return;
            if (event.target.closest('a, button, input, select, textarea, label')) return;
            definirEstado(!card.classList.contains('is-flipped'));
        });
        card.addEventListener('keydown', event => {
            if (event.key === 'Escape' && card.classList.contains('is-flipped')) definirEstado(false);
        });
        const solicitado = new URLSearchParams(window.location.search).get('detalhes');
        if (solicitado && solicitado === card.dataset.serviceId && !card.classList.contains('is-paused')) {
            definirEstado(true);
            card.scrollIntoView({behavior: 'smooth', block: 'center'});
        }
    });
});
