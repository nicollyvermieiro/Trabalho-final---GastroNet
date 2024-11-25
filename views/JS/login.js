$(document).ready(function() {
    // Função para realizar o login
    $('#loginForm').submit(function(event) {
        event.preventDefault();
        var login = $('#login').val();
        var senha = $('#senha').val();

        $.ajax({
            url: '../controllers/funcionarioController.php',
            method: 'POST',
            data: {
                acao: 'login',
                login: login,
                senha: senha
            },
            success: function(response) {
                try {
                    var data = JSON.parse(response);
                    if (data.success) {
                        // Salva as informações do funcionário no localStorage
                        localStorage.setItem('loggedIn', true);
                        localStorage.setItem('funcionarioId', data.funcionario.id);
                        window.location.href = 'index.html'; // Redireciona para a página principal
                    } else {
                        alert(data.message);
                    }
                } catch (e) {
                    Swal.fire({
                        title: "ERRO",
                        text: "Erro na resposta com o servidor.",
                        icon: "error",
                        confirmButtonColor: '#5e0a0a'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: "ERRO",
                    text: "Erro na comunicação com o servidor.",
                    icon: "error",
                    confirmButtonColor: '#5e0a0a'
                });
            }
        });

        window.location.href = 'index.html'; // Redireciona para a página principal
    });
});