<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>INDICA SÁUDE</title>

    <!-- Custom fonts for this template-->
    <link href="{{asset("assets")}}/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset("assets")}}/css/sb-admin-2.css" rel="stylesheet">
    <style>

        @media screen and (max-width: 800px) {
            .nav-link .dropdown-toggle {
                margin: 0;
                padding: 0;
                height: auto;
            }
        }
    
        .overlay {
            position: fixed;
            width: 100vw;
            height: 100vh;
            z-index: 998;
            background-color: black;
            opacity: 0.6;
            display: none;
            transition: opacity 0.3s ease-in-out;
        }
    
        .overlay.active {
            display: block !important;
        }
    
        .drawer {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: white;
            z-index: 999;
            transition: transform 0.3s ease-in-out;
            transform: translateX(-100%);
        }
    
        @media screen and (min-width: 728px) {
            .drawer {
                width: 40%;
            }
        }
    
        .drawer.active {
            transform: translateX(0);
        }
    
        .drawer .close-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
            color: #333;
        }
    
        .drawer .nav-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
    
        .drawer .nav-item a {
            color: #333;
            font-size: 16px;
            display: flex;
            align-items: center;
        }
    
        .drawer .nav-item a i {
            margin-right: 10px;
        }
    
        .drawer .nav-item:hover {
            background-color: #f9f9f9;
        }
    
        /* Estilos do Submenu */
        .drawer .has-submenu {
            position: relative;
        }
    
        .drawer .submenu {
            display: none; /* Ocultar por padrão */
            padding-left: 15px;
            background-color: #f1f1f1; /* Fundo do submenu */
        }
    
        .drawer .has-submenu.active .submenu {
            display: block; /* Mostrar quando ativo */
        }
    
        .drawer .submenu li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
    
        .drawer .submenu li a {
            font-size: 14px;
            color: #555;
            display: block;
            padding-left: 20px; /* Indentação do link do submenu */
        }
    
        .drawer .submenu li a:hover {
            background-color: #e9ecef; /* Fundo ao passar o mouse */
            text-decoration: underline;
        }
    
        /* New Styles for Titles */
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
            color: #0055a5;
            padding-left: 15px;
        }
    
        /* body {
            transform: scale(0.75);
            transform-origin: top left;
            width: 133.33%;
            height: 133.33%;
        }
        *[style*="100vw"] {
            width: calc(100vw / 0.75);
        }
    
        /* Ajusta todos os elementos que têm altura de 100vh */
        /* *[style*="100vh"] {
            height: calc(100vh / 0.75);
        } */ 
    
    </style>
    
</head>

