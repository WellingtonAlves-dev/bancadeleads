<style>
  /* Estilo do Modal de Alerta */
.modal_alert {
    background-color: rgba(0, 0, 0, 0.5); /* Fundo semi-transparente */
}

.modal_dialog_small {
    max-width: 90%; /* Largura máxima do modal em dispositivos móveis */
    margin: 30px auto; /* Centraliza na tela */
}

.modal_content_alert {
    border-radius: 10px; /* Bordas arredondadas */
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); /* Sombra suave */
}

/* Cabeçalho do Modal */
.modal_header_alert {
    border-bottom: none; /* Remove a borda inferior */
    justify-content: space-between; /* Espaço entre título e botão de fechar */
}

/* Estilo do Título */
.modal_title_alert {
    font-size: 1.5rem; /* Tamanho maior */
    color: #333; /* Cor do texto */
}

/* Botão de Fechar */
.modal_close_button {
    font-size: 1.5rem; /* Tamanho maior */
}

/* Corpo do Modal */
.modal_body_alert {
    padding: 20px; /* Aumenta o padding */
}

/* Mensagem de Alerta */
.alert_message_style {
    border-radius: 8px; /* Bordas arredondadas */
    padding: 15px; /* Padding confortável */
}

/* Rodapé do Modal */
.modal_footer_alert {
    border-top: none; /* Remove a borda superior */
    justify-content: flex-end; /* Alinha os botões à direita */
}

/* Botão de Fechar */
.modal_btn_close {
    background-color: #ccc; /* Cor de fundo do botão de fechar */
    color: #333; /* Cor do texto */
    border-radius: 5px; /* Bordas arredondadas */
    padding: 10px 15px; /* Padding ajustado */
    font-size: 1rem; /* Tamanho do texto */
}

/* Media Queries para Ajustes em Dispositivos Móveis */
@media (max-width: 768px) {
    .modal_title_alert {
        font-size: 1.25rem; /* Ajusta o tamanho do título para telas menores */
    }
    .modal_body_alert {
        padding: 15px; /* Reduz o padding em telas menores */
    }
    .modal_btn_close {
        width: 100%; /* Botão ocupa toda a largura */
        margin-top: 10px; /* Espaçamento entre os botões */
    }
}

</style>
<div class="modal modal_alert" tabindex="-1" role="dialog" id="modal_alert">
  <div class="modal-dialog modal_dialog_small" role="document">
      <div class="modal-content modal_content_alert">
          <div class="modal-header modal_header_alert">
              <h5 class="modal-title modal_title_alert">{{$title}}</h5>
              <button type="button" class="close modal_close_button" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body modal_body_alert">
              <input type="hidden" id="corretor_id_alert" value="{{$user_id ?? null}}"/>
              <input type="hidden" id="corretor_alert" value="{{$user ?? null}}"/>
              <div class="alert {{empty($alert_type) ? "bg-danger" : $alert_type}} alert_message_style">
                  {{$msg}}
              </div>
          </div>
          <div class="modal-footer modal_footer_alert">
              <button type="button" class="btn btn_secondary modal_btn_close" data-dismiss="modal">Fechar</button>
          </div>
      </div>
  </div>
</div>
