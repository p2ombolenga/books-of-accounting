<?php
session_start();
if(isset($_SESSION['id'])){
?>

<!-- Display the Balance Sheet -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance Sheet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .double-underline {
      font-weight: bolder;
      text-decoration: underline;
      border-bottom: 3px double; /* Adjust the value to increase/decrease the thickness of the double underline */
    }
    </style>
</head>
<body>
<?php include('navigation.php'); ?>
<?php
// ... (your existing PHP code)
include('connection.php');

// Step 1: Retrieve data from the database
$queryAssets = "SELECT accounts.name, ledgers.balance FROM ledgers INNER JOIN accounts ON ledgers.account = accounts.id WHERE accounts.type = 'Asset' AND LOWER(accounts.name) != 'purchases'";
$queryLiabilitiesEquity = "SELECT accounts.name, ledgers.balance FROM ledgers INNER JOIN accounts ON ledgers.account = accounts.id WHERE accounts.type IN ('Liability', 'Equity')";

$resultAssets = $conn->query($queryAssets);
$resultLiabilitiesEquity = $conn->query($queryLiabilitiesEquity);

// Step 2: Calculate total assets
$totalAssets = 0;
while ($rowAsset = $resultAssets->fetch_assoc()) {
    $totalAssets += $rowAsset['balance'];
}

// Step 3: Calculate total liabilities & equity
$totalLiabilitiesEquity = 0;
while ($rowLiabilityEquity = $resultLiabilitiesEquity->fetch_assoc()) {
    $totalLiabilitiesEquity += $rowLiabilityEquity['balance'];
}

// Step 4: Calculate net income
$queryRevenues = "SELECT accounts.name, ledgers.balance FROM ledgers INNER JOIN accounts ON ledgers.account = accounts.id WHERE accounts.type = 'Income' AND (LOWER(accounts.name) = 'sales' OR accounts.name = 'Sales')";
$queryPurchases = "SELECT accounts.name, ledgers.balance FROM ledgers INNER JOIN accounts ON ledgers.account = accounts.id WHERE accounts.type = 'Asset' AND (LOWER(accounts.name) = 'purchases' OR accounts.name = 'Purchases')";
$queryOtherIncomes = "SELECT accounts.name, ledgers.balance FROM ledgers INNER JOIN accounts ON ledgers.account = accounts.id WHERE accounts.type = 'Income' AND LOWER(accounts.name) != 'sales'";
$queryOtherExpenses = "SELECT accounts.name, ledgers.balance FROM ledgers INNER JOIN accounts ON ledgers.account = accounts.id WHERE accounts.type = 'Expense' AND LOWER(accounts.name) != 'purchases'";

$resultRevenues = $conn->query($queryRevenues);
$resultPurchases = $conn->query($queryPurchases);
$resultOtherIncomes = $conn->query($queryOtherIncomes);
$resultOtherExpenses = $conn->query($queryOtherExpenses);

$totalSales = 0;
$salesAccount = '';
while ($rowRevenue = $resultRevenues->fetch_assoc()) {
    if (strtolower($rowRevenue['name']) === 'sales') {
        $salesAccount = $rowRevenue['name'];
    }
    $totalSales += $rowRevenue['balance'];
}

$totalPurchases = 0;
$purchasesAccount = '';
while ($rowPurchase = $resultPurchases->fetch_assoc()) {
    if (strtolower($rowPurchase['name']) === 'purchases') {
        $purchasesAccount = $rowPurchase['name'];
    }
    $totalPurchases += $rowPurchase['balance'];
}

$grossProfit = $totalSales - $totalPurchases;

$totalOtherIncomes = 0;
while ($rowIncome = $resultOtherIncomes->fetch_assoc()) {
    $totalOtherIncomes += $rowIncome['balance'];
}

$totalOtherExpenses = 0;
while ($rowExpense = $resultOtherExpenses->fetch_assoc()) {
    $totalOtherExpenses += $rowExpense['balance'];
}

$netIncome = $grossProfit + $totalOtherIncomes - $totalOtherExpenses;

?>

    <div class="w-full text-2xl text-center">GRP3 SUBGRP3 LTD</div>
    <div class="w-full text-2xl text-center">Balance Sheet</div>
    <div class="w-full pb-5 text-md text-center">As at <?php echo date('Y-m-d');?></div>
    <div class="py-5 w-full md:w-2/3 lg:w-1/2 mx-auto p-8 border border-gray-200 shadow-md rounded-lg">

    <table class="w-full text-center border mb-2">
        <tr class="border">
            <th class="border py-2">Assets</th>
            <th class="border py-2">Amount</th>
            <th class="border py-2"></th>
        </tr>
        <?php
        // Display asset accounts
        $resultAssets->data_seek(0); // Reset the pointer to the beginning
        while ($rowAsset = $resultAssets->fetch_assoc()) {
            ?>
            <tr class="border">
                <td class="border py-2"><?php echo $rowAsset['name']; ?></td>
                <td class="border py-2"><?php echo $rowAsset['balance'].' RWF'; ?></td>
                <td></td>
            </tr>
            <?php
        }
        ?>
        <tr class="border font-bold">
            <td class="border py-2">Total Assets</td>
            <td></td>
            <td class="border py-2"><span class="double-underline"> <?php echo $totalAssets.' RWF'; ?> </span></td>
        </tr>
    </table>

    <table class="w-full text-center border">
        <tr>
            <th class="border py-2">Liabilities & Equity</th>
            <th class="border py-2">Amount</th>
            <th></th>
        </tr>
        <?php
        // Display liability and equity accounts
        $resultLiabilitiesEquity->data_seek(0); // Reset the pointer to the beginning
        while ($rowLiabilityEquity = $resultLiabilitiesEquity->fetch_assoc()) {
            ?>
            <tr class="border">
                <td class="border py-2"><?php echo $rowLiabilityEquity['name']; ?></td>
                <td class="border py-2"><?php echo $rowLiabilityEquity['balance'].' RWF'; ?></td>
            </tr>
            <?php
        }
        ?>
        <!-- Display Net Income -->
        <tr class="border">
            <td class="border py-2">Net Income or Loss</td>
            <td class="border py-2"><?php echo $netIncome.' RWF'; ?></td>
        </tr>
        <tr class="border font-bold">
            <td class="border py-2">Total Liabilities & Equity</td>
            <td></td>
            <td class="border py-2"><span class="double-underline"> <?php echo $totalLiabilitiesEquity + $netIncome.' RWF'; ?> </span></td>
        </tr>
    </table>
</body>
</html>

<?php 
}
else{
    echo "<script>window.location.href='index.php';</script>";
}
?>