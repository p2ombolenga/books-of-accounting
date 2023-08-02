<?php
session_start();
if(isset($_SESSION['id'])){
?>

<!DOCTYPE html>
<html lang="en">
<head>
<style>
  .overline {
    text-decoration: overline;
  }
</style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>General Ledger</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <?php include('navigation.php'); ?>

    <div class="w-full text-2xl text-center">GRP3 SUBGRP3 LTD</div>
    <div class="w-full text-2xl text-center">General Ledger</div>
    <div class="w-full pb-5 text-md text-center">As at <?php echo date('Y-m-d');?></div>
<?php
include('connection.php');

$accountid = "SELECT * from accounts";
$eachid = $conn->query($accountid);

while($row4 = mysqli_fetch_array($eachid)){
    $realaccountid = $row4['id'];

    $display = "SELECT accounts.name, accounts.type, journals.description, journals.debit, journals.credit 
                FROM journals 
                INNER JOIN accounts ON journals.account = accounts.id 
                WHERE accounts.id = $realaccountid";
    $result = $conn->query($display);

    if ($result->num_rows > 0) {
        // output data of each row
        ?>
        <div class="w-1/2 mx-auto pb-5 text-2xl text-center"><?php echo $row4['name']; ?></div>
        <div class="mb-5 py-5 w-full md:w-2/3 lg:w-1/2 mx-auto p-5 border border-gray-200 shadow-md rounded-lg">

            <table class="w-full text-center border border-black">
                <tr class="border">
                    <th class="border">Description</th>
                    <th class="border">DEBIT</th>
                    <th class="border">CREDIT</th>
                </tr>
                <?php
                $debitsum = 0;

                $creditsum = 0;
                while($row = $result->fetch_assoc()) {
                    ?>
                    <tr class="border">
                        <td class="border py-2"><?php echo $row['description']; ?></td>
                        <td class="border py-2"><?php if($row['debit'] != 0){ echo $row['debit'].' RWF'; } ?></td>
                        <?php $debitsum = $debitsum + $row['debit']; ?>
                        <td class="border py-2"><?php if($row['credit'] != 0){ echo $row['credit'].' RWF'; } ?></td>
                        <?php $creditsum = $creditsum + $row['credit']; ?>
                    </tr>
                    <?php
                    $type = $row['type'];
                }
                ?>
                <tr>
                    <td class="border font-bold py-2">Balance</td>
                    <?php $balance = $debitsum - $creditsum; ?>
                    <td class="border py-2 font-bold"> <span class="overline"> <?php if($type == "Bank" || $type == "Asset" || $type == "Expense"){ echo $balance.' RWF'; } ?> </span> </td>
                    <td class="border py-2 font-bold"> <span class="overline"> <?php if($type == "Liability" || $type == "Equity" || $type == "Income"){ echo -1 * $balance.' RWF'; } ?> </span> </td>
                </tr>
            </table>
        </div>
        <?php
    }
}
?>

</body>
</html>

<?php 
}
else{
    echo "<script>window.location.href='index.php';</script>";
}
?>