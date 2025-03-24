<style>
/* Estilo do Modal */
.modal_confirm_purchase {
    background-color: rgba(0, 0, 0, 0.5); /* Fundo semi-transparente */
}

.modal_dialog_large {
    max-width: 90%; /* Largura máxima do modal em dispositivos móveis */
    margin: 30px auto; /* Centraliza na tela */
}

.modal_content_clean {
    border-radius: 15px; /* Bordas arredondadas */
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2); /* Sombra suave */
}

/* Cabeçalho do Modal */
.modal_header_style {
    border-bottom: none; /* Remove a borda inferior */
    justify-content: space-between; /* Espaço entre título e botão de fechar */
}

/* Estilo do Título */
.modal_title_clean {
    font-size: 1.5rem; /* Tamanho maior */
    color: #333; /* Cor do texto */
}

/* Botão de Fechar */
.modal_close_button {
    font-size: 1.5rem; /* Tamanho maior */
}

/* Corpo do Modal */
.modal_body_style {
    padding: 20px; /* Aumenta o padding */
}

/* Alerta */
.alert_warning_style {
    border-radius: 8px; /* Bordas arredondadas */
    padding: 15px; /* Padding confortável */
}

/* Preço da Lead */
.lead_price_style {
    font-weight: bold; /* Destaque para o preço */
    font-size: 1.5rem; /* Aumenta o tamanho */
    color: #e67e22; /* Cor do preço */
}

/* Rodapé do Modal */
.modal_footer_style {
    border-top: none; /* Remove a borda superior */
    justify-content: flex-end; /* Alinha os botões à direita */
}

/* Botões */
.modal_btn_cancel {
    background-color: #ccc; /* Cor de fundo do botão de cancelar */
    color: #333; /* Cor do texto */
    border-radius: 5px; /* Bordas arredondadas */
    padding: 10px 15px; /* Padding ajustado */
    font-size: 1rem; /* Tamanho do texto */
}

.modal_btn_confirm {
    background-color: #007bff; /* Cor de fundo do botão de confirmar */
    color: white; /* Cor do texto */
    border-radius: 5px; /* Bordas arredondadas */
    padding: 10px 15px; /* Padding ajustado */
    font-size: 1rem; /* Tamanho do texto */
}

/* Media Queries para Ajustes em Dispositivos Móveis */
@media (max-width: 768px) {
    .modal_title_clean {
        font-size: 1.25rem; /* Ajusta o tamanho do título para telas menores */
    }
    .modal_body_style {
        padding: 15px; /* Reduz o padding em telas menores */
    }
    .lead_price_style {
        font-size: 1.25rem; /* Reduz o tamanho do preço */
    }
    .modal_btn_cancel, .modal_btn_confirm {
        width: 100%; /* Botões ocupam toda a largura */
        margin-top: 10px; /* Espaçamento entre os botões */
    }
}

</style>
<div class="modal modal_confirm_purchase" tabindex="-1" role="dialog" id="modalComprarLead">
  <div class="modal-dialog modal_dialog_large" role="document">
      <div class="modal-content modal_content_clean">
          <div class="modal-header modal_header_style">
              <h5 class="modal-title modal_title_clean">Atender Lead</h5>
              <button type="button" class="close modal_close_button" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body modal_body_style">
              <div class="alert alert-warning alert_warning_style">
                  <span>
                      Deseja atender essa Lead por
                      <strong class="lead_price_style">R$ {{ number_format($lead->preco, 2) }}</strong>.
                  </span>
              </div>
          </div>
          <div class="modal-footer modal_footer_style">
              <button type="button" class="btn btn_cancel modal_btn_cancel" data-dismiss="modal">Cancelar</button>
              <button type="button" class="btn btn_confirm modal_btn_confirm" onclick="comprar('{{$lead->id}}')">Comprar</button>
          </div>
      </div>
  </div>
</div>
