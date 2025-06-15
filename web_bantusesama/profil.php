<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Donasi</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        
        .profile-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .donation-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .donation-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px;
            transition: transform 0.3s ease;
        }
        
        .donation-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        
        .donation-info {
            margin: 8px 0;
            font-size: 0.9rem;
        }
        
        .donation-target {
            color: #27ae60;
        }
        
        .donation-progress {
            color: #3498db;
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
            margin-top: 15px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.2s;
        }
        
        .btn-update {
            background-color: #3498db;
            color: white;
        }
        
        .btn-delete {
            background-color: #e74c3c;
            color: white;
        }
        
        .btn-chart {
            background-color: #9b59b6;
            color: white;
        }
        
        .btn-home {
            background-color: #2ecc71;
            color: white;
        }
        
        .btn-show-all {
            background-color: #f39c12;
            color: white;
        }
        
        .chart-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            display: none;
        }
        
        .chart-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow: auto;
        }
        
        .close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 1.5rem;
            cursor: pointer;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="profile-container">
        <?php
        $host = "localhost";
        $username = "root";
        $passwordd = "";
        $database = "wbs";
        
        $koneksi = mysqli_connect($host, $username, $passwordd, $database);
        if (!$koneksi) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        if(isset($_SESSION['username'])) {
            $session_username = $_SESSION['username'];
            $query = "SELECT `Nama`, `Pekerjaan`, `Ussername` FROM `data_donatur` WHERE `Ussername` = ?";
            $stmt = mysqli_prepare($koneksi, $query);
            mysqli_stmt_bind_param($stmt, "s", $session_username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if ($row = mysqli_fetch_assoc($result)) {
                echo "<h2>Profil Donatur</h2>";
                echo "<p><strong>Nama:</strong> " . htmlspecialchars($row['Nama']) . "</p>";
                echo "<p><strong>Pekerjaan:</strong> " . htmlspecialchars($row['Pekerjaan']) . "</p>";
                echo "<p><strong>Username:</strong> " . htmlspecialchars($row['Ussername']) . "</p>";
            }
        }
        ?>
        <div class="action-buttons">
            <a href="sesudahlogin.php" class="btn btn-home">Home</a>
            <button onclick="showAllCharts()" class="btn btn-show-all">Tampilkan Grafik</button>
        </div>
    </div>
    
    <h2>Daftar Donasi</h2>
    
    <div class="donation-container">
        <?php
        $query_select = "SELECT `id`, `Nama_Donasi`, `Target_Donasi`, `Progress`, `Penerima` 
                         FROM `data_donasi` 
                         WHERE `Ussername` = '$session_username'";
        
        $result = mysqli_query($koneksi, $query_select);
        
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="donation-card">';
                echo '<div class="donation-title">' . htmlspecialchars($row['Nama_Donasi']) . '</div>';
                echo '<div class="donation-info donation-target">Target: Rp ' . number_format($row['Target_Donasi']) . '</div>';
                echo '<div class="donation-info donation-progress">Progress: Rp ' . number_format($row['Progress']) . '</div>';
                echo '<div class="donation-info">Penerima: ' . htmlspecialchars($row['Penerima']) . '</div>';
                echo '<div class="action-buttons">';
                echo '<a href="update.php?id=' . $row['id'] . '" class="btn btn-update">Update</a>';
                echo '<a href="delete.php?id=' . $row['id'] . '" class="btn btn-delete" onclick="return confirm(\'Yakin ingin menghapus?\')">Delete</a>';
                echo '<button onclick="showSingleChart(' . $row['id'] . ', \'' . addslashes($row['Nama_Donasi']) . '\', ' . $row['Target_Donasi'] . ', ' . $row['Progress'] . ')" class="btn btn-chart">Grafik</button>';
                echo '</div>';
                echo '</div>';
            }
        }
        
        mysqli_close($koneksi);
        ?>
    </div>
    
    <!-- Chart Modal -->
    <div id="chartModal" class="chart-modal">
        <div class="chart-content">
            <span class="close-btn" onclick="closeChart()">&times;</span>
            <canvas id="progressChart"></canvas>
        </div>
    </div>

    <script>
        let currentChart = null;
        
        function showAllCharts() {
            fetch('get_donations.php')
                .then(response => response.json())
                .then(data => {
                    drawChart(data, 'Semua Donasi');
                    document.getElementById('chartModal').style.display = 'flex';
                })
                .catch(error => console.error('Error:', error));
        }
        
        function showSingleChart(id, name, target, progress) {
            const data = {
                labels: [name],
                targets: [target],
                progresses: [progress]
            };
            drawChart(data, name);
            document.getElementById('chartModal').style.display = 'flex';
        }
        
        function drawChart(data, title) {
            const ctx = document.getElementById('progressChart').getContext('2d');
            
            if (currentChart) {
                currentChart.destroy();
            }
            
            currentChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Target Donasi',
                            data: data.targets,
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Progress',
                            data: data.progresses,
                            backgroundColor: 'rgba(75, 192, 192, 0.7)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: title,
                            font: {
                                size: 16
                            }
                        },
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': Rp ' + context.raw.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }
        
        function closeChart() {
            document.getElementById('chartModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('chartModal');
            if (event.target == modal) {
                closeChart();
            }
        }
    </script>
</body>
</html>