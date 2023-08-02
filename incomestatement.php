<?php
session_start();
if(isset($_SESSION['id'])){
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income Statement</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<?php include('navigation.php'); ?>
<?php
// ... (your existing PHP code)
include('connection.php');

// Step 1: Retrieve data from the database
$queryRevenues = "SELECT accounts.name, ledgers.balance FROM ledgers INNER JOIN accounts ON ledgers.account = accounts.id WHERE accounts.type = 'Income' AND (LOWER(accounts.name) = 'sales' OR accounts.name = 'Sales')";
$queryPurchases = "SELECT accounts.name, ledgers.balance FROM ledgers INNER JOIN accounts ON ledgers.account = accounts.id WHERE accounts.type = 'Asset' AND (LOWER(accounts.name) = 'purchases' OR accounts.name = 'Purchases')";
$queryOtherIncomes = "SELECT accounts.name, ledgers.balance FROM ledgers INNER JOIN accounts ON ledgers.account = accounts.id WHERE accounts.type = 'Income' AND LOWER(accounts.name) != 'sales'";
$queryOtherExpenses = "SELECT accounts.name, ledgers.balance FROM ledgers INNER JOIN accounts ON ledgers.account = accounts.id WHERE accounts.type = 'Expense' AND LOWER(accounts.name) != 'purchases'";

$resultRevenues = $conn->query($queryRevenues);
$resultPurchases = $conn->query($queryPurchases);
$resultOtherIncomes = $conn->query($queryOtherIncomes);
$resultOtherExpenses = $conn->query($queryOtherExpenses);

// Step 2: Calculate total sales
$totalSales = 0;
$salesAccount = '';
while ($rowRevenue = $resultRevenues->fetch_assoc()) {
    if (strtolower($rowRevenue['name']) === 'sales') {
        $salesAccount = $rowRevenue['name'];
    }
    $totalSales += $rowRevenue['balance'];
}

// Step 3: Calculate total purchases
$totalPurchases = 0;
$purchasesAccount = '';
while ($rowPurchase = $resultPurchases->fetch_assoc()) {
    if (strtolower($rowPurchase['name']) === 'purchases') {
        $purchasesAccount = $rowPurchase['name'];
    }
    $totalPurchases += $rowPurchase['balance'];
}

// Step 4: Calculate gross profit
$grossProfit = $totalSales - $totalPurchases;

// Step 5: Calculate total other incomes
$totalOtherIncomes = 0;
while ($rowIncome = $resultOtherIncomes->fetch_assoc()) {
    $totalOtherIncomes += $rowIncome['balance'];
}

// Step 6: Calculate total other expenses
$totalOtherExpenses = 0;
while ($rowExpense = $resultOtherExpenses->fetch_assoc()) {
    $totalOtherExpenses += $rowExpense['balance'];
}

// Step 7: Calculate net profit
$netProfit = $grossProfit + $totalOtherIncomes - $totalOtherExpenses;

?>

<!-- Display the Income Statement -->
<div class="w-full text-2xl text-center">GRP3 SUBGRP3 LTD</div>
<div class="w-1/2 mx-auto text-2xl text-center">Income Statement</div>
<div class="w-full pb-5 text-md text-center">As at <?php echo date('Y-m-d');?></div>
<div class="py-5 w-full md:w-2/3 lg:w-1/2 mx-auto p-8 border border-gray-200 shadow-md rounded-lg">
    <table class="w-full text-center border border-black">
        <tr class="border">
            <th class="border py-2"></th>
            <th class="border py-2">Amount</th>
            <th></th>
        </tr>
        <tr class="border">
            <td class="border py-2">Net Sales</td>
            <td class="border py-2"><?php echo $totalSales.' RWF'; ?></td>
        </tr>
        <tr class="border">
            <td class="border py-2">Net Purchases</td>
            <td class="border py-2"><?php echo $totalPurchases.' RWF'; ?></td>
        </tr>
        <tr class="border">
            <td class="border font-bold py-2">Gross Profit</td>
            <td class="border font-bold py-2"></td>
            <td class="border font-bold py-2"><?php echo $grossProfit.' RWF'; ?></td>
        </tr>
        <?php
        // Display other income accounts
        $resultOtherIncomes->data_seek(0); // Reset the pointer to the beginning
        while ($rowIncome = $resultOtherIncomes->fetch_assoc()) {
            if (strtolower($rowIncome['name']) !== 'sales') {
                ?>
                <tr class="border">
                    <td class="border py-2"><?php echo $rowIncome['name']; ?></td>
                    <td class="border py-2"><?php echo $rowIncome['balance'].' RWF'; ?></td>
                </tr>
                <?php
            }
        }
        ?>
        <tr class="border">
            <td class="border font-bold py-2">Total Other Incomes</td>
            <td class="border font-bold py-2"></td>
            <td class="border font-bold py-2"><?php echo $totalOtherIncomes.' RWF'; ?></td>
        </tr>
        <?php
        // Display other expense accounts
        $resultOtherExpenses->data_seek(0); // Reset the pointer to the beginning
        while ($rowExpense = $resultOtherExpenses->fetch_assoc()) {
            if (strtolower($rowExpense['name']) !== 'purchases') {
                ?>
                <tr class="border">
                    <td class="border py-2"><?php echo $rowExpense['name']; ?></td>
                    <td class="border py-2"><?php echo $rowExpense['balance'].' RWF'; ?></td>
                </tr>
                <?php
            }
        }
        ?>
        <tr class="border">
            <td class="border font-bold py-2">Total Other Expenses</td>
            <td class="border font-bold py-2"></td>
            <td class="border font-bold py-2 underline"><?php echo $totalOtherExpenses.' RWF'; ?></td>
        </tr>
        <tr>
            <td class="border font-bold py-2">Net Profit/Loss</td>
            <td class="border font-bold py-2"></td>
            <td class="border font-bold py-2 text-xl underline"><?php echo $netProfit.' RWF'; ?></td>
        </tr>
    </table>
</div>
</body>
</html>

<?php 
}
else{
    echo "<script>window.location.href='index.php';</script>";
}
?>