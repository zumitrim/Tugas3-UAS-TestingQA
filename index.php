<?php
session_start();

// Cek apakah pengguna sudah login
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    echo "<script>var loggedInUser = '$username';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            background-color: #222;
            color: #fff;
            font-family: 'Arial', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #333;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            width: 300px;
            display: block;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 1px solid #444;
            border-radius: 4px;
            background-color: #555;
            color: #fff;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        #login-container {
            display: block;
        }

        #home-page {
            display: none;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #222;
            color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 300px; /* Ukuran maksimal modal */
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 20px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div id="login-container">
        <h2>Login</h2>
        <form id="login-form">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <br>

            <button type="submit" onclick="login()">Login</button>
        </form>
    </div>

    <div id="home-page">
        <h2>Welcome, <span id="welcome-user"></span>!</h2>
        <button onclick="logout()" id="logout">Logout</button>
    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p id="modal-message"></p>
        </div>
    </div>

    <script>
        // Cek apakah pengguna sudah login, jika ya, tampilkan halaman beranda
        if (loggedInUser) {
            showHomePage(loggedInUser);
        }

        function showModal(message) {
            var modal = document.getElementById('myModal');
            var modalMessage = document.getElementById('modal-message');
            modalMessage.innerText = message;
            modal.style.display = 'block';

            // Tambahkan event listener untuk menutup modal ketika di-klik di luar modal
            window.addEventListener('click', function(event) {
                if (event.target == modal) {
                    closeModal();
                }
            });

            // Tambahkan kode untuk menutup modal setelah 1 detik
            setTimeout(function() {
                closeModal();
            }, 1000);
        }

        function closeModal() {
            var modal = document.getElementById('myModal');
            modal.style.display = 'none';
        }

        function login() {
            var username = document.getElementById('username').value;
            var password = document.getElementById('password').value;

            fetch('process_login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'username=' + encodeURIComponent(username) + '&password=' + encodeURIComponent(password),
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'success') {
                    showModal('Login successful');
                    showHomePage(username);
                } else {
                    showModal('Username or password is incorrect. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function logout() {
            fetch('logout.php')
            .then(response => response.text())
            .then(data => {
                if (data === 'success') {
                    showModal('Logout successful');
                    showLoginPage();
                } else {
                    showModal('Logout failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function showHomePage(username) {
            document.getElementById('login-container').style.display = 'none';
            document.getElementById('home-page').style.display = 'block';
            document.getElementById('welcome-user').innerText = username;
        }

        function showLoginPage() {
            document.getElementById('login-form').reset();
            document.getElementById('login-container').style.display = 'block';
            document.getElementById('home-page').style.display = 'none';
        }

        // Tambahkan event listener untuk menanggapi tombol "Enter" pada input password
        document.getElementById('password').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                login();
            }
        });
    </script>
</body>
</html>
