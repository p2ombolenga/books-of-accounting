<?php
session_start();
if (isset($_SESSION['id'])) {
?>

<!DOCTYPE html>
<html lang="en">
<head>
<style>
  .double-underline {
    font-weight: bolder;
    text-decoration: underline;
    border-bottom: 3px double; /* Adjust the value to increase/decrease the thickness of the double underline */
  }
</style>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Trial Balance</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

<?php include('navigation.php'); ?>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-5">
<div class="w-full text-lg px-8 border border-slate-300 rounded-lg p-8">
<p class="mb-3 text-3xl font-semibold text-gray-900">Welcome, <span class="text-blue-500 text-lg"> <?php echo $_SESSION['username'];?> </span></p>
<p class="my-5 text-gray-500 text-2xl font-semibold">Financial accounting is made easier. Follow the instructions below for this system to generate Books Of Accounting.</p>
<ul class="space-y-3 text-gray-500 list-inside">
    <li class="w-full flex items-center">
        <svg class="w-3.5 h-3.5 mr-2 text-green-500 flex-shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
         </svg>
        Record New Account on <a href="accounts.php" class="text-blue-500 hover:underline px-1">Chart of Accounts</a> Tab
    </li>
    <li class="w-full flex items-center">
        <svg class="w-3.5 h-3.5 mr-2 text-green-500 flex-shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
         </svg>
         Click on <a href="journal-entry.php" class="text-blue-500 hover:underline px-1">Make Journal Entries</a> button Journal Tab
    </li>
    <li class="w-full flex items-center">
        <svg class="w-3.5 h-3.5 mr-2 text-green-500 flex-shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
         </svg>
         View <a href="journal.php" class="text-blue-500 hover:underline px-1">Journal</a>,<a href="ledgers.php" class="text-blue-500 hover:underline px-1">Ledgers</a>,<a href="trialbalance.php" class="text-blue-500 hover:underline px-1">Trial Balance</a>,<a href="incomestatement.php" class="text-blue-500 hover:underline px-1">Income Satement</a>,<a href="balancesheet.php" class="text-blue-500 hover:underline px-1">Balance Sheet</a>
    </li>
    <li class="w-full flex items-center">
         ⚠ View Group Members Names and Reg N<sup class="px-1">o</sup> on <a href="group.php" class="text-blue-500 hover:underline px-1">Group Members</a> Tab
    </li>
</ul>


</div>
<div class="py-5 w-full mx-auto p-8 border border-gray-200 shadow-md rounded-lg border border-slate-300">
    <div class="text-center text-xl text-gray-500 font-semibold">View Balances</div>
    <table class="w-full text-center border">
        <tr class="border">
            <th class="border py-2">Account</th>
            <th class="border py-2">Balance</th>
            </tr>
<?php
include('connection.php');

$display = "SELECT accounts.name, accounts.type,ledgers.balance FROM ledgers INNER JOIN accounts ON ledgers.account = accounts.id WHERE accounts.type = 'Asset' OR accounts.type = 'Equity' OR accounts.type = 'Liability' ";

$result = $conn->query($display);

if ($result->num_rows > 0) {
    // output data of each row
            while ($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td class="border py-2"><?php echo $row['name']; ?></td>
                    <td class="border py-2"><?php echo $row['balance'].' RWF'; ?></td>
                </tr>

                <?php
            }
            ?>
    <?php
}
?>
</table>
</div>
</div>

</body>
</html>

<?php 
} else {
    echo "<script>window.location.href='index.php';</script>";
}
?>
