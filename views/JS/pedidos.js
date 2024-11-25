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
        .catch(error => {
            console.error('Erro ao carregar clientes:', error);
            Swal.fire({
                title: "ERRO",
                text: "Erro ao carregar clientes.",
                icon: "error",
                confirmButtonColor: '#5e0a0a'
            });
        });
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
            itensSelect.innerHTML = ''; // Limpa a lista

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
        .catch(error => {
            console.error('Erro ao carregar itens do cardápio:', error);
            Swal.fire({
                title: "ERRO",
                text: "Erro ao carregar itens do cardápio.",
                icon: "error",
                confirmButtonColor: '#5e0a0a'
            });
        });
    }

    // Função para calcular e atualizar o valor total
    function atualizarValorTotal() {
        const itensSelect = document.getElementById('listaItens');
        const itensSelecionados = Array.from(itensSelect.selectedOptions);
        let valorTotal = 0;

        // Limpa a lista de itens selecionados
        const listaItensSelecionados = document.getElementById('itensSelecionados');
        listaItensSelecionados.innerHTML = '';

        itensSelecionados.forEach(option => {
            const preco = parseFloat(option.getAttribute('data-preco')) || 0;
            const li = document.createElement('li');
            li.textContent = `${option.textContent} - R$ ${preco.toFixed(2)}`;
            listaItensSelecionados.appendChild(li);
            valorTotal += preco;
        });

        // Atualiza o valor total na interface
        const valorTotalElement = document.getElementById('valorTotal');
        valorTotalElement.textContent = valorTotal.toFixed(2);
    }

    // Função para cadastrar um novo pedido
    document.getElementById('cadastroPedido').addEventListener('submit', function (event) {
        event.preventDefault();

        const clienteId = document.getElementById('listaCliente').value;
        const itensSelect = document.getElementById('listaItens');
        const itensSelecionados = Array.from(itensSelect.selectedOptions);
        const formaPag = document.getElementById('formaPag').value;

        if (!clienteId || itensSelecionados.length === 0) {
            Swal.fire({
                title: "ERRO",
                text: "Por favor, selecione um cliente e pelo menos um item.",
                icon: "error",
                confirmButtonColor: '#5e0a0a'
            });
            return;
        }

        const itens = itensSelecionados.map(option => ({
            id: option.value,
            nome: option.textContent,
            preco: option.getAttribute('data-preco')
        }));

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
        .then(response => response.text())  // Alterado para .text()
        .then(data => {
            console.log(data);  // Imprime o retorno do servidor para verificar a resposta
            try {
                const jsonData = JSON.parse(data);  // Tenta converter para JSON
                alert(jsonData.message);
                if (jsonData.message === 'Pedido cadastrado com sucesso!') {
                    carregarPedidos();
                }
            } catch (e) {
                console.error('Erro ao parsear JSON:', e);
                Swal.fire({
                    title: "ERRO",
                    text: "Erro ao cadastrar pedido. Verifique o console para mais detalhes.",
                    icon: "error",
                    confirmButtonColor: '#5e0a0a'
                });
            }
        })
        .catch(error => {
            console.error('Erro ao cadastrar pedido:', error);
            Swal.fire({
                title: "ERRO",
                text: "Erro ao cadastrar pedido.",
                icon: "error",
                confirmButtonColor: '#5e0a0a'
            });
        });
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
        .catch(error => {
            console.error('Erro ao carregar pedidos:', error);
            Swal.fire({
                title: "ERRO",
                text: "Erro ao carregar pedidos.",
                icon: "error",
                confirmButtonColor: '#5e0a0a'
            });
        });
    }

    // Carregar os dados ao carregar a página
    carregarClientes();
    carregarItensCardapio();
    carregarPedidos();
});