<div class="sidebar">
    <div class="logo">

    </div>
    <div class="links">
        <h4>FOOD</h4>

        <a href="dashboard.php"> Dashboard </a>
        <div class="dashed"></div>
        <a href="add_category.php">Add Category</a>
        <div class="dashed"></div>
        <a href="view_categories.php">View Categories</a>
        <div class="dashed"></div>
        <a href="add_menu.php">Add Menu</a>
        <div class="dashed"></div>
        <a href="view_menu.php">View Menu</a>
        <h4>CASHIER</h4>
        <a href="register_cashier.php">Register Cashier</a>
        <div class="dashed"></div>
        <a href="view_cashiers.php">View Cashiers</a>
        <h4>ORDERS</h4>
        <a href="view_orders.php">View Orders</a>
        <div class="dashed"></div>
        <a href="cancel_order.php">Cancel Orders</a>
        <h4>SALES</h4>
        <a href="todays_sales.php">Daily Sales</a>
        <div class="dashed"></div>
        <a href="weekly_sales.php">Weekly Sales</a>
        <div class="dashed"></div>
        <a href="monthly_sales.php">Monthly Sales</a>
        <div class="dashed"></div>
        <a href="yearly_sales.php">Yearly Sales</a>
        <h4>EXPENSES</h4>
        <a href="record_expenses.php">Record Expenses</a>
        <div class="dashed"></div>
        <a href="view_expenses.php">Expenses History</a>
        <h4>PROFIT / LOSS</h4>
        <a href="daily_earns.php">Daily Profit</a>
        <div class="dashed"></div>
        <a href="weekly_earns.php">Weekly Profit</a>
        <div class="dashed"></div>
        <a href="monthly_earns.php"> Monthly Profit</a>
        <div class="dashed"></div>
        <a href="yearly_earns.php">Yearly Profit</a>
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