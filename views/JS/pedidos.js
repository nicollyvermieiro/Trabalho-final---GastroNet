document.addEventListener('DOMContentLoaded', function () {
    // Função para carregar os clientes
    function carregarClientes() {
        fetch('../controllers/clienteController.php', {
            method: 'POST',
            body: new URLSearchParams({ acao: 'listar' })
        })
        .then(response => response.json())
        .then(data => {
            const clienteSelect = document.getElementById('listaCliente');
            clienteSelect.innerHTML = '<option value="" disabled selected>Selecione um cliente</option>'; // Limpa a lista

            if (data.message) {
                alert(data.message);
            } else {
                data.forEach(cliente => {
                    const option = document.createElement('option');
                    option.value = cliente.id;
                    option.textContent = cliente.nome;
                    clienteSelect.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Erro ao carregar clientes:', error));
    }

    // Função para carregar os itens do cardápio
    function carregarItensCardapio() {
        fetch('../controllers/cardapioController.php', {
            method: 'POST',
            body: new URLSearchParams({ acao: 'listar' })
        })
        .then(response => response.json())
        .then(data => {
            const itensSelect = document.getElementById('listaItens');
            itensSelect.innerHTML = '<option value="" disabled selected>Selecione um item</option>'; // Limpa a lista

            if (data.message) {
                alert(data.message);
            } else {
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = `${item.nome} - R$ ${parseFloat(item.valor).toFixed(2)}`;
                    option.setAttribute('data-preco', item.valor);
                    itensSelect.appendChild(option);
                });

                // Atualiza o valor total ao selecionar itens
                itensSelect.addEventListener('change', atualizarValorTotal);
            }
        })
        .catch(error => console.error('Erro ao carregar itens do cardápio:', error));
    }

    // Função para calcular e atualizar o valor total
    function atualizarValorTotal() {
        const itensSelect = document.getElementById('listaItens');
        const selectedOption = itensSelect.options[itensSelect.selectedIndex];
        const preco = parseFloat(selectedOption.getAttribute('data-preco')) || 0;

        const valorTotalElement = document.getElementById('valorTotal');
        const valorAtual = parseFloat(valorTotalElement.textContent);
        valorTotalElement.textContent = (valorAtual + preco).toFixed(2);
    }

    // Função para cadastrar um novo pedido
    document.getElementById('cadastroPedido').addEventListener('submit', function (event) {
        event.preventDefault();

        const clienteId = document.getElementById('listaCliente').value;
        const itensSelect = document.getElementById('listaItens');
        const itemSelecionado = itensSelect.options[itensSelect.selectedIndex];
        const formaPag = document.getElementById('formaPag').value;

        if (!clienteId || !itemSelecionado) {
            alert('Por favor, selecione um cliente e pelo menos um item.');
            return;
        }

        const itens = [{
            id: itemSelecionado.value,
            nome: itemSelecionado.textContent,
            preco: itemSelecionado.getAttribute('data-preco')
        }];

        const valorTotal = parseFloat(document.getElementById('valorTotal').textContent);

        fetch('../controllers/pedidoController.php', {
            method: 'POST',
            body: new URLSearchParams({
                acao: 'salvar',
                cliente_id: clienteId,
                itens: JSON.stringify(itens),
                valor_total: valorTotal.toFixed(2),
                forma_pag: formaPag
            })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.message === 'Pedido cadastrado com sucesso!') {
                carregarPedidos();
            }
        })
        .catch(error => console.error('Erro ao cadastrar pedido:', error));
    });

    // Função para carregar todos os pedidos
    function carregarPedidos() {
        fetch('../controllers/pedidoController.php', {
            method: 'POST',
            body: new URLSearchParams({ acao: 'listar' })
        })
        .then(response => response.json())
        .then(data => {
            const listaPedidos = document.getElementById('listaPedido');
            listaPedidos.innerHTML = ''; // Limpa a lista antes de recarregar

            if (data.message) {
                listaPedidos.innerHTML = `<p>${data.message}</p>`;
            } else {
                data.forEach(pedido => {
                    const pedidoElement = document.createElement('li');
                    pedidoElement.classList.add('pedido-item');
                    pedidoElement.innerHTML = `
                        <strong>Pedido #${pedido.num_pedido}</strong><br>
                        <strong>Cliente:</strong> ${pedido.cliente_nome}<br>
                        <strong>Valor Total:</strong> R$ ${parseFloat(pedido.valor_total).toFixed(2)}<br>
                        <strong>Forma de Pagamento:</strong> ${pedido.forma_pag}
                    `;
                    listaPedidos.appendChild(pedidoElement);
                });
            }
        })
        .catch(error => console.error('Erro ao carregar pedidos:', error));
    }

    // Carregar os dados ao carregar a página
    carregarClientes();
    carregarItensCardapio();
    carregarPedidos();
});