<?php
session_start();
if(isset($_SESSION['id'])){
?>

<?php include('connection.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Journal Entries</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <?php include('navigation.php'); ?>
        <div class="w-full pb-5 text-2xl text-center"> Make Journal Entries </div>
        <div class="mb-20 py-5 w-full md:w-10/12 md:p-8 border border-gray-200 shadow-md rounded-lg md:mx-auto">

            <form action="" method="post">
                <table class="w-full text-center border border-black">
                    <tr>
                        <th class="border">Account Name</th>
                        <th class="border">Description</th>
                        <th class="border">Debit (RWF)</th>
                        <th class="border">Credit (RWF)</th>
                    </tr>
                    <?php 

                        $query = "SELECT * FROM accounts";
                        $query2 = "SELECT * FROM accounts";
                        $results = $conn->query($query);
                        $results2 = $conn->query($query2);

                    ?>
                    <tr class="border ">
                        <td>
                            <select name="account1" id="account1" class="w-full border border-gray-300 py-2 px-1">
                                <?php
                                    while($row = mysqli_fetch_array($results)){
                                        ?>
                                        <option value="<?php echo $row['id'];?>"> <?php echo $row['name'];?> </option>
                                    <?php
                                    }
                                ?>
                            </select>
                        </td>
                        <td><input type="text" name="description1" id="description1" placeholder="Write Description" class="w-full border border-gray-300 py-2 px-1"></td>
                        <td><input type="number" name="debit" id="debit" placeholder="Enter Debit Amount" class="w-full border border-gray-200 py-2 px-1"></td>
                        <td></td>
                    </tr>
                    <tr class="border">
                        <td>
                            <select name="account2" id="account2" class="w-full border border-gray-300 py-2 px-1">
                                <?php
                                    while($row2 = mysqli_fetch_array($results2)){
                                        ?>
                                        <option value="<?php echo $row2['id'];?>"> <?php echo $row2['name'];?> </option>
                                    <?php
                                    }
                                ?>
                            </select>
                        </td>
                        <td><input type="text" name="description2" id="description2" placeholder="Write Description" class="w-full border border-gray-300 py-2 px-1"></td>
                        <td></td>
                        <td><input type="number" name="credit" id="credit" placeholder="Enter Credit Amount" class="w-full border border-gray-300 py-2 px-1"></td>
                    </tr>
                    <tr class="border">
                        <td colspan="4" class="mx-auto py-3"><button type="submit" name="enter" class="bg-blue-500 text-white px-3 py-2">Record Journal Entry</button></td>
                    </tr>
                </table>
            </form>
        </div>
    </body>
</html>
        
        

        <?php
// ... (your existing PHP code)

function updateOrInsertAccount($account, $amount, $type, $transactionType, $conn) {
    $searchQuery = "SELECT * FROM ledgers WHERE account = '$account'";
    $result = $conn->query($searchQuery);

    if ($result->num_rows > 0) {
        // Account exists in ledgers table, update the balance
        $row = $result->fetch_assoc();
        $oldBalance = $row['balance'];

        // Calculate the new balance based on the account type and the transaction type (debit or credit)
        if (($type === "Asset" || $type === "Expense" || $type === "Bank") && $transactionType === "debit") {
            $newBalance = $oldBalance + $amount;
        } elseif (($type === "Liability" || $type === "Equity" || $type === "Income") && $transactionType === "debit") {
            $newBalance = $oldBalance - $amount;
        } elseif (($type === "Asset" || $type === "Expense" || $type === "Bank") && $transactionType === "credit") {
            $newBalance = $oldBalance - $amount;
        } elseif (($type === "Liability" || $type === "Equity" || $type === "Income") && $transactionType === "credit") {
            $newBalance = $oldBalance + $amount;
        }

        // Adjust the new balance in the ledgers table
        $updateQuery = "UPDATE ledgers SET balance = '$newBalance' WHERE account = '$account'";
        $conn->query($updateQuery);
    } else {
        // Account doesn't exist in ledgers table, insert the account with the initial balance
        $initialBalance = 0;

        // Set the initial balance based on the account type and the transaction type
        if (($type === "Asset" || $type === "Expense" || $type === "Bank") && $transactionType === "debit") {
            $initialBalance = $amount;
        } elseif (($type === "Liability" || $type === "Equity" || $type === "Income") && $transactionType === "debit") {
            $initialBalance = -$amount;
        } elseif (($type === "Asset" || $type === "Expense" || $type === "Bank") && $transactionType === "credit") {
            $initialBalance = -$amount;
        } elseif (($type === "Liability" || $type === "Equity" || $type === "Income") && $transactionType === "credit") {
            $initialBalance = $amount;
        }

        // Insert the new account into the ledgers table
        $insertQuery = "INSERT INTO ledgers (account, balance) VALUES ('$account', '$initialBalance')";
        $conn->query($insertQuery);
    }
}

if (isset($_POST['enter'])) {
    $account1 = $_POST['account1'];
    $description1 = $_POST['description1'];
    $debit = $_POST['debit'];
    $account2 = $_POST['account2'];
    $description2 = $_POST['description2'];
    $credit = $_POST['credit'];


      // Validation: $debit and $credit must be numbers greater than 0 and not empty
      if (!is_numeric($debit) || !is_numeric($credit) || $debit <= 0 || $credit <= 0) {
        echo "<script>alert('Debit and Credit Amount must be numeric values greater than 0.');";
        echo "window.location.href='journal-entry.php';</script>";
        exit; // Stop further execution
    }

    // Validation: $debit and $credit cannot be equal
    if ($debit != $credit) {
        echo "<script>alert('Debit and Credit Amount must be equal.');";
        echo "window.location.href='journal-entry.php';</script>";
        exit; // Stop further execution
    }

    // Validation: $account1 and $account2 cannot be the same
    if ($account1 === $account2) {
        echo "<script>alert('Accounts must be different.');";
        echo "window.location.href='journal-entry.php';</script>";
        exit; // Stop further execution
    }

    // Assuming you have the account type information for the accounts in $account1 and $account2
    // Replace 'account_type1' and 'account_type2' with the actual column names in your 'accounts' table that represent the account type.
    // For example, if the account type is stored in a column called 'type', then you should use $row['type'] instead of $row['account_type1'].
    $query1 = "SELECT type FROM accounts WHERE id = '$account1'";
    $query2 = "SELECT type FROM accounts WHERE id = '$account2'";
    $result1 = $conn->query($query1);
    $result2 = $conn->query($query2);
    $row1 = $result1->fetch_assoc();
    $row2 = $result2->fetch_assoc();
    $type1 = $row1['type'];
    $type2 = $row2['type'];

    $currentDate = date('Y-m-d');
    // Insert the information for both account1 and account2 into the journals table
    $entry1 = "INSERT INTO journals(account, description, debit, credit, created_at) VALUES('$account1', '$description1', '$debit', 0, '$currentDate')";
    $entry2 = "INSERT INTO journals(account, description, debit, credit, created_at) VALUES('$account2', '$description2', 0, '$credit', '$currentDate')";

    // Execute the entry queries
    $done1 = $conn->query($entry1);
    $done2 = $conn->query($entry2);

    // Update or insert account data into the ledgers table for account1
    updateOrInsertAccount($account1, $debit, $type1, "debit", $conn);

    // Update or insert account data into the ledgers table for account2
    updateOrInsertAccount($account2, $credit, $type2, "credit", $conn);

    echo "<script>alert('Journal Entry recorded well');</script>";
    echo "<script>window.location.href='journal-entry.php';</script>";

    // Rest of your code...
}
?>

<?php 
}
else{
    echo "<script>window.location.href='index.php';</script>";
}
?>