<?php

function checkDatabaseConnection($dbHost, $dbName, $dbUser, $dbPassword) {
    try {
        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return true; // Connection successful
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false; // Connection failed
    }
}

// Check for script prerequisites before anything else
function checkPrerequisites() {
    $errors = [];

    // Check if exec() function is available
    if (!function_exists('exec')) {
        $errors[] = "PHP exec() function is disabled. Shell commands cannot be executed.";
    }

    // Check for required PHP modules
    $requiredModules = ['mbstring', 'pdo', 'tokenizer', 'xml', 'ctype', 'json', 'gd', 'pdo_mysql'];
    $missingModules = [];
    foreach ($requiredModules as $module) {
        if (!extension_loaded($module)) {
            $missingModules[] = $module;
        }
    }

    if (count($missingModules) > 0) {
        $errors[] = "Required PHP module(s) missing: " . implode(",", $missingModules);
    }

    // Check for required PHP modules in the CLI environment
    exec('php -m', $cliExtensions);
    $cliExtensions = array_map('strtolower', $cliExtensions);
    $missingModules = [];
    foreach ($requiredModules as $extension) {
        if (!in_array($extension, $cliExtensions)) {
            $missingModules[] = $extension;
        }
    }

    if (count($missingModules) > 0) {
        $errors[] = "Required PHP CLI module(s) missing: " . implode(",", $missingModules);
    }

    // Check if .env can be written to
    if (!is_writable(dirname(__DIR__))) {
        $errors[] = "The script does not have permission to write the .env file in the directory: " . dirname(__DIR__);
    }

    // Check if the mysql command-line tool is available
    exec('mysql --version', $mysqlOutput, $mysqlReturn);
    if ($mysqlReturn !== 0) {
        $errors[] = "MySQL is not installed or not available in the PATH.";
    }

    // Check for either 'unzip' or '7z'
    exec("which unzip", $outputUnzip, $returnUnzip);
    exec("which 7z", $output7z, $return7z);
    if ($returnUnzip != 0 && $return7z != 0) {
        $errors[] = "Neither 'unzip' nor '7z' commands are available.";
    }

    $lockFile = dirname(__DIR__) . '/composer.lock';
    if (!file_exists($lockFile)) {
        $errors[] = "The 'composer.lock' file is missing. Please run 'composer update' in the project root.";
    }

    return $errors;
}

