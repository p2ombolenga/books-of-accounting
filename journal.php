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
    <title>Journal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <?php include('navigation.php'); ?>
    <div class="w-full px-5 flex justify-end"><a href="journal-entry.php" class="bg-blue-500 text-white px-3 py-2">Make Journal Entries</a></div>
  <div class="w-full text-2xl text-center">GRP3 SUBGRP3 LTD</div>
  <div class="w-full text-2xl text-center">Journal</div>
  <div class="w-full pb-5 text-md text-center">As at <?php echo date('Y-m-d');?></div>
<?php
include('connection.php');
$display = "SELECT accounts.name,accounts.type,journals.debit,journals.credit,journals.description,journals.created_at from journals inner join accounts on journals.account = accounts.id";
$result = $conn->query($display);


if ($result->num_rows > 0) {
    // output data of each row
    ?>

  <div class="mb-20 py-5 w-full md:w-10/12 md:p-8 border border-gray-200 shadow-md rounded-lg md:mx-auto">

    <table class="w-full text-center border border-black">
        <tr class="border">
            <th class="border">Date</th>
            <th class="border">Account</th>
            <th class="border">Description</th>
            <th class="border">Debit</th>
            <th class="border">Credit</th>
        </tr>
    <?php
    $sum_debit_side = 0;
    $sum_credit_side = 0;
    while($row = $result->fetch_assoc()) {
        ?>
        <tr class="border">
            <td class="border py-2">
              <?php 
              $dateFromDB = $row['created_at'];
              $formattedDate = date('Y-m-d', strtotime($dateFromDB));
              echo $formattedDate; 
              ?>
            </td>
            <td class="border py-2"><?php echo $row['name'];?></td>
            <td class="border py-2"><?php echo $row['description'];?></td>
            <?php $sum_debit_side += $row['debit']; ?>
            <td class="border py-2"> <?php if($row['debit'] != 0){ echo $row['debit'].' RWF'; } ?> </td>
            <?php $sum_credit_side += $row['credit']; ?>
            <td class="border py-2"> <?php if($row['credit'] != 0){ echo $row['credit'].' RWF'; } ?> </td>
        </tr>
        <?php
    }
    ?>
    <tr class="border">
        <td class="font-bold py-2">Total</td>
        <td class="py-2"></td>
        <td></td>
        <td class="py-2 border"><span class="double-underline"> <?php echo $sum_debit_side ?> RWF</span></td>
        <td class="py-2 border"><span class="double-underline"> <?php echo $sum_credit_side ?> RWF</span></td>
    </tr>
    </table>
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