<?php
session_start();
include 'db.php'; // Database connection

// Check if cashier is logged in
if (!isset($_SESSION['cashier_id'])) {
    header("Location: login.php");
    exit;
}

$cashier_name = $_SESSION['cashier_name'];

// Get current date and time
date_default_timezone_set('GMT');  // Changed to GMT
$current_hour = date('H');
$current_date = date('l, F j, Y');
$current_time = date('h:i A');

// Determine greeting based on time
if ($current_hour < 12) {
    $greeting = "Mema wo akye! (Good morning)";
} elseif ($current_hour < 18) {
    $greeting = "Mema wo aha! (Good afternoon)";
} else {
    $greeting = "Mema wo adwo! (Good evening)";
}

// Ghanaian Public Holidays & Special Days
$ghana_holidays = [
    // 🏛 National & Public Holidays
    "01-01" => "Happy New Year! Afihyia Pa!",
    "01-07" => "Happy Constitution Day!",
    "03-06" => "Happy Independence Day! Yɛn ara yɛ asase ni!",
    "05-01" => "Happy Workers' Day! Meda mo ase for your hard work!",
    "05-25" => "Happy Africa Day! Long live African unity!",
    "07-01" => "Happy Republic Day!",
    "08-04" => "Happy Founders' Day! Remembering our great leaders!",
    "09-21" => "Happy Kwame Nkrumah Memorial Day! Yɛ ma wo nkae!",
    "12-25" => "Merry Christmas! Afehyia Pa!",
    "12-26" => "Happy Boxing Day!",
    "12-31" => "New Year's Eve! May next year bring you more blessings!",

    // 🔆 **Akan Akwasidae Festival (Varies, but falls on Sunday every 6 weeks)**
    "01-14" => "Happy Akwasidae! Nyame nhyira wo!",
    "02-25" => "Happy Akwasidae! May your ancestors guide you!",
    "04-07" => "Happy Akwasidae! Medɔ mo nyansa ne nkɔso!",
    "05-19" => "Happy Akwasidae! Afehyia pa!",
    "06-30" => "Happy Akwasidae! May your blessings be multiplied!",
    "08-11" => "Happy Akwasidae! Enjoy this sacred day!",
    "09-22" => "Happy Akwasidae! Nyame nsa wo ho!",
    "11-03" => "Happy Akwasidae! Wishing you good health & prosperity!",
    "12-15" => "Happy Akwasidae! Celebrate with love and wisdom!",

    // ☪ **Islamic Holidays (Based on the Islamic Calendar)**
    "04-10" => "Ramadan Kareem! May this holy month bring you peace!",
    "04-21" => "Eid-ul-Fitr Mubarak! Barika da Sallah!",
    "06-28" => "Eid-ul-Adha Mubarak! May Allah accept your prayers!",
    "07-19" => "Islamic New Year! Happy Hijri New Year!",
    "09-27" => "Mawlid al-Nabi! Happy Prophet Muhammad’s Birthday!",

    // 🍽 **Food Days in Ghana**
    "02-09" => "Happy National Chocolate Day! Enjoy Ghana’s finest cocoa!",
    "04-16" => "World Beans Day – Eat healthy, stay strong!",
    "05-06" => "Fufu Day! Wɔnya ade pa wɔ Ghana!",
    "06-10" => "Kenkey Festival! Enjoy your hot kenkey with pepper & fish!",
    "07-07" => "World Waakye Day! Tuo waakye na nyɛ den!",
    "08-15" => "Jollof Rice Day! Ghana Jollof is the best!",
    "10-01" => "World Cocoa Day – Proudly made in Ghana!",
    "10-16" => "World Food Day – Eat well, stay strong!",

    // 🎉 **Other Important Days**
    "02-14" => "Happy Valentine's Day! Medɔ wo!",
    "06-16" => "International Day of the African Child!",
    "10-15" => "Global Handwashing Day – Keep Ghana clean!",
    "11-19" => "International Men's Day – Wishing all men strength and wisdom!",
    "12-03" => "International Day of Persons with Disabilities – Respect & Inclusion for all!"
];

$today = date('m-d');
$holiday_message = isset($ghana_holidays[$today]) ? $ghana_holidays[$today] : "";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Dashboard</title>
    <?php include '../cdn.php'; ?>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body>
<?php include 'sidebar.php'?>
<div class="auth_alls">
<div class="auth_box">
        <h2>Akwaaba, <?= $cashier_name ?>!</h2>
        <p><?= $greeting ?></p>
        <p><strong>Today's Date:</strong> <?= $current_date ?></p>
        <p><strong>Current Time (GMT):</strong> <?= $current_time ?></p>
        <?php if ($holiday_message): ?>
            <p style="color: green; font-weight: bold;"><?= $holiday_message ?></p>
        <?php endif; ?>
    </div>
    </div>
</body>
</html>
