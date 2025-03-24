@extends("templates.list")
@section("content")
    <style>
        /* Estilos Gerais */
        .container-central {
            max-width: 500px;
            margin: 40px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h2 {
            font-size: 2rem;
            color: #007fff;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .header h2 img {
            height: 40px;
            margin-right: 10px;
        }

        .header p {
            font-size: 1rem;
            color: #555;
            margin: 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-size: 1rem;
            color: #333;
            font-weight: 500;
            margin-bottom: 10px;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #007fff;
            outline: none;
        }

        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 25px;
        }

        .btn {
            flex: 1;
            padding: 12px;
            font-size: 1rem;
            font-weight: 500;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-align: center;
        }

        .btn-primary {
            background-color: #007fff;
            color: white;
        }

        .btn-primary:hover {
            background-color: #005bb5;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .wallet-container {
            margin-top: 20px;
            text-align: center;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .container-central {
                padding: 20px;
            }

            .header h2 {
                font-size: 1.5rem;
            }

            .btn-group {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>

    {{-- <!-- Breadcrumb (mantido igual) -->
    <div class="d-flex justify-content-between mb-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">Área do Cliente</a></li>
                <li class="breadcrumb-item active" aria-current="page">Recarregar Saldo</li>
            </ol>
        </nav>
    </div>
 --}}
    <!-- Conteúdo Principal -->
    <div class="container container-central">
        <!-- Header -->
        <div class="header">
            <h2>
                Recarregar Saldo
            </h2>
            <p>
                Adicione créditos à sua conta para acessar leads exclusivas e impulsionar seus negócios.
            </p>
        </div>

        <!-- Formulário -->
        <div class="form-group">
            <label for="valorAdd">Insira o valor desejado</label>
            <input type="text" class="form-control" id="valorAdd" placeholder="Ex. R$ 50,00">
            {{-- <small class="form-text text-muted">Valor mínimo: R$ 25,00</small> --}}
        </div>

        <!-- Botões -->
        <div class="btn-group">
            <button class="btn btn-primary" id="confirmPay">
                Confirmar Compra
            </button>
            <button class="btn btn-secondary" onclick="window.history.back()">
                Cancelar
            </button>
        </div>

        <!-- Wallet Container -->
        <div class="wallet-container" id="wallet_container"></div>
    </div>
@endsection