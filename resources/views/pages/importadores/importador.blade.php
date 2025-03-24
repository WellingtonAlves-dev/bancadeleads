<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importar CSV</title>
</head>
<body style="font-family: Arial, sans-serif; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; margin: 0; background-color: #f9f9f9;">

    <h1 style="color: #333;">Importar Arquivo CSV</h1>

    @if(session('success'))
        <p style="color: green; background-color: #d4edda; padding: 10px; border-radius: 5px; width: fit-content;">{{ session('success') }}</p>
    @endif

    <form id="uploadForm" action="{{ url('/importar') }}" method="POST" enctype="multipart/form-data" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
        @csrf
        <label style="font-weight: bold; margin-right: 10px;">Tabela: </label>
        <select name="tabela" style="padding: 5px; border: 1px solid #ccc; border-radius: 5px;">
            <option value="users">Usuários</option>
            <option value="leads">Leads</option>
        </select>
        <input type="file" name="arquivo" required style="margin-top: 10px; display: block;">
        <button type="submit" id="submitButton" style="background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; margin-top: 10px;">
            Importar
        </button>
        
        <!-- Barra de progresso oculta inicialmente -->
        <div id="progressContainer" style="width: 100%; background-color: #ddd; border-radius: 5px; overflow: hidden; margin-top: 10px; display: none;">
            <div id="progressBar" style="width: 0%; height: 10px; background-color: #007bff; transition: width 0.4s;"></div>
        </div>

        <p id="progressText" style="color: #333; font-size: 14px; display: none;">Processando...</p>
    </form>

    @if(session("logs") && is_array(session("logs")))
        <table style="width: 80%; max-width: 600px; border-collapse: collapse; margin-top: 20px; border: 1px solid #ddd; background: white; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); border-radius: 5px; overflow: hidden;">
            <thead>
                <tr style="background-color: #007bff; color: white; border-bottom: 2px solid #ccc;">
                    <th style="padding: 10px; text-align: left; border-right: 1px solid #ddd;">Linha</th>
                    <th style="padding: 10px; text-align: left;">Mensagem de erro</th>
                </tr>
            </thead>
            <tbody>
            @foreach(session("logs") as $linha => $log)
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 8px; border-right: 1px solid #ddd;">{{ $linha }}</td>
                    <td style="padding: 8px;">{{ $log }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

    <script>
        document.getElementById('uploadForm').addEventListener('submit', function() {
            let button = document.getElementById('submitButton');
            let progressBar = document.getElementById('progressBar');
            let progressContainer = document.getElementById('progressContainer');
            let progressText = document.getElementById('progressText');

            button.disabled = true;
            button.innerText = "Importando...";
            progressContainer.style.display = "block";
            progressText.style.display = "block";

            let progress = 0;
            let interval = setInterval(function() {
                if (progress >= 90) {
                    clearInterval(interval);
                } else {
                    progress += 10;
                    progressBar.style.width = progress + "%";
                }
            }, 500);
        });
    </script>

</body>
</html>
