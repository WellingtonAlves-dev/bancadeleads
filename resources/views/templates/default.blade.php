<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>BANCA DE LEADS</title>

    <!-- Custom fonts for this template -->
    <link href="{{asset('assets')}}/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{asset('assets')}}/css/sb-admin-2.css" rel="stylesheet">

    <style>
        /* Sidebar Fixa */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            /* Largura normal */
            background-color: #007fff;
            color: white;
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        /* Estado recolhido */
        #sidebar.collapsed {
            width: 70px;
            overflow-x: hidden;
        }

        #sidebar.collapsed .sidebar-header {
            padding: 10px;
            text-align: center;
        }

        #sidebar.collapsed .sidebar-header img {
            width: 40px;
        }

        #sidebar.collapsed .nav-item {
            padding: 15px 5px;
            text-align: center;
        }

        #sidebar.collapsed .nav-link {
            justify-content: center;
        }

        #sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.2rem;
        }

        #sidebar.collapsed .nav-link span,
        #sidebar.collapsed .has-submenu .fa-chevron-down {
            display: none;
        }

        #sidebar.collapsed .has-submenu {
            position: relative;
        }

        #sidebar .sidebar-header {
            padding: 20px;
            background-color: #005bb5;
            text-align: center;
        }

        #sidebar .sidebar-header img {
            width: 100px;
        }

        #sidebar .nav-item {
            padding: 10px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        #sidebar .nav-item a {
            color: white;
            font-size: 14px;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        #sidebar .nav-item a i {
            margin-right: 10px;
        }

        #sidebar .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        #sidebar .submenu {
            display: none;
            padding-left: 20px;
            background-color: rgba(0, 0, 0, 0.1);
        }


        #sidebar .submenu li {
            padding: 10px 0;
        }

        #sidebar .submenu li a {
            font-size: 13px;
            color: #ddd;
            display: block;
        }

        #sidebar .submenu li a:hover {
            color: white;
        }

        #sidebar.collapsed .submenu {
            position: fixed;
            background-color: #1c2541;
            left: 10px;
            padding: 15px;
        }

        /* Conteúdo Principal */
        #content-wrapper {
            margin-left: 250px;
            /* Espaço para a sidebar */
            transition: margin 0.3s ease-in-out;
        }

        /* Topbar */
        .topbar {
            background-color: #007fff !important;
            height: 120px;
        }

        .topbar .logo_principal {
            height: 80px;
            cursor: pointer;
        }

        .topbar .navbar-nav {
            margin-left: auto;
        }

        .topbar .nav-link {
            color: white !important;
        }


        .cta-button-14 {
            background-color: transparent;
            /* Fundo transparente */
            color: #007fff;
            /* Cor do texto azul */
            border: 2px solid #007fff;
            /* Borda azul */
            border-radius: 8px;
            /* Bordas mais arredondadas */
            padding: 10px 20px;
            /* Padding maior para melhor toque */
            cursor: pointer;
            /* Cursor pointer */
            font-weight: bold;
            /* Texto em negrito */
            font-size: 16px;
            /* Tamanho da fonte */
            transition: all 0.3s ease;
            /* Transição suave para todos os efeitos */
            display: inline-flex;
            /* Alinhar ícone e texto */
            align-items: center;
            /* Centralizar verticalmente */
            gap: 8px;
            /* Espaço entre ícone e texto */
        }

        .cta-button-14:hover {
            background-color: #007fff;
            /* Fundo azul no hover */
            color: white;
            /* Texto branco no hover */
            box-shadow: 0 4px 12px rgba(0, 127, 255, 0.3);
            /* Sombra suave no hover */
            transform: translateY(-2px);
            /* Efeito de levantar o botão */
        }

        .cta-button-14:active {
            transform: translateY(0);
            /* Remove o efeito de levantar ao clicar */
            box-shadow: 0 2px 6px rgba(0, 127, 255, 0.3);
            /* Sombra mais suave ao clicar */
        }

        /* Ícone SVG (opcional) */
        .cta-button-14 svg {
            width: 20px;
            height: 20px;
            fill: #007fff;
            /* Cor do ícone */
            transition: fill 0.3s ease;
            /* Transição suave para o ícone */
        }

        .cta-button-14:hover svg {
            fill: white;
            /* Cor do ícone no hover */
        }

        /* Responsividade */
        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
                width: 250px;
            }

            #sidebar.active {
                transform: translateX(0);
            }

            #sidebar.collapsed {
                width: 250px;
                /* Mantém largura normal em mobile */
            }

            #content-wrapper {
                margin-left: 0 !important;
            }

            /* Esconde o toggle em desktop se estiver em mobile */
            #sidebarToggle {
                display: block !important;
            }

            #sidebar.collapsed .has-submenu:hover .submenu,
            #sidebar.collapsed .has-submenu:focus-within .submenu {
                left: 70px;
                top: auto;
            }

        }
    </style>
