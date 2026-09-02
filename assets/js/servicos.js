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
});
