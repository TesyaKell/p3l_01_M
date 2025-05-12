<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CS - Homepage</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
        }

        .sidebar {
            width: 220px;
            height: 100vh;
            background-color: #edd091;
            color: black;
            transition: width 0.3s;
            overflow: hidden;
            border-right: 1px solid black;
        }

        .sidebar.collapsed {
            width: 60px;
        }

        .sidebar h2 {
            font-size: 1.4rem;
            text-align: center;
            margin: 1.5rem 0;
            transition: opacity 0.3s;
        }

        .sidebar.collapsed h2 {
            opacity: 0;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            color: black;
            text-decoration: none;
            padding: 1rem;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #c7a14e;
        }

        .sidebar i {
            margin-right: 1rem;
        }

        .sidebar.collapsed a span {
            display: none;
        }

        .toggle-btn {
            background-color: #c7a14e;
            color: black;
            border: none;
            width: 100%;
            padding: 1rem;
            cursor: pointer;
        }

        .main-content {
            flex-grow: 1;
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        iframe {
            width: 100%;
            height: 100%;
            border: none;
            background-color: #cf9651;
        }
    </style>
</head>
<body>

    <div class="sidebar" id="sidebar">
        <button class="toggle-btn" onclick="toggleSidebar()">CS menu</button>
        <a href="{{ route('register.penitip') }}" target="cs-content">
            <i>📋</i><span>Register Penitip</span>
        </a>
        <a href="{{ route('jabatan') }}" target="cs-content">
            <i>💬</i><span>Chat</span>
        </a>
        <a href="/">
            <i>🏠</i><span>Beranda</span>
        </a>
    </div>

    <div class="main-content">
        <iframe name="cs-content" title="CS Content Frame"></iframe>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        }
    </script>
</body>
</html>

    