</head>

<body id="page-top">
    @if(Session::get("user_master") ?? null)
        <div class="bg-primary sticky-top text-center">
            <a href="{{url('user/retornar')}}" style="color: white;">
                Você está acessando a conta do usuário <strong>{{strtoupper(Auth::user()->name)}}</strong>. Clique aqui para
                retornar à sua conta.
            </a>
        </div>
    @endif

    <!-- Sidebar -->
    <div id="sidebar">
        <div class="sidebar-header">
            <img src="{{asset('assets/img/logo.png')}}" alt="BANCA DE LEADS">
        </div>
        <ul class="navbar-nav">
            <!-- Área do Cliente -->
            @if(Auth::user()->role == "user")
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/checkout')}}">
                        <i class="fas fa-wallet"></i> <span class="texto-sidebar">Recarregar Saldo</span>
                    </a>
                </li>
                <li class="nav-item has-submenu">
                    <a class="nav-link" href="#">
                        <i class="fas fa-shopping-cart"></i> <span class="texto-sidebar">Comprar Leads</span> <i
                            class="fas fa-chevron-down ml-auto"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{url('/leads')}}">Leads Avulsas</a></li>
                        <li><a href="{{url('/pacotes')}}">Pacotes de Leads</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/minhas/leads')}}">
                        <i class="fas fa-folder-open"></i> <span class="texto-sidebar">Minhas Leads</span>
                    </a>
                </li>
            @endif

            <!-- Gestão de Reposições -->
            @if(Auth::user()->role !== "admin")
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/reposicoes')}}">
                        <i class="fas fa-sync-alt"></i> <span class="texto-sidebar">Solicitar Reposições</span>
                    </a>
                </li>
            @endif

            <!-- Gestão de Corretores e Financeiro -->
            @if(Auth::user()->role === "user")
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/corretores')}}">
                        <i class="fas fa-users"></i> <span class="texto-sidebar">Gestão de Corretores</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/meus/extratos')}}">
                        <i class="fas fa-chart-line"></i> <span class="texto-sidebar">Extrato Financeiro</span>
                    </a>
                </li>
            @endif

            <!-- Área do Administrador -->
            @if(Auth::user()->role === "admin")
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/admin/planos')}}">
                        <i class="fas fa-cogs"></i> Configuração de Planos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/admin/tipos')}}">
                        <i class="fas fa-cogs"></i> Configuração de Tipos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/leads')}}">
                        <i class="fas fa-cogs"></i> Configuração de Leads
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/admin/avisos')}}">
                        <i class="fas fa-cogs"></i> Configuração de Avisos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/admin/users')}}">
                        <i class="fas fa-users-cog"></i> Gerenciar Usuários
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/admin/marketing')}}">
                        <i class="fas fa-bullhorn"></i> Campanhas de Marketing
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/admin/reposicoes')}}">
                        <i class="fas fa-undo"></i> Reposições Pendentes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/admin/financeiro/vendas')}}">
                        <i class="fas fa-coins"></i> Relatórios Financeiros
                    </a>
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
                style="padding: 0 !important; height: 120px;   background-color: #007fff !important;">
                <div>
                    <button id="sidebarToggle" class="btn btn-link text-white mr-3">
                        <i class="fas fa-bars fa-lg"></i>
                    </button>
                </div>


                <!-- Sidebar Toggle (Topbar) -->
                {{-- <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3 open-drawer-btn">
                    <i class="fa fa-bars"></i>
                </button> --}}
                <!-- Topbar Navbar -->
                <ul class="navbar-nav top-nav text-white">
                    @if(Auth::user()->role == "admin")
                        <li class="nav-item">
                            <div class="h-100 px-4 flex">
                                <div class="col h-100 d-flex flex-column justify-content-center align-items-center ">
                                    <div>

                                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="mr-2 d-none d-lg-inline  small" style="color: white !important">
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
                                            <a class="dropdown-item" href="#" data-toggle="modal"
                                                data-target="#logoutModal">
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
                                            <span class="mr-2 d-none d-lg-inline  small" style="color: white !important">
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
                                            <a class="dropdown-item" href="#" data-toggle="modal"
                                                data-target="#logoutModal">
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
            @if(Auth::user()->role == "user")
                <div class="w-100 d-flex justify-content-end align-items-center">
                    <button class="cta-button-14" onclick="window.location.href = '{{url("/checkout")}}'">Recarregar
                        Saldo</button>
                    <span onclick="window.location.href = '{{url("/checkout")}}'" class="on-hover p-3">
                        Meu saldo
                        <strong>
                            R$
                            <span class="saldo_infos" id="seuSaldoAtualInfo">{{ Auth::user()->getValorSaldo(true) }}</span>
                        </strong>
                    </span>
                </div>
                {{-- <div class="w-100 p-3 d-flex justify-content-end align-items-center">
                    <button class="cta-button-14" onclick="window.location.href = '{{url("/checkout")}}'">Recarregar
                        Saldo</button>
                </div> --}}
            @endif

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


                            Termo de Uso do Site "BANCA DE LEADS"

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
                    <a style="color: black;" target="_blank"
                        href="https://bancadeleads.com.br/sistema/termos.php">Termos e a
                        política de privacidade</a>
                </div>
                <div class="copyright text-center mt-5">
                    <span>&copy; {{date("Y")}} BANCA DE LEADS. Todos os direitos reservados.</span>
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
                        <img src="{{asset("assets/img/money.png")}}" style="height: 48px; margin-left: 10px;" />
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Insira o valor desejado</label>
                        <input type="text" class="form-control" placeholder="Ex. R$ 50,00" id="valorAdd" />
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"
        integrity="sha512-0XDfGxFliYJPFrideYOoxdgNIvrwGTLnmK20xZbCAvPfLGQMzHUsaqZK8ZoH+luXGRxTrS46+Aq400nCnAT0/w=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script>
        const MODAL_LOADING = $("#modalLoading");
        const DIV_GENERIC = $("#divgeneric");
        $("#valorAdd").mask("#.##0,00", { reverse: true });
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
        // const openBtn = document.querySelector('.open-drawer-btn');
        const closeBtn = document.getElementById('closeDrawer');
        const overlay = document.getElementById("overlay");
        // openBtn.addEventListener('click', () => {
        //     drawer.classList.add('active');
        //     overlay.classList.add("active");
        // });

        // closeBtn.addEventListener('click', () => {
        //     drawer.classList.remove('active');
        //     overlay.classList.remove("active");
        // });

        $(document).ready(function () {

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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const contentWrapper = document.getElementById('content-wrapper');

            // Verifica estado salvo
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
                contentWrapper.style.marginLeft = '70px';
            }

            // Evento do botão toggle
            sidebarToggle.addEventListener('click', function () {
                sidebar.classList.toggle('collapsed');

                // Ajusta o conteúdo principal
                if (sidebar.classList.contains('collapsed')) {
                    contentWrapper.style.marginLeft = '70px';
                    localStorage.setItem('sidebarCollapsed', 'true');
                } else {
                    contentWrapper.style.marginLeft = '250px';
                    localStorage.setItem('sidebarCollapsed', 'false');
                }
            });
        });
    </script>
    @yield("script")
</body>

</html>