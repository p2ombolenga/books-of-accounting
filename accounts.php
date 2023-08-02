<?php
session_start();
if(isset($_SESSION['id'])){
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<div>
<?php include('navigation.php'); ?>
    <form action="" method="post">
    <div class="mb-5 py-5 w-full md:w-2/3 lg:w-1/2 mx-auto p-8 border border-gray-200 shadow-md rounded-lg">
        <table class="w-full text-center">
            <tr class="">
                <th>Account Name</th>
                <th>Account Type</th>
            </tr>
            <tr class="">
                <td><input type="text" name="name" id="name" placeholder="Enter Account Name" class="w-full border border-gray-300 py-2 px-1"></td>
                <td>
                    <select name="type" id="type" class="w-full border border-gray-300 py-2 px-1">
                    <option value="Asset">Asset</option>
                    <option value="Equity">Equity</option>
                    <option value="Liability">Liability</option>
                    <option value="Income">Income</option>
                    <option value="Expense">Expense</option>
                    </select>
                </td>
                <tr>
                    <td colspan="2" class="text-center pt-3"><button type="submit" name="save" class="bg-blue-500 text-white px-5 py-2">Add Account</button></td>
                </tr>
            </tr>
        </table>
    </div>
    </form> 

    <?php
    include('connection.php');
    if(isset($_POST['save'])){
        $name = $_POST['name'];
        $type = $_POST['type'];

        if(!empty($name) && !empty($type)){
            $query = "INSERT INTO accounts(name, type) VALUES('$name', '$type')";
            $results = $conn->query($query);
            if($results){
                echo "<script>alert('Account Recorded successfully');</script>";
                echo "<script>window.location.href = 'accounts.php';</script>";
            }
            else{
                echo "<script>alert('Failed to record new account');</script>";
                echo "<script>window.location.href = 'accounts.php';</script>";
            }
        }
        else{
            echo "<script>alert('Fill in Both Account name and type');</script>";
            echo "<script>window.location.href = 'accounts.php';</script>";
        }

    }    
    ?>

    <div class="w-1/2 mx-auto pb-5 text-2xl text-center">Charts of Accounts</div>
        <div class="mb-20 py-5 w-full md:w-2/3 lg:w-1/2 mx-auto p-8 border border-gray-200 shadow-md rounded-lg">

            <table class="w-full text-center border border-black">
                <tr>
                    <th class="border">N<sup>o</sup></th>
                    <th class="border">Account Name</th>
                    <th class="border">Account Type</th>
                </tr>
                <?php
            
                    $retrieve = "SELECT * FROM accounts";
                    $display = $conn->query($retrieve);
                    $numbering = 1;
                    while($row = mysqli_fetch_array($display)){
                        ?>
                        <tr class="border">
                            <td class="border py-2"><?php echo $numbering; ?></td>
                            <td class="border py-2"><?php echo $row['name'] ?></td>
                            <td class="border py-2"><?php echo $row['type'] ?></td>
                        </tr>
                        <?php
                        $numbering++;
                    }
                    
                    ?>

            </table>
        </div>
    </div>
</body>
</html>

<?php 
}
else{
    echo "<script>window.location.href='index.php';</script>";
}
?>