<body id="page-top" class="sidebar-toggled">
    @if(Session::get("user_master") ?? null)
        <div class="bg-primary sticky-top  text-center">
            <a href="{{url("user/retornar")}}" >
                Você está acessando a conta do usuário <strong>{{strtoupper(Auth::user()->name)}}</strong>. Por favor, clique aqui para retornar à sua própria conta.
            </a>
        </div>
    @endif
    <!-- Page Wrapper -->
    <div id="wrapper">
        <div class="overlay" id="overlay"></div>
        <!-- Drawer Sidebar -->
        {{-- <div class="drawer" id="drawerSidebar">
            <!-- Botão de Fechar -->
            <span class="close-btn" id="closeDrawer">&times;</span>
        
            <!-- Nav Items -->
            <ul class="navbar-nav">
                @if(Auth::user()->role == "user")
                    <li class="section-title">Cliente</li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/checkout")}}">
                            <i class="fas fa-credit-card"></i> Inserir Saldo
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/pacotes")}}">
                            <i class="fas fa-box"></i> Pacote de Leads
                        </a>
                    </li>
                @endif
        
                @if(in_array(Auth::user()->role, ["admin", "user"]))
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/leads")}}">
                            <span>
                                @if(Auth::user()->role == "admin")
                                    Leads
                                @else
                                    <i class="fas fa-id-badge"></i> Comprar Leads
                                @endif
                            </span>
                        </a>
                    </li>
                @endif
        
                @if(Auth::user()->role !== "admin")
                    <li class="section-title">Gerenciamento de Leads</li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/minhas/leads")}}">
                            <i class="fas fa-folder-open"></i> Carteira de Leads
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/reposicoes")}}">
                            <i class="fas fa-gavel"></i> Contestações Solicitadas </a>
                    </li>
                @endif
        
                @if(Auth::user()->role === "user")
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/corretores")}}">
                            <i class="fas fa-users"></i> Meus Corretores
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/meus/extratos")}}">
                            <i class="fas fa-wallet"></i> Movimentação Financeira
                        </a>
                    </li>
                @endif
        
                @if(Auth::user()->role == "admin")
                    <li class="section-title">Administrador</li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/admin/planos")}}">
                            <i class="fas fa-tasks"></i> Planos
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/admin/tipos")}}">
                            <i class="fas fa-tasks"></i> Tipos
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/admin/pacotes")}}">
                            <i class="fas fa-box"></i> Pacotes
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/admin/users")}}">
                            <i class="fas fa-users"></i> Usuários
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/admin/financeiro/vendas")}}">
                            <i class="fas fa-file-invoice-dollar"></i> Financeiro
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/admin/notificacoes")}}">
                            <i class="fas fa-bell"></i> Notificações Pagamento
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/admin/reposicoes")}}">
                            <i class="fas fa-undo"></i> Reposições
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/admin/avisos")}}">
                            <i class="fas fa-bullhorn"></i> Avisos</small>
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/admin/marketing/mobile")}}">
                            <i class="fas fa-bullhorn"></i> Marketing <small>(Mobile)</small>
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/admin/marketing")}}">
                            <i class="fas fa-bullhorn"></i> Marketing
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/admin/marketing/templates")}}">
                            <i class="fas fa-bullhorn"></i> Marketing <small>Templates</small>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
         --}}
    
         {{-- <div class="drawer" id="drawerSidebar">
            <!-- Botão de Fechar -->
            <span class="close-btn" id="closeDrawer">&times;</span>
        
            <!-- Nav Items -->
            <ul class="navbar-nav">
                @if(Auth::user()->role == "user")
                    <li class="section-title">Área do Cliente</li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/checkout")}}">
                            <i class="fas fa-wallet"></i> Adicionar Crédito
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/pacotes")}}">
                            <i class="fas fa-box-open"></i> Pacotes de Leads
                        </a>
                    </li>
                @endif
        
                @if(in_array(Auth::user()->role, ["admin", "user"]))
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/leads")}}">
                            <span>
                                @if(Auth::user()->role == "admin")
                                    Leads Disponíveis
                                @else
                                    <i class="fas fa-shopping-cart"></i> Comprar Leads
                                @endif
                            </span>
                        </a>
                    </li>
                @endif
        
                @if(Auth::user()->role !== "admin")
                    <li class="section-title">Gerenciamento de Leads</li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/minhas/leads")}}">
                            <i class="fas fa-folder"></i> Minhas Leads
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/reposicoes")}}">
                            <i class="fas fa-exchange-alt"></i> Reposições Solicitadas
                        </a>
                    </li>
                @endif
        
                @if(Auth::user()->role === "user")
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/corretores")}}">
                            <i class="fas fa-user-tie"></i> Meus Agentes
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/meus/extratos")}}">
                            <i class="fas fa-receipt"></i> Extratos Financeiros
                        </a>
                    </li>
                @endif
        
                @if(Auth::user()->role == "admin")
                    <li class="section-title">Administração</li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/admin/planos")}}">
                            <i class="fas fa-cogs"></i> Gerenciar Planos
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/admin/tipos")}}">
                            <i class="fas fa-tags"></i> Categorias
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/admin/pacotes")}}">
                            <i class="fas fa-boxes"></i> Gerenciar Pacotes
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/admin/users")}}">
                            <i class="fas fa-user-cog"></i> Usuários Registrados
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/admin/financeiro/vendas")}}">
                            <i class="fas fa-dollar-sign"></i> Vendas e Financeiro
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/admin/notificacoes")}}">
                            <i class="fas fa-bell"></i> Notificações de Pagamento
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/admin/reposicoes")}}">
                            <i class="fas fa-sync-alt"></i> Reposições de Leads
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/admin/avisos")}}">
                            <i class="fas fa-exclamation-circle"></i> Avisos Gerais
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/admin/marketing/mobile")}}">
                            <i class="fas fa-bullhorn"></i> Campanhas Mobile
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/admin/marketing")}}">
                            <i class="fas fa-bullhorn"></i> Marketing Geral
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/admin/marketing/templates")}}">
                            <i class="fas fa-palette"></i> Modelos de Marketing
                        </a>
                    </li>
                @endif
            </ul>
        </div> --}}
        
        <div class="drawer" id="drawerSidebar">
            <!-- Botão de Fechar -->
            <span class="close-btn" id="closeDrawer">&times;</span>
        
            <!-- Nav Items -->
            <ul class="navbar-nav">
                @if(Auth::user()->role == "user")
                    <!-- Adicionar Crédito no topo, sem submenu -->
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url("/checkout")}}">
                            <i class="fas fa-wallet"></i> Adicionar Crédito
                        </a>
                    </li>
        
                <!-- Pacotes e Leads com Submenu -->
                <li class="nav-item has-submenu">
                    <a class="nav-link" href="#">
                        <i class="fas fa-box-open"></i> Comprar Leads <i class="fa fa-chevron-down ml-3" aria-hidden="true"></i>
                    </a>
                    <ul class="submenu">
                        @if(Auth::user()->role == "admin")
                            <li><a href="{{url("/leads")}}">Leads Disponíveis</a></li>
                        @else
                            <li><a href="{{url("/leads")}}">Comprar Lead Avulsa</a></li>
                        @endif
                        <li><a href="{{url("/pacotes")}}">Comprar Pacote de Leads</a></li>
                        <li><a href="{{url("/minhas/leads")}}">Leads Que Comprei</a></li>
                    </ul>
                </li>
                @endif

                @if(Auth::user()->role !== "admin")
                    <!-- Reposições de Leads com Submenu -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{url("/reposicoes")}}">
                            <i class="fas fa-exchange-alt"></i> Reposições Solicitadas
                        </a>
                    </li>
                @endif
        
                @if(Auth::user()->role === "user")
                    <!-- Gestão de Agentes com Submenu -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{url("/corretores")}}">
                            <i class="fas fa-user-tie"></i> Meus Corretores
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{url("/meus/extratos")}}">
                            <i class="fas fa-file-invoice"></i> Meu Extrato Financeiro
                        </a>
                    </li>
                @endif
        
                @if(Auth::user()->role === "admin")
                    <!-- Administração com Submenu -->
                    <li class="nav-item has-submenu" style="margin-top: 50px">
                        <a class="nav-link" href="#">
                            <i class="fas fa-cogs"></i> Administração <i class="fa fa-chevron-down ml-3" aria-hidden="true"></i>
                        </a>
                        <ul class="submenu">
                            <li><a href="{{url("/leads")}}">Leads Avulsas</a></li>
                            <li><a href="{{url("/admin/pacotes")}}">Pacotes de Leads</a></li>
                            <li><a href="{{url("/admin/planos")}}">Gerenciar Planos</a></li>
                            <li><a href="{{url("/admin/tipos")}}">Tipos</a></li>
                            <li><a href="{{url("/admin/users")}}">Usuários Registrados</a></li>
                            <li><a href="{{url("/admin/financeiro/vendas")}}">Vendas e Financeiro</a></li>
                            <li><a href="{{url("/admin/notificacoes")}}">Notificações de vendas</a></li>
                        </ul>
                    </li>
        
                    <!-- Marketing com Submenu -->
                    <li class="nav-item has-submenu">
                        <a class="nav-link" href="#">
                            <i class="fas fa-bullhorn"></i> Marketing <i class="fa fa-chevron-down ml-3" aria-hidden="true"></i>
                        </a>
                        <ul class="submenu">
                            <li><a href="{{url("/admin/marketing/mobile")}}">Campanhas Mobile</a></li>
                            <li><a href="{{url("/admin/marketing")}}">Marketing Geral</a></li>
                            <li><a href="{{url("/admin/marketing/templates")}}">Modelos de Marketing</a></li>
                            <li><a href="{{url("/admin/avisos")}}">Avisos</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
        
        

        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar shadow navbar-expand navbar-light topbar mb-4 static-top"
                style="padding: 0 !important; height: 120px;   background-color: #F5F5F5 !important;"
                >

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3 open-drawer-btn">
                        <i class="fa fa-bars"></i>
                    </button>
                        <img
                            src="{{asset("assets/img/logo.png")}}" 
                            alt="Indica Saúde"
                            class="d-flex justify-content-end ml-auto logo_principal mr-auto"
                            id="icon"
                            style="cursor: pointer"
                            onclick="window.location.href = '{{url("/")}}'"
                        />
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav top-nav">
                        @if(Auth::user()->role == "admin")
                        <li class="nav-item">
                            <div class="h-100 px-4 flex">
                                <div class="col h-100 d-flex flex-column justify-content-center align-items-center ">
                                    <div>

                                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="mr-2 d-none d-lg-inline  small">
                                                <i class="fas fa-user"></i>
                                                {{ucwords(Auth::user()->name)}}
                                            </span>
                                        </a>
                                        <!-- Dropdown - User Information -->
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                            aria-labelledby="userDropdown">
                                            @if(Auth::user()->role != "corretor")
                                            <a class="dropdown-item" href="{{url("/profile")}}">
                                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                                Perfil
                                            </a>
                                            @endif 
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                                Sair
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </li>
                        @endif
                        @if(Auth::user()->role == "user")
                        <li class="nav-item">
                            <div class="h-100 px-4 flex">
                                <div class="col h-100 d-flex flex-row justify-content-center align-items-center ">
                                    Perfil
                                    <div>

                                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="mr-2 d-none d-lg-inline  small">
                                                <i class="fas fa-user"></i>
                                                {{ucwords(Auth::user()->name)}}
                                            </span>
                                        </a>
                                        <!-- Dropdown - User Information -->
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                            aria-labelledby="userDropdown">
                                            @if(Auth::user()->role != "corretor")
                                            <a class="dropdown-item" href="{{url("/profile")}}">
                                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                                Perfil
                                            </a>
                                            @endif 
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                                Sair
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </li>
                        @endif
                    </ul>
                </nav>
                <div class="w-100 d-flex justify-content-end align-items-center">
                    <span onclick="window.location.href = '{{url("/checkout")}}'" class="on-hover p-3">
                        Meu saldo
                        <strong>
                            R$
                            <span class="saldo_infos" id="seuSaldoAtualInfo">{{ Auth::user()->getValorSaldo(true) }}</span>
                        </strong>
                    </span>
                </div>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @yield("content")
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <div class="modal" id="modal_termos" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">termos e a política de privacidade</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body" style="max-height: 300px; overflow: auto;">

                        <p>


                            Termo de Uso do Site "INDICA SAUDE"

                        </p>

                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    </div>
                  </div>
                </div>
              </div>

            <!-- Footer -->
            <footer class="sticky-footer">
                <div class="container my-auto">
                    <div class="text-center my-auto">
                    </div>
                    <div class="copyright text-center my-auto">
                        <a style="color: black;" target="_blank" href="https://indicasaude.com.br/termos.html">Termos e a política de privacidade</a>
                    </div>
                    <div class="copyright text-center mt-5">
                        <span>&copy; {{date("Y")}} INDICA SAÚDE. Todos os direitos reservados.</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Quer mesmo sair ?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Clique em sair para efetuar o logoff</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="{{url("/logout")}}">Sair</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="modalAddSaldo">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Adicionar Crédito
                    <img
                    src="{{asset("assets/img/money.png")}}"
                    style="height: 48px; margin-left: 10px;"
                    />
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Insira o valor desejado</label>
                    <input type="text" class="form-control" placeholder="Ex. R$ 50,00" id="valorAdd"/>
                    {{-- <small>O valor minimo é de R$ 25,00</small> --}}
                    <div id="wallet_container"></div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    Cancelar
                </button>
                <button type="button" id="confirmPay" class="btn btn-success">
                    Comprar
                </button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="modalLoading">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Carregando....</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p>
                    Sua página está sendo carregada...
                </p>
                <i class="fas fa-spinner fa-spin"></i>
            </div>
            </div>
        </div>
    </div>
    <div id="divgeneric"></div>


    <!-- Bootstrap core JavaScript-->
    <script src="{{asset("assets")}}/vendor/jquery/jquery.min.js"></script>
    <script src="{{asset("assets")}}/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{asset("assets")}}/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{asset("assets")}}/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="{{asset("assets")}}/vendor/chart.js/Chart.min.js"></script>
    <script src="{{asset("assets/js/validateInput.js")}}"></script>
    <!-- Page level custom scripts -->
    <script src="{{asset("assets")}}/js/demo/chart-area-demo.js"></script>
    <script src="{{asset("assets")}}/js/demo/chart-pie-demo.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js" integrity="sha512-0XDfGxFliYJPFrideYOoxdgNIvrwGTLnmK20xZbCAvPfLGQMzHUsaqZK8ZoH+luXGRxTrS46+Aq400nCnAT0/w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script>
        const MODAL_LOADING = $("#modalLoading");
        const DIV_GENERIC = $("#divgeneric");
        $("#valorAdd").mask("#.##0,00", {reverse: true});
        $("#confirmPay").on("click", () => {
            $("#modalLoading").modal("show");
            $("#modalAddSaldo").modal("hide");
            $.ajax({
                method: "POST",
                url: "{{url("pagamento/saldo/add")}}",
                data: {
                    "_token": "{{csrf_token()}}",
                    valor: $("#valorAdd").val()
                },
                success: (res) => {
                    $("#modalLoading").modal("hide");
                    return window.location.href = res;
                }
            })
        });
        function setSaldoInfo() {
            $.ajax({
                method: "GET",
                url: "{{url("saldo/info")}}",
                success: res => {
                    $("#seuSaldoAtualInfo").html(res);
                }
            })
        }

        setInterval(() => {
            setSaldoInfo();
        }, 5000);

    </script>
    <script>
                const drawer = document.getElementById('drawerSidebar');
        const openBtn = document.querySelector('.open-drawer-btn');
        const closeBtn = document.getElementById('closeDrawer');
        const overlay = document.getElementById("overlay");
        openBtn.addEventListener('click', () => {
            drawer.classList.add('active');
            overlay.classList.add("active");
        });

        closeBtn.addEventListener('click', () => {
            drawer.classList.remove('active');
            overlay.classList.remove("active");
        });

        $(document).ready(function(){
        
            document.querySelectorAll('.has-submenu > .nav-link').forEach(function (element) {
                element.addEventListener('click', function (e) {
                    e.preventDefault();
                    var submenu = this.nextElementSibling;
                    submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
                });
            });
            overlay.addEventListener("click", () => {
                drawer.classList.remove("active");
                overlay.classList.remove("active");
            });

        });
    </script>
    @yield("script")
</body>

</html>