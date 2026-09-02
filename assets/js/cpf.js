document.addEventListener('DOMContentLoaded',()=>{const cpf=document.querySelector('#cpf');cpf?.addEventListener('input',()=>cpf.value=cpf.value.replace(/\D/g,'').slice(0,11))});
