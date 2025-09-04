<?php
require_once __DIR__ . '/../app/Controller/PontoController.php';


$controller = new PontoController();

$page = $_GET['page'] ?? 'listar';

switch ($page) {
    case 'ponto': // Detalhes de um ponto
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id > 0) {
            $ponto = $controller->buscarPorId($id);

            if ($ponto) {
                include __DIR__ . '/../app/View/gestor/ponto.php';
            } else {
                echo "<h2>Ponto não encontrado!</h2>";
            }
        } else {
            echo "<h2>ID inválido!</h2>";
        }
        break;

    case 'pre_selecao': // formulário
        require __DIR__ . '/../app/View/gestor/pre_selecao.php';
        break;

    case 'pre_selecao_gerar': // resultado
        require_once __DIR__ . '/../app/Controller/PreSelecaoController.php';
        $preSelecaoController = new PreSelecaoController();
        $preSelecaoController->gerar();
        break;

    default: // Listar pontos
        $filtros = [
            'situacao' => $_GET['situacao'] ?? '',
            'regiao'   => $_GET['regiao'] ?? '',
            'tipo'     => $_GET['tipo'] ?? '',
            'cidade'   => $_GET['cidade'] ?? '',
        ];

        $paginaAtual = max(1, (int) ($_GET['pagina'] ?? 1));
        $limite = 10;

        $dados = $controller->listar($filtros, $paginaAtual, $limite);
        $pontos = $dados['pontos'];
        $total = $dados['total'];
        $limite = $dados['limite'];
        $pagina = $dados['pagina'];

        $totalPaginas = ceil($total / $limite);

        include __DIR__ . '/../app/View/gestor/listar_ponto.php';
        break;
}
