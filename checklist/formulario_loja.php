<?php
session_start();
include('admin/db.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliação de loja</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Navbar -->
    <nav class="navbar navbar-light bg-light">
        <div class="container align-items-baseline">
            <div class="navbar-brand py-4">
                <img src="images/hisense.svg" alt="">
            </div>
            <div class="d-flex align-items-baseline">
                <h5 class="h6 mr-2 font-weight-bold text-dark"><?php echo htmlspecialchars($_SESSION['nome_usuario']); ?></h5>
                <div class="user-logo bg-info">
                    <img src="vendor/fontawesome-free/svgs/regular/user.svg" width="28" alt="">
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Image -->
    <div class="text-center hero-container">
        <img src="images/checklist.png" alt="Hero Image" class="hero-image">
    </div>

    <!-- Breadcrumb -->
    <nav class="bg-light pt-3 pb-1" aria-label="breadcrumb">
        <div class="container px-2">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="formulario_categorias.php">Categorias</a></li>
                <li class="breadcrumb-item active text-dark" aria-current="page">Avaliação de Loja</li>
            </ol>
        </div>
    </nav>

    <div class="container my-5">
        <div class="mb-5">
            <h1 class="text-dark font-weight-bold">Avaliação da Loja</h1>
            <p class="">Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome_usuario']); ?>! Escolha uma categoria para iniciar o checklist</p>
        </div>

        <div class="alert bg-info d-lg-flex text-center text-lg-left align-items-center py-3 rounded-lg">
            <div class='p-4'>
                <img class="" src="images/shop.png" width="128">
            </div>
            <div class="text-white h4 ml-lg-5">
                <div class="mb-2">
                    <strong>Loja:</strong> FastShop <br>
                </div>
                <div class="mb-2">
                    <strong>Localização:</strong> Alphaville <br>
                </div>
            </div>
        </div>

        <form style="margin-top: -35px;" action="" method="POST">
            <div class="container p-4 rounded bg-light rounded-lg">
                <h4 class="text-dark py-5">Preencha as informações abaixo:</h4>

                <h5 class="mb-4">1. Status Concorrentes</h5>
                <div class="row border p-3 py-4 mx-1 rounded-lg">

                    <!-- Seleção de Concorrente -->
                    <div class="form-group col-md-6 col-sm-12">
                        <label for="">Selecione o Concorrente:</label>
                        <select id="" name="" class="form-control">
                            <option value="">-- Escolha um concorrente --</option>
                            <option class='' value=''>Opção 1</option>
                            <option class='' value=''>Opção 2</option>
                            <option class='' value=''>Opção 3</option>
                        </select>
                    </div>

                    <!-- Seleção de Categoria -->
                    <div class="form-group col-md-6 col-sm-12">
                        <label for="">Selecione a Categoria:</label>
                        <select id="" name="" class="form-control">
                            <option value="">-- Escolha uma Categoria --</option>
                            <option class='' value=''>Opção 1</option>
                            <option class='' value=''>Opção 2</option>
                            <option class='' value=''>Opção 3</option>
                        </select>
                    </div>

                    <div class="form-group col-md-6 col-sm-12 mt-2">
                        <div>
                            <button class="btn btn-info btn-circle pb-1">
                                -
                            </button>
                            <span class="px-2">
                                0 produtos
                            </span>
                            <button class="btn btn-info btn-circle pb-1">
                                +
                            </button>
                        </div>
                        <div class="mt-3">
                            <span class="text-info" role="button">+ Adicionar categoria</span>
                        </div>
                    </div>
                </div>
                <div class="ml-1 mt-3">
                    <span class="text-info" role="button">+ Adicionar concorrente</span>
                </div>

                <hr class="mt-5">

                <h5 class="mt-5 mb-5">2. Espaço Hisense</h5>

                <div class="row mb-5">
                    <div class="col-md-5 d-flex flex-column">
                        <label class="mb-3" for="">Como você avalia o espaço Hisense?</label>
                        <input type="range" min="1" max="5" value="0" class="slider" id="myRange">
                        <p id="feedback" class="mt-3 text-info">Normal</p>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6 col-sm-12">
                        <span>Há espaço destaque Hisense na loja?</span>
                        <div class="d-flex mt-2">
                            <button class="btn btn-info px-4">Sim</button>
                            <button class="btn btn-secondary px-4 ml-2">Não</button>
                        </div>
                    </div>

                    <div class="form-group col-md-6 col-sm-12">
                        <span>Há lugar vazio no espaço destaque Hisense?</span>
                        <div class="d-flex mt-2">
                            <button class="btn btn-info px-4">Sim</button>
                            <button class="btn btn-secondary px-4 ml-2">Não</button>
                        </div>
                    </div>
                </div>

                <div class="row mt-lg-4">
                    <div class="form-group col-md-6 col-sm-12">
                        <span>O espaço Hisense é mais forte que o dos concorrentes?</span>
                        <div class="d-flex mt-2">
                            <button class="btn btn-info px-4">Sim</button>
                            <button class="btn btn-secondary px-4 ml-2">Não</button>
                        </div>
                    </div>

                    <div class="form-group col-md-6 col-sm-12">
                        <span>Tem promotor Hisense na loja?</span>
                        <div class="d-flex mt-2">
                            <button class="btn btn-info px-4">Sim</button>
                            <button class="btn btn-secondary px-4 ml-2">Não</button>
                        </div>
                    </div>
                </div>

                <div class="row mt-lg-4">
                    <div class="form-group col-md-6 col-sm-12">
                        <span>Promotor tem algum pedido pendente ainda sem resposta?</span>
                        <div class="d-flex mt-2">
                            <button class="btn btn-info px-4">Sim</button>
                            <button class="btn btn-secondary px-4 ml-2">Não</button>
                        </div>
                    </div>

                    <div class="form-group col-md-6 col-sm-12">
                        <span>Os funcionários da loja estão cientes dos produtos e promoções Hisense?</span>
                        <div class="d-flex mt-2">
                            <button class="btn btn-info px-4">Sim</button>
                            <button class="btn btn-secondary px-4 ml-2">Não</button>
                        </div>
                    </div>

                    <div class="form-group col-md-12 mt-4">
                        <label for="">Problemas ou sugestões:</label>
                        <textarea class="form-control" id="" rows="8"></textarea>
                    </div>
                </div>

                <div class="mt-5 mb-3">
                    <button type="submit" class="btn-submit btn btn-info font-weight-bold px-5 py-3">Finalizar Avaliação</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Rodapé -->
    <div class="text-center bg-dark text-white py-5">
        <p>© Hisense 2025 - Hisense Gorenje do Brasil Importação e Comércio de Eletrodoméstico LTDA.</p>
    </div>

    <!-- Input Range (Feedbacks) -->
    <script>
        const feedbackTexts = [
            "Normal", // 1
            "Notável", // 2
            "Chama a atenção", // 3
            "Elegante", // 4
            "Premium e Aspiracional", // 5
        ];

        const rangeInput = document.getElementById("myRange");
        const feedbackText = document.getElementById("feedback");

        rangeInput.addEventListener("input", () => {
            const value = rangeInput.value; // Pega o valor do range
            feedbackText.textContent = feedbackTexts[value - 1]; // Atualiza o texto
        });
    </script>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>