<?php
session_start();
if(isset($_SESSION['id'])){
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

    <div class="w-full text-2xl text-center">GRP3 SUBGRP3 LTD</div>
    <div class="w-full text-2xl text-center">TRIAL BALANCE</div>
    <div class="w-full pb-5 text-md text-center">As at <?php echo date('Y-m-d');?></div>
<?php
include('connection.php');
$display = "SELECT accounts.name,accounts.type,ledgers.balance from ledgers inner join accounts on ledgers.account = accounts.id";
$result = $conn->query($display);


if ($result->num_rows > 0) {
    // output data of each row
    ?>
    
    <div class="mb-20 py-5 w-full md:w-2/3 lg:w-1/2 mx-auto p-8 border border-gray-200 shadow-md rounded-lg">

            <table class="w-full text-center border">
                <tr class="border">
                    <th class="border py-2">Account</th>
                    <th class="border py-2">DEBIT</th>
                    <th class="border py-2">CREDIT</th>
                </tr>
            <?php
            $sum_debit_balance = 0;
            $sum_credit_balance = 0;
            while($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td class="border py-2"><?php echo $row['name'];?></td>
                    <?php  ?>
                    <td class="border py-2">
                        <?php if($row['type'] == "Bank" || $row['type'] == "Asset" || $row['type'] == "Expense"){ 
                            echo $row['balance'].' RWF';
                            $sum_debit_balance += $row['balance'];
                            } ?>
                    </td>
                    <td class="border py-2">
                        <?php if($row['type'] == "Liability" || $row['type'] == "Income" || $row['type'] == "Equity"){ 
                        echo $row['balance'].' RWF';
                        $sum_credit_balance += $row['balance'];
                        } ?>
                    </td>
                </tr>
                <?php
            }
            ?>
            <tr>
                <td class="border py-2 font-bold">Total</td>
                <td class="border py-2"><span class="double-underline"> <?php echo $sum_debit_balance ?> RWF</span></td>
                <td class="border py-2"><span class="double-underline"> <?php echo $sum_credit_balance ?> RWF</span></td>
            </tr>
            </table>
    </div>
    <?php
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