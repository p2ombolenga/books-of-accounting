<?php
session_start();
if(isset($_SESSION['id'])){
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>General Ledger</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

<?php include('navigation.php'); ?>

<div class="container mx-auto">
    <div class="text-3xl text-center my-6">Group Members</div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <!-- Each child div will occupy the specified number of columns in the grid -->
      <div class="shadow-md border border-slate-200 rounded-md px-4 py-8 space-y-5">
        <span class="block font-semibold text-xl">NSABIMANA Peter</span>
        <span class="block text-lg">221022891</span>
      </div>
      <div class="shadow-md border border-slate-200 rounded-md px-4 py-8 space-y-5">
        <span class="block font-semibold text-xl">NIYONSENGA Dieudonne</span>
        <span class="block text-lg">221002659</span>
      </div>
      <div class="shadow-md border border-slate-200 rounded-md px-4 py-8 space-y-5">
        <span class="block font-semibold text-xl">TUYISENGE Felicite</span>
        <span class="block text-lg">221015694</span>
      </div>
      <div class="shadow-md border border-slate-200 rounded-md px-4 py-8 space-y-5">
        <span class="block font-semibold text-xl">TUYISHIMIRE Annet</span>
        <span class="block text-lg">220017475</span>
      </div>
      <div class="shadow-md border border-slate-200 rounded-md px-4 py-8 space-y-5">
        <span class="block font-semibold text-xl">UWIZEYIMANA Solange</span>
        <span class="block text-lg">221008567</span>
      </div>
      <!-- Add more items as needed -->
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
