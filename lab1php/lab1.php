<?php
session_start(); 

// Літерал нумерованого масиву
if (!isset($_SESSION['vehicles'])) {
    $_SESSION['vehicles'] = [
        [
            'code' => '1',
            'owner' => 'Вікторович Арсен Грігащ',
            'brand' => 'Toyota',
            'number' => 'AB1234CD',
            'color' => 'Red'
        ],
        [
            'code' => '2',
            'owner' => 'Антонов Денис Голуб',
            'brand' => 'Honda',
            'number' => 'BC5678EF',
            'color' => 'Blue'
        ],
        [
            'code' => '3',
            'owner' => 'Сергійович Олег Шикула',
            'brand' => 'Toyota',
            'number' => 'CA9101GH',
            'color' => 'Green'
        ],
        [
            'code' => '4',
            'owner' => 'Дмитровна Ольга Сідун',
            'brand' => 'Ford',
            'number' => 'DA2345JK',
            'color' => 'Black'
        ],
        [
            'code' => '5',
            'owner' => 'Георгіївна Марія Головач',
            'brand' => 'Toyota',
            'number' => 'AB3456CD',
            'color' => 'White'
        ]
    ];
}

// Функція для відображення транспортних засобів
function displayVehicles($vehicles) {
    echo '<table border="1">';
    echo '<thead>';
    echo '<tr><th>Код</th><th>Власник</th><th>Марка</th><th>Номер</th><th>Колір</th><th>Дії</th></tr>';
    echo '</thead>';
    echo '<tbody>';
    foreach ($vehicles as $index => $vehicle) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($vehicle['code']) . '</td>';
        echo '<td>' . htmlspecialchars($vehicle['owner']) . '</td>';
        echo '<td>' . htmlspecialchars($vehicle['brand']) . '</td>';
        echo '<td>' . htmlspecialchars($vehicle['number']) . '</td>';
        echo '<td>' . htmlspecialchars($vehicle['color']) . '</td>';
        echo '<td><a href="?edit=' . $index . '">Оновити</a></td>'; 
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
}

// Функція для фільтрації транспортних засобів
function filterVehicles($vehicles, $brand, $numberPattern) {
    return array_filter($vehicles, function($vehicle) use ($brand, $numberPattern) {
        return $vehicle['brand'] === $brand && strpos($vehicle['number'], $numberPattern) === 0;
    });
}

// Обробка запиту на фільтрацію
$vehicles = $_SESSION['vehicles'];

if (isset($_GET['brand']) && isset($_GET['numberPattern'])) {
    $brand = $_GET['brand'];
    $numberPattern = $_GET['numberPattern'];

    $filteredVehicles = filterVehicles($vehicles, $brand, $numberPattern);
    displayVehicles($filteredVehicles);
} else {
    displayVehicles($vehicles);
}

// Обробка форми для додавання нового транспортного засобу
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['edit_index'])) {
    $code = $_POST['code'];
    $owner = $_POST['owner'];
    $brand = $_POST['brand'];
    $number = $_POST['number'];
    $color = $_POST['color'];

    // Валідація даних
    if (empty($code) || empty($owner) || empty($brand) || empty($number) || empty($color)) {
        echo "Всі поля обов'язкові для заповнення.";
    } else {
        // Додавання нового транспортного засобу
        $_SESSION['vehicles'][] = [
            'code' => $code,
            'owner' => $owner,
            'brand' => $brand,
            'number' => $number,
            'color' => $color
        ];
        echo "Транспортний засіб додано успішно";
    }
}

// Обробка запиту на редагування
if (isset($_GET['edit'])) {
    $editIndex = (int)$_GET['edit'];
    $editVehicle = $_SESSION['vehicles'][$editIndex];
}

// Обробка форми для редагування транспортного засобу
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_index'])) {
    $editIndex = (int)$_POST['edit_index'];
    $code = $_POST['code'];
    $owner = $_POST['owner'];
    $brand = $_POST['brand'];
    $number = $_POST['number'];
    $color = $_POST['color'];

    // Валідація даних
    if (empty($code) || empty($owner) || empty($brand) || empty($number) || empty($color)) {
        echo "Всі поля обов'язкові для заповнення.";
    } else {
        // Оновлення транспортного засобу
        $_SESSION['vehicles'][$editIndex] = [
            'code' => $code,
            'owner' => $owner,
            'brand' => $brand,
            'number' => $number,
            'color' => $color
        ];
        echo "Транспортний засіб оновлено успішно";
        header("Location: index.php"); // Перенаправлення після оновлення
        exit;
    }
}
?>

<form method="post">
    <label for="code">Код</label>
    <input name="code" value="<?php echo isset($editVehicle) ? htmlspecialchars($editVehicle['code']) : ''; ?>"/><br/>
    <label for="owner">ПІБ Власника</label>
    <input name="owner" value="<?php echo isset($editVehicle) ? htmlspecialchars($editVehicle['owner']) : ''; ?>"/><br/>
    <label for="brand">Марка</label>
    <input name="brand" value="<?php echo isset($editVehicle) ? htmlspecialchars($editVehicle['brand']) : ''; ?>"/><br/>
    <label for="number">Номер</label>
    <input name="number" value="<?php echo isset($editVehicle) ? htmlspecialchars($editVehicle['number']) : ''; ?>"/><br/>
    <label for="color">Колір</label>
    <input name="color" value="<?php echo isset($editVehicle) ? htmlspecialchars($editVehicle['color']) : ''; ?>"/><br/>
    <?php if (isset($editVehicle)): ?>
        <input type="hidden" name="edit_index" value="<?php echo htmlspecialchars($editIndex); ?>"/>
        <button type="submit">Оновити</button><br/>
    <?php else: ?>
        <button type="submit">Додати</button><br/>
    <?php endif; ?>
</form>