// Sanitize input data
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags($data));
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Install URL Shortener</title>
    <link href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" rel="stylesheet">
    <style>
        /* Overall body styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        /* Form styling */
        form {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 40px auto;
        }

        /* Label styling */
        label {
            margin-bottom: 4px;
            display: block;
            color: #606060;
        }

        .highlight {
            display: inline;
            background: #e5e5e5;
            padding: 2px;
            border-radius: 2px;
        }

        /* Input field styling */
        input[type=text], input[type=password], input[type=email] {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            box-sizing: border-box; /* Makes sure padding doesn't affect the overall width */
        }

        /* Button styling */
        input[type=submit] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type=submit]:hover {
            background-color: #45a049;
        }

        /* Add some spacing to the h2 for better layout */
        h2 {
            text-align: center;
            color: #333;
        }

        .title {
            display: flex;
            align-items: center;
            width: 100%;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .title span {
            margin-right: 5px;
            margin-left: 5px;
        }

        .title::before {
            content: '';
            flex-grow: 1;
            height: 1px;
            background-color: black;
            width: 25px;
            min-width: 15px;
            max-width: 15px;
        }

        .title::after {
            content: '';
            flex-grow: 1;
            height: 1px;
            background-color: black;
        }

        .operations-result {
            text-align: center;
        }

        .footer-support {
            text-align: center;
            font-size: smaller;
        }

        /* Responsive adjustments */
        @media screen and (max-width: 600px) {
            body {
                padding: 10px;
            }
            form {
                width: calc(100% - 60px);
                margin: 10px;
            }
        }
    </style>
</head>
<body>
<h2>Setup URL Shortener</h2>
<div class="operations-result">
<?php

$prerequisiteErrors = checkPrerequisites();
if ($prerequisiteErrors) {
    echo "<div style='color: red; font-weight: bold;'>There are some errors you have to fix in order to use this installer:</div>";
    foreach ($prerequisiteErrors as $error) {
        echo "<div style='color: red'><i class='fas fa-times'></i> $error</div>";
    }
    exit;
}

$websiteTitle = sanitizeInput($_POST['website_title'] ?? '');
$websiteUrl = sanitizeInput($_POST['website_url'] ?? '');
if ($websiteUrl && !preg_match("~^(?:f|ht)tps?://~i", $websiteUrl)) {
    $websiteUrl = "http://" . $websiteUrl;
}
$dbHost = sanitizeInput($_POST['db_host'] ?? '');
$dbName = sanitizeInput($_POST['db_name'] ?? '');
$dbUser = sanitizeInput($_POST['db_user'] ?? '');
$dbPassword = sanitizeInput($_POST['db_password'] ?? '');
$adminEmail = sanitizeInput($_POST['admin_email'] ?? '');
$adminPassword = sanitizeInput($_POST['admin_password'] ?? '');


// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    set_time_limit(500);
    if (!checkDatabaseConnection($dbHost, $dbName, $dbUser, $dbPassword)) {
        echo "<div style='color: red'><i class='fas fa-times'></i> Could not connect to the database with the provided credentials.</div>";
    }else {
        $stepsOutput = [];

        // Perform installation steps
        $hasFailures = false;

        chdir("../");

        // Step 1: Write the .env file
        $envContent = "APP_NAME=\"$websiteTitle\"\nAPP_URL=$websiteUrl\nAPP_KEY=\n\nDB_CONNECTION=mysql\nDB_HOST=$dbHost\nDB_PORT=3306\nDB_DATABASE=$dbName\nDB_USERNAME=$dbUser\nDB_PASSWORD=\"$dbPassword\"\n";
        if (file_put_contents(getcwd() . '/.env', $envContent)) {
            $stepsOutput[] = "Step 1: .env file configuration - Success";
        } else {
            $stepsOutput[] = "Step 1: .env file configuration - Failed";
        }

        // Step 2: Generate application key
        exec('php artisan key:generate --force', $output, $return);
        if ($return === 0) {
            $stepsOutput[] = "Step 2: Laravel key generation - Success";
        } else {
            $stepsOutput[] = "Step 2: Laravel key generation - Failed";
            $hasFailures = true;
        }

        // Step 3: Run migrations
        exec('php artisan migrate --force', $output, $return);
        if ($return === 0) {
            $stepsOutput[] = "Step 3: Database migration - Success";
        } else {
            $stepsOutput[] = "Step 3: Database migration - Failed: " . implode(" | ", $output);
            $hasFailures = true;
        }

        // Step 4: Insert admin email and password
        try {
            $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT); // Hash the password
            $currentDateTime = date('Y-m-d H:i:s');
            $isAdmin = 1;

            $sql = "INSERT INTO users (name, email, email_verified_at, password, is_admin, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['Admin', $adminEmail, $currentDateTime, $hashedPassword, $isAdmin, $currentDateTime, $currentDateTime]);

            $stepsOutput[] = "Step 4: Admin user creation - Success";
        } catch (PDOException $e) {
            $stepsOutput[] = "Step 4: Admin user creation - Failed: " . $e->getMessage();
            $hasFailures = true;
        }

        // Step 5: Seed base settings
        try {
            $settings = [
                ['site_name', $websiteTitle],
                ['qr_eye', 'square'],
                ['qr_quality', 'H'],
                ['qr_style', 'square'],
                ['qr_size', '150'],
                ['social', 'facebook,instagram'],
                ['url_length', '5'],
                ['ad_publisher_id', ''],
                ['ad_top_unit_id', ''],
                ['ad_bottom_unit_id', ''],
                ['ad_left_unit_id', ''],
                ['ad_right_unit_id', ''],
            ];

            $sql = "INSERT INTO settings (`key`, `value`) VALUES (?, ?)";
            $stmt = $pdo->prepare($sql);

            foreach ($settings as $setting) {
                $stmt->execute([$setting[0], $setting[1]]);
            }

            $stepsOutput[] = "Step 5: Base settings seeding - Success";
        } catch (PDOException $e) {
            $stepsOutput[] = "Step 5: Base settings seeding - Failed: " . $e->getMessage();
            $hasFailures = true;
        }

        // Display steps output
        foreach ($stepsOutput as $output) {
            if (strpos($output, 'Failed') !== false) {
                $color = 'red';
                $icon = 'times';
            } else {
                $color = 'green';
                $icon = 'check';
            }

            echo "<div style='color: $color'><i class='fas fa-{$icon}'></i> {$output}</div>";
        }

        if ($hasFailures) {
            echo "Some steps failed. Please check the output for more details.<br>";
        } else {
            echo "Installation completed successfully. You can now navigate to <a href='$websiteUrl'>your website</a> or <a href='{$websiteUrl}/admin'>admin center</a>.<br>";
            echo "Do not forget to remove this file to prevent any issues in future.<br><br>";
            echo "Thanks for using our service.<br>";
        }

        exit;
    }
}
?>
</div>
<form method="post">
    <div class="title"><span>General settings</span></div>
    <label for="website_title">Website Title:</label>
    <input type="text" id="website_title" required name="website_title" value="<?= $websiteTitle ?>"><br>
    <label for="website_url">Website URL:</label>
    <input type="text" id="website_url" required name="website_url" value="<?= $websiteUrl ?>"><br><br>

    <div class="title"><span>Database</span></div>
    <label for="db_host">Database Host (usually <span class="highlight">localhost</span>):</label>
    <input type="text" id="db_host" required name="db_host" value="<?= $dbHost ?>"><br>
    <label for="db_name">Database Name:</label>
    <input type="text" id="db_name" required name="db_name" value="<?= $dbName ?>"><br>
    <label for="db_user">Database User:</label>
    <input type="text" id="db_user" required name="db_user" value="<?= $dbUser ?>"><br>
    <label for="db_password">Database Password:</label>
    <input type="text" id="db_password" required name="db_password"><br><br>

    <div class="title"><span>Admin</span></div>
    <label for="db_password">Admin email:</label>
    <input type="text" id="admin_email" required name="admin_email" value="<?= $adminEmail ?>"><br>
    <label for="db_password">Admin password:</label>
    <input type="text" id="admin_password" required name="admin_password"><br>
    <input type="submit" value="Install">
    <br><br>
    <div class="footer-support">For any problems and questions, feel free to send email to allaboutams@gmail.com</div>
</form>
</body>
</html>
