document.addEventListener('DOMContentLoaded', () => {
    const data = document.querySelector('#data-agendamento');
    const hora = document.querySelector('#hora-agendamento');
    const hoje = new Date();
    const localHoje = new Date(hoje.getTime() - hoje.getTimezoneOffset() * 60000).toISOString().split('T')[0];
    data.min = localHoje;
    if (!data.value) data.value = localHoje;

    document.querySelectorAll('.time-slot').forEach(slot => {
        slot.addEventListener('click', () => {
            hora.value = slot.dataset.time;
            document.querySelectorAll('.time-slot').forEach(item => item.classList.remove('active'));
            slot.classList.add('active');
        });
    });
    hora.addEventListener('input', () => {
        document.querySelectorAll('.time-slot').forEach(item => item.classList.toggle('active', item.dataset.time === hora.value));
    });

    const cep = document.querySelector('#cep');
    const cepStatus = document.querySelector('#cep-status');
    cep.addEventListener('input', async () => {
        const numeros = cep.value.replace(/\D/g, '').slice(0, 8);
        cep.value = numeros.replace(/(\d{5})(\d)/, '$1-$2');
        if (numeros.length !== 8) return;
        cepStatus.textContent = 'Buscando endereço…';
        cepStatus.style.color = 'var(--yellow)';
        try {
            const resposta = await fetch(`https://viacep.com.br/ws/${numeros}/json/`);
            const endereco = await resposta.json();
            if (endereco.erro) throw new Error('CEP não encontrado');
            document.querySelector('#bairro-cidade').value = `${endereco.bairro} — ${endereco.localidade}/${endereco.uf}`;
            document.querySelector('#endereco').value = endereco.logradouro ? `${endereco.logradouro}, ` : '';
            document.querySelector('#endereco').focus();
            cepStatus.textContent = 'Endereço encontrado ✓';
            cepStatus.style.color = '#4edd83';
        } catch {
            cepStatus.textContent = 'CEP não encontrado. Preencha o endereço manualmente.';
            cepStatus.style.color = '#ff8991';
        }
    });

    document.querySelector('#location-button').addEventListener('click', () => {
        const status = document.querySelector('#location-status');
        status.classList.add('show');
        status.textContent = 'Obtendo sua localização…';
        status.style.color = 'var(--yellow)';
        if (!navigator.geolocation) {
            status.textContent = 'Geolocalização não suportada neste navegador.';
            return;
        }
        navigator.geolocation.getCurrentPosition(position => {
            document.querySelector('#coords-gps').value = `https://maps.google.com/?q=${position.coords.latitude},${position.coords.longitude}`;
            status.textContent = 'Localização capturada com sucesso ✓';
            status.style.color = '#4edd83';
        }, () => {
            status.textContent = 'Não foi possível acessar sua localização.';
            status.style.color = '#ff8991';
        }, {enableHighAccuracy: true, timeout: 10000});
    });
});
