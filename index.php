<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Título dinâmico -->
    <title>Gerenciamento</title> 
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto max-w-7xl p-6">
        <?php
            // Buscar laboratórios uma vez para reutilizar nos modais
            if (!isset($conn)) require_once 'database.php'; // Ensure connection is available
            $sqlLabs = "SELECT id, nome FROM t_laboratorio";
            $stmtLabs = $conn->prepare($sqlLabs);
            $stmtLabs->execute();
            $laboratorios = $stmtLabs->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <!-- Campo de Busca e Botão Filtrar -->
        <!-- Abas -->
        <div class="mb-4 border-b border-gray-200">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="tabs" role="tablist">
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg" id="produtos-tab" data-tabs-target="#produtos-content" type="button" role="tab" aria-controls="produtos" aria-selected="true">Produtos</button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="equipamentos-tab" data-tabs-target="#equipamentos-content" type="button" role="tab" aria-controls="equipamentos" aria-selected="false">Equipamentos</button>
                </li>
            </ul>
        </div>

        <!-- Conteúdo das Abas -->
        <div id="tabs-content">
            <!-- Seção Produtos -->
            <div class="" id="produtos-content" role="tabpanel" aria-labelledby="produtos-tab">
                <h1 class="w-full flex items-center text-3xl font-bold text-center text-gray-800 mb-6">
                    Produtos
                    <div class="ml-auto flex justify-center gap-4">
                        <button id="load-produtos-btn" class="bg-blue-500 hover:bg-blue-600 text-white p-3 rounded-lg transition duration-300 ease-in-out flex items-center justify-center" title="Carregar Produtos">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="load-produtos-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </button>
                        <button id="insert-produto-btn" class="bg-green-500 hover:bg-green-600 text-white p-3 rounded-lg transition duration-300 ease-in-out flex items-center justify-center" title="Inserir Produto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                </h1>

                <div class="mb-6 flex items-center">
                    <input type="text" id="search-input-produtos" placeholder="Buscar Produtos..." class="flex-grow px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button id="filter-btn-produtos" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-r-md transition duration-300 ease-in-out">Filtrar</button>
                </div>
            
                <div id="status-message-produtos" class="hidden text-center p-3 mb-4 rounded-md"></div>
                
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="overflow-x-auto max-h-[500px]">
                        <table class="min-w-full" id="produtos-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Laboratório</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fórmula</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Volume</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Massa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="produtos-table-body" class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Clique no ícone de carregamento para visualizar os dados</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Seção Equipamentos (inicialmente oculta) -->
            <div class="hidden" id="equipamentos-content" role="tabpanel" aria-labelledby="equipamentos-tab">
                <h1 class="w-full flex items-center text-3xl font-bold text-center text-gray-800 mb-6">
                    Equipamentos
                    <div class="ml-auto flex justify-center gap-4">
                         <button id="load-equipamentos-btn" class="bg-blue-500 hover:bg-blue-600 text-white p-3 rounded-lg transition duration-300 ease-in-out flex items-center justify-center" title="Carregar Equipamentos">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="load-equipamentos-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </button>
                        <button id="insert-equipamento-btn" class="bg-green-500 hover:bg-green-600 text-white p-3 rounded-lg transition duration-300 ease-in-out flex items-center justify-center" title="Inserir Equipamento">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                </h1>

                <div class="mb-6 flex items-center">
                    <input type="text" id="search-input-equipamentos" placeholder="Buscar Equipamentos..." class="flex-grow px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button id="filter-btn-equipamentos" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-r-md transition duration-300 ease-in-out">Filtrar</button>
                </div>

                <div id="status-message-equipamentos" class="hidden text-center p-3 mb-4 rounded-md"></div>

                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="overflow-x-auto max-h-[500px]">
                        <table class="min-w-full" id="equipamentos-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Laboratório</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="equipamentos-table-body" class="bg-white divide-y divide-gray-200">
                                <tr>
                                     <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Clique no ícone de carregamento para visualizar os dados</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal para inserção de Produto -->
    <div id="insert-produto-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Inserir Novo Produto</h2>
                <button id="close-insert-produto" class="text-gray-400 hover:text-gray-600 transition duration-150 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="insert-produto-form" class="mt-4">
                <div class="mb-4">
                    <label for="produto-nome" class="block text-sm font-medium text-gray-700 mb-1">Nome:</label>
                    <input type="text" id="produto-nome" name="nome" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="produto-formula" class="block text-sm font-medium text-gray-700 mb-1">Fórmula:</label>
                    <input type="text" id="produto-formula" name="formula" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="produto-volume" class="block text-sm font-medium text-gray-700 mb-1">Volume:</label>
                    <input type="number" id="produto-volume" name="volume" step="0.01" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="produto-massa" class="block text-sm font-medium text-gray-700 mb-1">Massa:</label>
                    <input type="number" id="produto-massa" name="massa" step="0.01" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="produto-quantidade" class="block text-sm font-medium text-gray-700 mb-1">Quantidade:</label>
                    <input type="number" id="produto-quantidade" name="quantidade" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="produto-laboratorio" class="block text-sm font-medium text-gray-700 mb-1">Laboratório:</label>
                    <select name="laboratorio" id="produto-laboratorio" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <?php
                            // Note: Consider fetching labs once and reusing in JS or including a separate PHP file
                            foreach ($laboratorios as $lab) {
                                echo "<option value='{$lab['id']}'>{$lab['nome']}</option>";
                            }
                        ?>
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-300 ease-in-out">Salvar</button>
            </form>
        </div>
    </div>
    
    <!-- Modal para edição de Produto -->
    <div id="edit-produto-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Editar Produto</h2>
                <button id="close-edit-produto" class="text-gray-400 hover:text-gray-600 transition duration-150 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="edit-produto-form" class="mt-4">
                <input type="hidden" id="edit-produto-id" name="id">
                <div class="mb-4">
                    <label for="edit-produto-nome" class="block text-sm font-medium text-gray-700 mb-1">Nome:</label>
                    <input type="text" id="edit-produto-nome" name="nome" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="edit-produto-formula" class="block text-sm font-medium text-gray-700 mb-1">Fórmula:</label>
                    <input type="text" id="edit-produto-formula" name="formula" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="edit-produto-volume" class="block text-sm font-medium text-gray-700 mb-1">Volume:</label>
                    <input type="number" id="edit-produto-volume" name="volume" step="0.01" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="edit-produto-massa" class="block text-sm font-medium text-gray-700 mb-1">Massa:</label>
                    <input type="number" id="edit-produto-massa" name="massa" step="0.01" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="edit-produto-quantidade" class="block text-sm font-medium text-gray-700 mb-1">Quantidade:</label>
                    <input type="number" id="edit-produto-quantidade" name="quantidade" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="edit-produto-laboratorio" class="block text-sm font-medium text-gray-700 mb-1">Laboratório:</label>
                    <select name="laboratorio" id="edit-produto-laboratorio" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <?php
                            // Reusing the fetched laboratorios
                            foreach ($laboratorios as $lab) {
                                echo "<option value='{$lab['id']}'>{$lab['nome']}</option>";
                            }
                        ?>
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-300 ease-in-out">Atualizar</button>
            </form>
        </div>
    </div>

    <!-- Modal para inserção de Equipamento -->
     <div id="insert-equipamento-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Inserir Novo Equipamento</h2>
                <button id="close-insert-equipamento" class="text-gray-400 hover:text-gray-600 transition duration-150 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="insert-equipamento-form" class="mt-4">
                <div class="mb-4">
                    <label for="equipamento-nome" class="block text-sm font-medium text-gray-700 mb-1">Nome:</label>
                    <input type="text" id="equipamento-nome" name="nome" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                 <div class="mb-4">
                    <label for="equipamento-estado" class="block text-sm font-medium text-gray-700 mb-1">Estado:</label>
                    <input type="text" id="equipamento-estado" name="estado" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="equipamento-laboratorio" class="block text-sm font-medium text-gray-700 mb-1">Laboratório:</label>
                    <select name="laboratorio" id="equipamento-laboratorio" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                       <?php
                            // Reusing the fetched laboratorios
                            foreach ($laboratorios as $lab) {
                                echo "<option value='{$lab['id']}'>{$lab['nome']}</option>";
                            }
                        ?>
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-300 ease-in-out">Salvar</button>
            </form>
        </div>
    </div>

    <!-- Modal para edição de Equipamento -->
    <div id="edit-equipamento-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Editar Equipamento</h2>
                <button id="close-edit-equipamento" class="text-gray-400 hover:text-gray-600 transition duration-150 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="edit-equipamento-form" class="mt-4">
                <input type="hidden" id="edit-equipamento-id" name="id">
                 <div class="mb-4">
                    <label for="edit-equipamento-nome" class="block text-sm font-medium text-gray-700 mb-1">Nome:</label>
                    <input type="text" id="edit-equipamento-nome" name="nome" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="edit-equipamento-estado" class="block text-sm font-medium text-gray-700 mb-1">Estado:</label>
                    <input type="text" id="edit-equipamento-estado" name="estado" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="edit-equipamento-laboratorio" class="block text-sm font-medium text-gray-700 mb-1">Laboratório:</label>
                    <select name="laboratorio" id="edit-equipamento-laboratorio" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                         <?php
                            // Reusing the fetched laboratorios
                            foreach ($laboratorios as $lab) {
                                echo "<option value='{$lab['id']}'>{$lab['nome']}</option>";
                            }
                        ?>
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-300 ease-in-out">Atualizar</button>
            </form>
        </div>
    </div>
    
    <script>
        // --- Elementos DOM Globais ---
        const tabs = document.getElementById('tabs');
        const tabsContent = document.getElementById('tabs-content');
        const produtosTab = document.getElementById('produtos-tab');
        const equipamentosTab = document.getElementById('equipamentos-tab');
        const produtosContent = document.getElementById('produtos-content');
        const equipamentosContent = document.getElementById('equipamentos-content');

        // --- Elementos DOM Produtos ---
        const loadProdutosBtn = document.getElementById('load-produtos-btn');
        const insertProdutoBtn = document.getElementById('insert-produto-btn');
        const loadProdutosIcon = document.getElementById('load-produtos-icon');
        const produtosTableBody = document.getElementById('produtos-table-body');
        const statusMessageProdutos = document.getElementById('status-message-produtos');
        const insertProdutoModal = document.getElementById('insert-produto-modal');
        const editProdutoModal = document.getElementById('edit-produto-modal');
        const closeInsertProduto = document.getElementById('close-insert-produto');
        const closeEditProduto = document.getElementById('close-edit-produto');
        const insertProdutoForm = document.getElementById('insert-produto-form');
        const editProdutoForm = document.getElementById('edit-produto-form');
        const searchInputProdutos = document.getElementById('search-input-produtos');
        const filterBtnProdutos = document.getElementById('filter-btn-produtos');

        // --- Elementos DOM Equipamentos ---
        const loadEquipamentosBtn = document.getElementById('load-equipamentos-btn');
        const insertEquipamentoBtn = document.getElementById('insert-equipamento-btn');
        const loadEquipamentosIcon = document.getElementById('load-equipamentos-icon');
        const equipamentosTableBody = document.getElementById('equipamentos-table-body');
        const statusMessageEquipamentos = document.getElementById('status-message-equipamentos');
        const insertEquipamentoModal = document.getElementById('insert-equipamento-modal');
        const editEquipamentoModal = document.getElementById('edit-equipamento-modal');
        const closeInsertEquipamento = document.getElementById('close-insert-equipamento');
        const closeEditEquipamento = document.getElementById('close-edit-equipamento');
        const insertEquipamentoForm = document.getElementById('insert-equipamento-form');
        const editEquipamentoForm = document.getElementById('edit-equipamento-form');
        const searchInputEquipamentos = document.getElementById('search-input-equipamentos');
        const filterBtnEquipamentos = document.getElementById('filter-btn-equipamentos');

        // --- Constantes ---
        const API_PRODUTOS_URL = 'api.php';
        const API_EQUIPAMENTOS_URL = 'api_equipamentos.php'; // Novo endpoint

        // --- Funções Utilitárias ---
        function showMessage(element, message, type) {
            element.textContent = message;
            element.classList.remove('hidden', 'bg-green-100', 'text-green-800', 'bg-red-100', 'text-red-800');
            
            if (type === 'success') {
                element.classList.add('bg-green-100', 'text-green-800');
            } else {
                element.classList.add('bg-red-100', 'text-red-800');
            }
            
            setTimeout(() => {
                element.classList.add('hidden');
            }, 3000);
        }

        function setupModal(modalElement, openBtn, closeBtn) {
            if (openBtn) {
                openBtn.addEventListener('click', () => {
                    modalElement.classList.remove('hidden');
                });
            }
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    modalElement.classList.add('hidden');
                });
            }
            window.addEventListener('click', (event) => {
                if (event.target === modalElement) {
                    modalElement.classList.add('hidden');
                }
            });
        }

        // --- Lógica de Abas ---
        function switchTab(targetTab, targetContent) {
            // Desativa todas as abas e conteúdos
            tabs.querySelectorAll('[role="tab"]').forEach(tab => {
                tab.classList.remove('border-blue-500', 'text-blue-600');
                tab.classList.add('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
                tab.setAttribute('aria-selected', 'false');
            });
            tabsContent.querySelectorAll('[role="tabpanel"]').forEach(content => {
                content.classList.add('hidden');
            });

            // Ativa a aba e conteúdo selecionados
            targetTab.classList.add('border-blue-500', 'text-blue-600');
            targetTab.classList.remove('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
            targetTab.setAttribute('aria-selected', 'true');
            targetContent.classList.remove('hidden');
        }

        produtosTab.addEventListener('click', () => switchTab(produtosTab, produtosContent));
        equipamentosTab.addEventListener('click', () => {
             switchTab(equipamentosTab, equipamentosContent);
             // Carrega dados dos equipamentos ao mudar para a aba pela primeira vez
             if (equipamentosTableBody.querySelector('td[colspan="5"]')) { 
                 fetchEquipamentos();
             }
        });

        // --- Lógica Produtos ---
        async function fetchProdutos() {
            const searchTerm = searchInputProdutos.value.trim();
            let url = API_PRODUTOS_URL;
            if (searchTerm) {
                url += `?search=${encodeURIComponent(searchTerm)}`;
            }

            try {
                loadProdutosIcon.classList.add('animate-spin');
                const response = await fetch(url);
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const data = await response.json();
                loadProdutosIcon.classList.remove('animate-spin');
                
                if (!data || data.length === 0) {
                    produtosTableBody.innerHTML = '<tr><td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Nenhum produto encontrado</td></tr>';
                    return;
                }
                
                let html = '';
                data.forEach(item => {
                    html += `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.id}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${item.nome}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${item.nome_laboratorio || 'N/A'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${item.formula}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${item.volume}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${item.massa}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${item.quantidade}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button class="edit-produto-item bg-amber-500 hover:bg-amber-600 text-white px-3 py-1 rounded-md text-xs transition duration-300 ease-in-out" 
                                    data-id="${item.id}" 
                                    data-nome="${item.nome}" 
                                    data-id_laboratorio="${item.id_laboratorio}"
                                    data-formula="${item.formula}" 
                                    data-volume="${item.volume}"
                                    data-massa="${item.massa}"
                                    data-quantidade="${item.quantidade}">
                                    Editar
                                </button>
                                <button class="delete-produto-item bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-xs transition duration-300 ease-in-out" data-id="${item.id}">Excluir</button>
                            </td>
                        </tr>
                    `;
                });
                
                produtosTableBody.innerHTML = html;
                setupProdutoActionButtons();

            } catch (error) {
                loadProdutosIcon.classList.remove('animate-spin');
                showMessage(statusMessageProdutos, `Erro ao carregar produtos: ${error.message}`, 'error');
                console.error(error);
            }
        }

        function setupProdutoActionButtons() {
             document.querySelectorAll('.edit-produto-item').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.getElementById('edit-produto-id').value = this.getAttribute('data-id');
                    document.getElementById('edit-produto-nome').value = this.getAttribute('data-nome');
                    document.getElementById('edit-produto-formula').value = this.getAttribute('data-formula');
                    document.getElementById('edit-produto-volume').value = this.getAttribute('data-volume');
                    document.getElementById('edit-produto-massa').value = this.getAttribute('data-massa');
                    document.getElementById('edit-produto-quantidade').value = this.getAttribute('data-quantidade');
                    document.getElementById('edit-produto-laboratorio').value = this.getAttribute('data-laboratorio');
                    editProdutoModal.classList.remove('hidden');
                });
            });
                
            document.querySelectorAll('.delete-produto-item').forEach(btn => {
                btn.addEventListener('click', async function() {
                    if (confirm('Tem certeza que deseja excluir este produto?')) {
                        const id = this.getAttribute('data-id');
                        try {
                            const response = await window.fetch(`${API_PRODUTOS_URL}?id=${id}`, { method: 'DELETE' });
                            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                            const data = await response.json();
                            
                            if (data.success) {
                                showMessage(statusMessageProdutos, 'Produto excluído com sucesso!', 'success');
                                fetchProdutos(); // Recarregar a tabela
                            } else {
                                showMessage(statusMessageProdutos, data.message || 'Erro ao excluir produto', 'error');
                            }
                        } catch (error) {
                            showMessage(statusMessageProdutos, `Erro de conexão: ${error.message}`, 'error');
                            console.error(error);
                        }
                    }
                });
            });
        }

        insertProdutoForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = {
                nome: document.getElementById('produto-nome').value,
                formula: document.getElementById('produto-formula').value,
                volume: document.getElementById('produto-volume').value,
                massa: document.getElementById('produto-massa').value,
                quantidade: document.getElementById('produto-quantidade').value,
                id_laboratorio: document.getElementById('produto-laboratorio').value
            };
            
            try {
                const response = await window.fetch(API_PRODUTOS_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const data = await response.json();
                
                if (data.id) {
                    showMessage(statusMessageProdutos, 'Produto inserido com sucesso!', 'success');
                    insertProdutoModal.classList.add('hidden');
                    insertProdutoForm.reset();
                    fetchProdutos(); // Recarregar a tabela
                } else {
                    showMessage(statusMessageProdutos, data.message || 'Erro ao inserir produto', 'error');
                }
            } catch (error) {
                showMessage(statusMessageProdutos, `Erro de conexão: ${error.message}`, 'error');
                console.error(error);
            }
        });

        editProdutoForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = {
                id: document.getElementById('edit-produto-id').value,
                nome: document.getElementById('edit-produto-nome').value,
                formula: document.getElementById('edit-produto-formula').value,
                volume: document.getElementById('edit-produto-volume').value,
                massa: document.getElementById('edit-produto-massa').value,
                quantidade: document.getElementById('edit-produto-quantidade').value,
                id_laboratorio: document.getElementById('edit-produto-laboratorio').value
            };
            
            try {
                const response = await window.fetch(API_PRODUTOS_URL, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });
                 if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const data = await response.json();
                
                if (data.success) {
                    showMessage(statusMessageProdutos, 'Produto atualizado com sucesso!', 'success');
                    editProdutoModal.classList.add('hidden');
                    fetchProdutos(); // Recarregar a tabela
                } else {
                     showMessage(statusMessageProdutos, data.message || 'Erro ao atualizar produto', 'error');
                }
            } catch (error) {
                 showMessage(statusMessageProdutos, `Erro de conexão: ${error.message}`, 'error');
                console.error(error);
            }
        });

        // --- Lógica Equipamentos ---
        async function fetchEquipamentos() {
            const searchTerm = searchInputEquipamentos.value.trim();
            let url = API_EQUIPAMENTOS_URL;
            if (searchTerm) {
                url += `?search=${encodeURIComponent(searchTerm)}`;
            }

            try {
                loadEquipamentosIcon.classList.add('animate-spin');
                const response = await fetch(url);
                 if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const data = await response.json();
                loadEquipamentosIcon.classList.remove('animate-spin');

                if (!data || data.length === 0) {
                    equipamentosTableBody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Nenhum equipamento encontrado</td></tr>';
                    return;
                }

                let html = '';
                data.forEach(item => {
                    html += `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.id}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${item.nome}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${item.nome_laboratorio || 'N/A'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${item.estado}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button class="edit-equipamento-item bg-amber-500 hover:bg-amber-600 text-white px-3 py-1 rounded-md text-xs transition duration-300 ease-in-out" 
                                    data-id="${item.id}" 
                                    data-nome="${item.nome}" 
                                    data-laboratorio="${item.id_laboratorio}" 
                                    data-estado="${item.estado}">
                                    Editar
                                </button>
                                <button class="delete-equipamento-item bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-xs transition duration-300 ease-in-out" data-id="${item.id}">Excluir</button>
                            </td>
                        </tr>
                    `;
                });

                equipamentosTableBody.innerHTML = html;
                setupEquipamentoActionButtons();

            } catch (error) {
                loadEquipamentosIcon.classList.remove('animate-spin');
                showMessage(statusMessageEquipamentos, `Erro ao carregar equipamentos: ${error.message}`, 'error');
                console.error(error);
            }
        }

        function setupEquipamentoActionButtons() {
            document.querySelectorAll('.edit-equipamento-item').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.getElementById('edit-equipamento-id').value = this.getAttribute('data-id');
                    document.getElementById('edit-equipamento-nome').value = this.getAttribute('data-nome');
                    document.getElementById('edit-equipamento-estado').value = this.getAttribute('data-estado');
                    document.getElementById('edit-equipamento-laboratorio').value = this.getAttribute('data-laboratorio');
                    editEquipamentoModal.classList.remove('hidden');
                });
            });

            document.querySelectorAll('.delete-equipamento-item').forEach(btn => {
                btn.addEventListener('click', async function() {
                    if (confirm('Tem certeza que deseja excluir este equipamento?')) {
                        const id = this.getAttribute('data-id');
                        try {
                            const response = await window.fetch(`${API_EQUIPAMENTOS_URL}?id=${id}`, { method: 'DELETE' });
                            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                            const data = await response.json();

                            if (data.success) {
                                showMessage(statusMessageEquipamentos, 'Equipamento excluído com sucesso!', 'success');
                                fetchEquipamentos(); // Recarregar a tabela
                            } else {
                                showMessage(statusMessageEquipamentos, data.message || 'Erro ao excluir equipamento', 'error');
                            }
                        } catch (error) {
                             showMessage(statusMessageEquipamentos, `Erro de conexão: ${error.message}`, 'error');
                            console.error(error);
                        }
                    }
                });
            });
        }

        insertEquipamentoForm.addEventListener('submit', async (e) => {
            e.preventDefault();
             const formData = {
                nome: document.getElementById('equipamento-nome').value,
                estado: document.getElementById('equipamento-estado').value,
                id_laboratorio: document.getElementById('equipamento-laboratorio').value 
            };

            try {
                const response = await window.fetch(API_EQUIPAMENTOS_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });
                 if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const data = await response.json();

                if (data.id) {
                    showMessage(statusMessageEquipamentos, 'Equipamento inserido com sucesso!', 'success');
                    insertEquipamentoModal.classList.add('hidden');
                    insertEquipamentoForm.reset();
                    fetchEquipamentos(); // Recarregar a tabela
                } else {
                     showMessage(statusMessageEquipamentos, data.message || 'Erro ao inserir equipamento', 'error');
                }
            } catch (error) {
                 showMessage(statusMessageEquipamentos, `Erro de conexão: ${error.message}`, 'error');
                console.error(error);
            }
        });

        editEquipamentoForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = {
                id: document.getElementById('edit-equipamento-id').value,
                nome: document.getElementById('edit-equipamento-nome').value,
                estado: document.getElementById('edit-equipamento-estado').value,
                id_laboratorio: document.getElementById('edit-equipamento-laboratorio').value
            };

            try {
                const response = await window.fetch(API_EQUIPAMENTOS_URL, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const data = await response.json();

                if (data.success) {
                    showMessage(statusMessageEquipamentos, 'Equipamento atualizado com sucesso!', 'success');
                    editEquipamentoModal.classList.add('hidden');
                    fetchEquipamentos(); // Recarregar a tabela
                } else {
                     showMessage(statusMessageEquipamentos, data.message ||'Erro ao atualizar equipamento', 'error');
                }
            } catch (error) {
                showMessage(statusMessageEquipamentos, `Erro de conexão: ${error.message}`, 'error');
                console.error(error);
            }
        });


        // --- Inicialização ---
        document.addEventListener('DOMContentLoaded', () => {
            // Configurar modais de Produtos
            setupModal(insertProdutoModal, insertProdutoBtn, closeInsertProduto);
            setupModal(editProdutoModal, null, closeEditProduto); // Botão de abrir é dinâmico na tabela
            
            // Configurar modais de Equipamentos
            setupModal(insertEquipamentoModal, insertEquipamentoBtn, closeInsertEquipamento);
            setupModal(editEquipamentoModal, null, closeEditEquipamento); // Botão de abrir é dinâmico na tabela

            // Configurar botões de carregamento e filtro
            loadProdutosBtn.addEventListener('click', fetchProdutos);
            filterBtnProdutos.addEventListener('click', fetchProdutos);
            loadEquipamentosBtn.addEventListener('click', fetchEquipamentos);
            filterBtnEquipamentos.addEventListener('click', fetchEquipamentos);

            // Carregar produtos ao iniciar a página (aba padrão)
            fetchProdutos(); 
        });

    </script>
</body>
</html> 