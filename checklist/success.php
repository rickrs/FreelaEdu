<?php
// Aguarda 5 segundos antes de redirecionar para o form.php
header("refresh:5;url=form.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sucesso</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f4f6f9;
        }

        .success-container {
            text-align: center;
        }

        .logo-container img {
            max-width: 200px;
            height: auto;
        }

        .message {
            margin-top: 20px;
            font-size: 24px;
            color: #333;
        }

        .redirect-message {
            margin-top: 10px;
            font-size: 18px;
            color: #666;
        }

        @media (max-width: 768px) {
            .logo-container img {
                max-width: 150px;
            }

            .message {
                font-size: 20px;
            }

            .redirect-message {
                font-size: 16px;
            }
        }
    </style>
</head>
<body class="hold-transition">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-sm-8 col-xs-12">
            <div class="success-container">
                <div class="logo-container">
                    <img src="https://hisense.com.br/wp-content/uploads/2024/03/Vector2.svg" alt="Hisense Logo">
                </div>
                <div class="message">Formulário enviado com sucesso!</div>
                <div class="redirect-message">Você será redirecionado em 5 segundos...</div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
