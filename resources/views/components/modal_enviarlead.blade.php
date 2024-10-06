<div class="modal" tabindex="-1" role="dialog" id="modal_enviar_lead">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Enviar Lead # {{$lead_id}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            {{-- <div class="form-group">
              <label>Usuários que tem acesso a essa lead</label>
              <div style="max-height: 200px; overflow: auto;">
                <table style="font-size: 12px" class="table table-bordered">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>USUÁRIO</th>
                      <th>AÇÃO</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($leadsusers as $user)
                    <tr id="tr_{{$lead_id}}_{{$user->id_user}}">
                      <td>{{$user->id_user}}</td>
                      <td>
                          <a target="_blank" href="{{url("/corretores/editar/".$user->id_user)}}">
                            {{$user->name}}
                          </a>
                      </td>
                      <td>
                        <a href="#" onclick="removerLead('{{$lead_id}}', '{{$user->id_user}}')">
                          Remover Lead do usuário
                        </a>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div> --}}
            <div class="form-group">
                <label>Escolha o usuário que deseja enviar a lead</label>
                <input type="hidden" value="{{$lead_id}}" id="lead_id_enviar"/>
                <select class="form-control" id="userSendLead">
                    <option disabled></option>
                    @foreach($users as $user)
                        <option value="{{$user->id}}">{{$user->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
          <button class="btn btn-primary" onclick="enviarLead()">Enviar</button>
        </div>
      </div>
    </div>
  </div>