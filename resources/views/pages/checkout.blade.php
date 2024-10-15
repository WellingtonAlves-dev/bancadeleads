@extends("templates.list")
@section("content")
    <style>
        .container-central {
            max-width: 100%;
            margin: 20px auto;
            padding: 15px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header-icon {
            height: 36px;
            margin-left: 10px;
        }
        h2 {
            font-size: 1.5rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .form-group label {
            font-size: 1rem;
            color: #333;
        }
        .cta-btn, .cancel-btn {
            display: block;
            width: 100%;
            margin-top: 15px;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }
        .cta-btn {
            background-color: #28a745;
            color: white;
        }
        .cta-btn:hover {
            background-color: #218838;
        }
        .cancel-btn {
            background-color: #dc3545;
            color: white;
        }
        .cancel-btn:hover {
            background-color: #c82333;
        }
        .copy-text {
            font-size: 0.9rem;
            color: #666;
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="text"] {
            font-size: 1rem;
            padding: 10px;
        }
        small.form-text {
            font-size: 0.8rem;
        }
        @media (min-width: 768px) {
            .container {
                max-width: 400px;
                padding: 20px;
            }
            h2 {
                font-size: 2rem;
            }
            .cta-btn, .cancel-btn {
                font-size: 18px;
            }
            input[type="text"] {
                font-size: 1.2rem;
            }
        }
    </style>
    <!-- Page Heading -->
<div class="d-flex justify-content-between mb-2">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{url("/")}}">Área do Cliente</a></li>
          <li class="breadcrumb-item active" aria-current="page">Adicionar Crédito</li>
        </ol>
    </nav>
</div>
    <div class="container container-central">
        <h2>
            Adicionar Crédito
        </h2>

        <p class="copy-text">
            Adicione o crédito que desejar a sua conta
            para poder aproveitar as nossas leads disponíveis na plataforma.
        </p>

        <div class="form-group">
            <label for="valorAdd">Insira o valor desejado</label>
            <input type="text" class="form-control" id="valorAdd" placeholder="Ex. R$ 50,00">
            {{-- <small class="form-text text-muted">O valor mínimo é R$ 25,00.</small> --}}
        </div>

        <button class="cta-btn" id="confirmPay">
            Confirmar Compra
        </button>
        <button class="cancel-btn" onclick="window.history.back()">
            Cancelar
        </button>

        <div id="wallet_container"></div>
    </div>
@endsection
