<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Cargo </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
    
    <style>
        body {
            background-color: #fff5e6;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }


        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 250px;
            background-color: #212529;
            padding: 20px;
            color: white;
            transition: transform 0.3s ease;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 20px 0;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin-bottom: 10px;
        }

        .sidebar-menu a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .sidebar-menu a:hover {
            background-color: rgba(255, 255, 255, 0.8);
            color: white;
        }

        .sidebar-menu i {
            margin-right: 10px;
        }

        /* Main Content Styles */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .alter-container {
            max-width: 600px;
            width: 100%;
            padding: 20px;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .employee-card {
            background-color: #f8f9fa;
            border-radius: 15px;
            padding: 15px;
            margin: 20px 0;
            display: none;
        }

        .user-icon {
            width: 48px;
            height: 48px;
            background-color: #e9ecef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-control, .select2-container--bootstrap-5 .select2-selection {
            padding: 12px;
            border-radius: 8px;
            height: auto;
        }

        .btn-alterar {
            background-color: #212529;
            color: white;
            padding: 12px;
            border-radius: 8px;
        }

        .btn-alterar:hover {
            background-color: #343a40;
            color: white;
        }

        .select2-container {
            width: 100% !important;
        }

        .employee-option {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .employee-option small {
            color: #6c757d;
        }

     
        .sidebar-toggle {
            position: fixed;
            left: 10px;
            top: 10px;
            z-index: 1001;
            display: none;
            background: #212529;
            border: none;
            color: white;
            padding: 10px;
            border-radius: 5px;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>

    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>


    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4>Menu</h4>
        </div>
        <ul class="sidebar-menu">
            
        </ul>
    </div>


    <div class="main-content">
        <div class="alter-container">
            <div class="card">
                <div class="card-body p-4">

                    <div class="text-center mb-4">
                        <h1 class="h2 mb-2">Alterar Cargo</h1>
                        <p class="text-muted">Selecione o Funcionário</p>
                    </div>

              
                    <div class="mb-4">
                        <label class="form-label text-muted small mb-1">Buscar funcionário</label>
                        <select class="form-select" id="employeeSelect">
                            <option></option>
                            <option value="0001">0001 - Everton Cebolinha (Cozinheiro)</option>
        
                        </select>
                    </div>

                 
                    <div class="employee-card" id="employeeInfo">
                        <div class="d-flex align-items-center gap-3">
                            <div class="user-icon">
                                <i class="bi bi-person fs-4"></i>
                            </div>
                            <div>
                                <h5 class="mb-1" id="employeeName">EVERTON CEBOLINHA</h5>
                                <p class="text-muted mb-0" id="employeeRole">Função: Cozinheiro</p>
                            </div>
                        </div>
                    </div>

                 
                    <div class="mb-4">
                        <label class="form-label text-muted small mb-1">Alterar cargo</label>
                        <select class="form-select" id="cargoSelect">
                            <option selected disabled>Selecione o novo cargo</option>
                            
                            <option>Cozinheiro</option>
                         
                        </select>
                    </div>

                   
                    <button type="submit" class="btn btn-alterar w-100" disabled id="submitButton">
                        Alterar
                    </button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
 
            $('#sidebarToggle').click(function() {
                $('#sidebar').toggleClass('active');
            });

       
            $(document).click(function(event) {
                if (!$(event.target).closest('#sidebar').length && 
                    !$(event.target).closest('#sidebarToggle').length && 
                    window.innerWidth <= 768) {
                    $('#sidebar').removeClass('active');
                }
            });

            $('#employeeSelect').select2({
                theme: 'bootstrap-5',
                placeholder: 'Busque por ID ou nome do funcionário',
                allowClear: true,
                templateResult: formatEmployee,
                templateSelection: formatEmployee
            });


            $('#cargoSelect').select2({
                theme: 'bootstrap-5',
                placeholder: 'Selecione o novo cargo'
            });

         
            $('#employeeSelect').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                if (selectedOption.val()) {
                    const [id, name, role] = selectedOption.text().split(' - ');
                    $('#employeeName').text(name);
                    $('#employeeRole').text('Função: ' + role.replace('(', '').replace(')', ''));
                    $('.employee-card').slideDown();
                    updateSubmitButton();
                } else {
                    $('.employee-card').slideUp();
                    updateSubmitButton();
                }
            });


            $('#cargoSelect').on('change', function() {
                updateSubmitButton();
            });

         
            function formatEmployee(employee) {
                if (!employee.id) return employee.text;
                
                const [id, name, role] = employee.text.split(' - ');
                return $(`
                    <div class="employee-option">
                        <div class="user-icon-small">
                            <i class="bi bi-person"></i>
                        </div>
                        <div>
                            <div>${name}</div>
                            <small>${id} - ${role}</small>
                        </div>
                    </div>
                `);
            }

          
            function updateSubmitButton() {
                const employeeSelected = $('#employeeSelect').val();
                const cargoSelected = $('#cargoSelect').val() && $('#cargoSelect').val() !== 'Selecione o novo cargo';
                $('#submitButton').prop('disabled', !employeeSelected || !cargoSelected);
            }
        });
    </script>
</body>
</html>