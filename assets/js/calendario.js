// assets/js/calendario.js
// Calendário para agendamentos

class Calendario {
    constructor(elementId) {
        this.element = document.getElementById(elementId);
        this.data_atual = new Date();
        this.inicializar();
    }
    
    inicializar() {
        this.renderizar();
        this.adicionarEventos();
    }
    
    renderizar() {
        const mes = this.data_atual.getMonth();
        const ano = this.data_atual.getFullYear();
        const primeiro_dia = new Date(ano, mes, 1).getDay();
        const dias_mes = new Date(ano, mes + 1, 0).getDate();
        
        let html = `<div class="calendario">`;
        html += `<h3>${this.getNomeMes(mes)} ${ano}</h3>`;
        html += `<table>`;
        html += `<tr><th>Dom</th><th>Seg</th><th>Ter</th><th>Qua</th><th>Qui</th><th>Sex</th><th>Sab</th></tr><tr>`;
        
        for (let i = 0; i < primeiro_dia; i++) {
            html += `<td></td>`;
        }
        
        for (let dia = 1; dia <= dias_mes; dia++) {
            if ((primeiro_dia + dia - 1) % 7 === 0 && dia !== 1) {
                html += `</tr><tr>`;
            }
            html += `<td class="dia" data-dia="${dia}">${dia}</td>`;
        }
        
        html += `</tr></table></div>`;
        this.element.innerHTML = html;
    }
    
    getNomeMes(mes) {
        const meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                      'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
        return meses[mes];
    }
    
    adicionarEventos() {
        const dias = this.element.querySelectorAll('.dia');
        dias.forEach(dia => {
            dia.addEventListener('click', (e) => {
                dias.forEach(d => d.classList.remove('selecionado'));
                e.target.classList.add('selecionado');
                console.log('Dia selecionado:', e.target.dataset.dia);
            });
        });
    }
}

// Inicializar quando o documento estiver pronto
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        const calendarioElement = document.getElementById('calendario');
        if (calendarioElement) {
            new Calendario('calendario');
        }
    });
} else {
    const calendarioElement = document.getElementById('calendario');
    if (calendarioElement) {
        new Calendario('calendario');
    }
}
