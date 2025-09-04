document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('inputBusca');
    const tabela = document.getElementById('tabela-pontos');
    let timeout = null;

    input.addEventListener('keyup', () => {
        clearTimeout(timeout);

        timeout = setTimeout(() => {
            const valor = input.value;

            fetch(`listar_ponto.php?ajax=1&busca=${encodeURIComponent(valor)}`)
                .then(res => res.text())
                .then(html => {
                    tabela.innerHTML = html;
                });
        }, 300); // atraso para evitar requisições a cada tecla
    });
});
