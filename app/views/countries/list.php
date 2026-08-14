<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ciudades por País</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 900px;
            margin: 0 auto;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
        }

        .country-info {
            background: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 0.95em;
        }

        .error {
            background-color: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #667eea;
            color: white;
            font-weight: 600;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .scale-bar {
            width: 100%;
            height: 25px;
            background: #eee;
            border-radius: 3px;
            overflow: hidden;
        }

        .scale-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.8em;
            font-weight: bold;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: opacity 0.3s;
        }

        .back-link:hover {
            opacity: 0.9;
        }

        .no-data {
            text-align: center;
            color: #666;
            padding: 40px;
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            table {
                font-size: 0.9em;
            }

            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏙️ Ciudades por País</h1>

        <?php if (isset($error) && !empty($error)): ?>
            <div class="error">
                <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($cities) && isset($country)): ?>
            <div class="country-info">
                <strong><?php echo htmlspecialchars($country['Name']); ?></strong> 
                (<?php echo htmlspecialchars($country['Code']); ?>)
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Nombre de la Ciudad</th>
                        <th>Población</th>
                        <th>Escala de 10</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cities as $city): 
                        // Calcular escala de 0-10
                        $scale = ($maxPop > 0) ? round(($city['Population'] / $maxPop) * 10, 1) : 0;
                        $percentage = ($scale / 10) * 100;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($city['Name']); ?></td>
                            <td><?php echo number_format($city['Population'], 0, ',', '.'); ?></td>
                            <td>
                                <div class="scale-bar">
                                    <div class="scale-fill" style="width: <?php echo $percentage; ?>%">
                                        <?php echo $scale; ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">
                <p>No se encontraron ciudades para este país.</p>
            </div>
        <?php endif; ?>

        <a href="/" class="back-link">← Volver</a>
    </div>
</body>
</html>
