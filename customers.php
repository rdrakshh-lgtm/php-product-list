```php
<?php
$customers = [
    ["id" => 1, "name" => "Rahul Kumar", "email" => "rahul@example.com", "city" => "Belagavi"],
    ["id" => 2, "name" => "Priya Sharma", "email" => "priya@example.com", "city" => "Hubballi"],
    ["id" => 3, "name" => "Amit Patel", "email" => "amit@example.com", "city" => "Bengaluru"],
    ["id" => 4, "name" => "Sneha Rao", "email" => "sneha@example.com", "city" => "Mysuru"],
    ["id" => 5, "name" => "Kiran Desai", "email" => "kiran@example.com", "city" => "Dharwad"],
    ["id" => 6, "name" => "Ananya Joshi", "email" => "ananya@example.com", "city" => "Pune"],
    ["id" => 7, "name" => "Vikram Singh", "email" => "vikram@example.com", "city" => "Mumbai"],
    ["id" => 8, "name" => "Meera Nair", "email" => "meera@example.com", "city" => "Chennai"],
    ["id" => 9, "name" => "Arjun Verma", "email" => "arjun@example.com", "city" => "Hyderabad"],
    ["id" => 10, "name" => "Pooja Kulkarni", "email" => "pooja@example.com", "city" => "Goa"]
];

$search = trim($_GET["search"] ?? "");

if ($search !== "") {
    $customers = array_filter($customers, function ($customer) use ($search) {
        return stripos($customer["name"], $search) !== false
            || stripos($customer["email"], $search) !== false
            || stripos($customer["city"], $search) !== false;
    });

    $customers = array_values($customers);
}

$perPage = 5;
$totalCustomers = count($customers);
$totalPages = max(1, (int) ceil($totalCustomers / $perPage));

$page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
$page = ($page !== false && $page !== null && $page > 0) ? $page : 1;
$page = min($page, $totalPages);

$offset = ($page - 1) * $perPage;
$visibleCustomers = array_slice($customers, $offset, $perPage);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Directory</title>
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 35px 16px;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            padding: 28px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px #00000010;
        }

        h1 { color: #2563eb; }

        .search-form {
            display: flex;
            gap: 10px;
            margin: 24px 0;
        }

        input {
            flex: 1;
            min-width: 0;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 16px;
        }

        button, .pagination a {
            padding: 11px 16px;
            border: none;
            border-radius: 6px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            cursor: pointer;
        }

        .clear { background: #64748b; }

        .table-wrapper { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 14px;
            border: 1px solid #e2e8f0;
            text-align: left;
        }

        th { background: #2563eb; color: white; }

        .summary { margin: 20px 0; color: #475569; }

        .pagination {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 24px;
        }

        .pagination .active { background: #0f172a; }

        .disabled {
            opacity: 0.45;
            pointer-events: none;
        }

        @media (max-width: 550px) {
            .container { padding: 18px; }
            .search-form { flex-wrap: wrap; }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Customer Directory</h1>
    <p>Search customers by name, email, or city.</p>

    <form class="search-form" method="GET" action="customers.php">
        <input
            type="search"
            name="search"
            placeholder="Search customers..."
            value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>"
        >
        <button type="submit">Search</button>
        <a class="pagination clear" href="customers.php">Clear</a>
    </form>

    <p class="summary">
        Showing <?= count($visibleCustomers) ?> of <?= $totalCustomers ?> matching customers.
        Page <?= $page ?> of <?= $totalPages ?>.
    </p>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>City</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!$visibleCustomers): ?>
                <tr>
                    <td colspan="4">No matching customers found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($visibleCustomers as $customer): ?>
                    <tr>
                        <td><?= $customer["id"] ?></td>
                        <td><?= htmlspecialchars($customer["name"], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($customer["email"], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($customer["city"], ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <nav class="pagination" aria-label="Customer pages">
        <?php
        $baseUrl = "customers.php?search=" . urlencode($search) . "&page=";
        ?>

        <a class="<?= $page <= 1 ? 'disabled' : '' ?>"
           href="<?= $baseUrl . max(1, $page - 1) ?>">Previous</a>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a class="<?= $i === $page ? 'active' : '' ?>"
               href="<?= $baseUrl . $i ?>"><?= $i ?></a>
        <?php endfor; ?>

        <a class="<?= $page >= $totalPages ? 'disabled' : '' ?>"
           href="<?= $baseUrl . min($totalPages, $page + 1) ?>">Next</a>
    </nav>
</div>
</body>
</html>
```
