// assets/js/main.js
// JavaScript principal

document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.querySelector('.menu-toggle');
    const nav = document.querySelector('header nav');
    if (toggle && nav) {
        toggle.addEventListener('click', () => {
            const aberto = nav.classList.toggle('open');
            toggle.setAttribute('aria-expanded', aberto);
            toggle.textContent = aberto ? '×' : '☰';
        });
    }

    const atual = window.location.pathname;
    document.querySelectorAll('header nav a').forEach(link => {
        const destino = new URL(link.href).pathname;
        if ((destino === '/' && atual === '/') || (destino !== '/' && atual.startsWith(destino.replace(/[^/]+$/, '')) && atual === destino)) {
            link.classList.add('active');
        }
    });
    
    // Validação de formulários
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', validarFormulario);
    });
    
    // Fechar alertas
    const alertas = document.querySelectorAll('.alerta');
    alertas.forEach(alerta => {
        setTimeout(() => {
            alerta.style.opacity = '0';
            setTimeout(() => {
                alerta.style.display = 'none';
            }, 300);
        }, 5000);
    });
});

function validarFormulario(e) {
    const form = e.target;
    const campos = form.querySelectorAll('input[required], textarea[required]');
    
    let valido = true;
    campos.forEach(campo => {
        if (!campo.value.trim()) {
            campo.style.borderColor = '#dc3545';
            valido = false;
        } else {
            campo.style.borderColor = '#30333c';
        }
    });
    
    if (!valido) {
        e.preventDefault();
    }
}

// Formatação de valores monetários
function formatarMoeda(valor) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(valor);
}

// Formatação de datas
function formatarData(data) {
    return new Intl.DateTimeFormat('pt-BR', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    }).format(new Date(data));
}
