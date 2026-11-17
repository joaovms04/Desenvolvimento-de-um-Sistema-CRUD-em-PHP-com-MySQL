// Pequenos comportamentos da interface (sem bibliotecas externas)

// 1) Pede confirmação antes de enviar formulários que têm o atributo data-confirma (exclusões)
document.querySelectorAll('form[data-confirma]').forEach(function (formulario) {
    formulario.addEventListener('submit', function (evento) {
        if (!confirm(formulario.dataset.confirma)) {
            evento.preventDefault();
        }
    });
});

// 2) As mensagens de sucesso somem sozinhas depois de alguns segundos
document.querySelectorAll('.alerta-sucesso').forEach(function (alerta) {
    setTimeout(function () {
        alerta.classList.add('some');
        setTimeout(function () { alerta.remove(); }, 700);
    }, 5000);
});
