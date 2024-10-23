<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incluit Cargo</title>
  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
   
    <style>
        body {
            background-color: #fff5e6;
            min-height: 100vh;
        }

        .main-container {
            min-height: 100vh;
            display: flex;
        }

        .sidebar {
            width: 280px;
            background-color: white;
            position: fixed;
            left: -280px;
            top: 0;
            bottom: 0;
            transition: 0.3s;
            z-index: 1000;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            padding: 20px;
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0,0,0,0.5);
            z-index: 999;
        }

        .sidebar-overlay.active {
            display: block;
        }

        .tracking-container {
            max-width: 600px;
            width: 100%;
            padding: 20px;
            margin: auto;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .user-icon {
            width: 40px;
            height: 40px;
            background-color: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-track {
            background-color: #fd7e14;
            border-color: #fd7e14;
        }

        .btn-track:hover {
            background-color: #e67211;
            border-color: #e67211;
        }

        .menu-toggle {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            z-index: 1001;
        }

        .form-control {
            padding: 12px;
            font-size: 16px;
        }
    </style>
</head>
<body>
  
    <div class="menu-toggle" onclick="toggleSidebar()">
        <i class="bi bi-list fs-4"></i>
    </div>


    <div class="sidebar">
        <h5 class="mb-4">Menu</h5>
        
        </ul>
    </div>


    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <div class="main-container">
        <div class="tracking-container">
            <div class="card">
                <div class="card-body p-4">

                    <div class="text-center mb-4">
                        <h1 class="h2 mb-2">Incluir Cargo</h1>
                        
                    </div>

                    


                    <form>
                        <div class="mb-3">
                            <input type="text" class="form-control form-control-lg" placeholder="ID do cargo">
                        </div>
                        <div class="mb-4">
                            <input type="text" class="form-control form-control-lg" placeholder="Nome do cargo">
                        </div>
                        <button type="submit" class="btn btn-track btn-lg text-white w-100">
                            Incluir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
   
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.sidebar-overlay').classList.toggle('active');
        }
    </script>
</body>
</html>