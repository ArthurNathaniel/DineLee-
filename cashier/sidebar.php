<div class="sidebar">
    <div class="logo">

    </div>
    <div class="links">
    <h4><i class="fas fa-user-tie"></i> CASHIER</h4>
<a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
<div class="dashed"></div>
<a href="chart.php"><i class="fas fa-chart-pie"></i> Chart</a>

<h4><i class="fas fa-utensils"></i> ORDERS</h4>
<a href="order_food.php"><i class="fas fa-concierge-bell"></i> Order Food</a>
<div class="dashed"></div>
<a href="view_orders.php"><i class="fas fa-receipt"></i> View Orders</a>

<h4><i class="fas fa-dollar-sign"></i> SALES</h4>
<a href="daily_sales.php"><i class="fas fa-calendar-day"></i> Daily Sales</a>

    </div>

    <a href="logout.php">
        <div class="logout">
            <i class="fas fa-power-off"></i> Logout
        </div>
    </a>
</div>

<div class="toggle_btn">
    <p><i class="fas fa-bars"></i></p>
</div>

<script>
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.querySelector('.toggle_btn');
    const toggleIcon = toggleBtn.querySelector('i');

    // Toggle sidebar visibility
    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('hidden');
        toggleBtn.classList.toggle('collapsed');

        if (sidebar.classList.contains('hidden')) {
            toggleIcon.classList.replace('fa-bars', 'fa-xmark');
        } else {
            toggleIcon.classList.replace('fa-xmark', 'fa-bars');
        }
    });

    // Highlight the active link based on the current page
    const currentPage = window.location.pathname.split("/").pop();
    const links = document.querySelectorAll(".links a");

    links.forEach(link => {
        if (link.getAttribute("href") === currentPage) {
            link.classList.add("active");
        }
    });
</script>