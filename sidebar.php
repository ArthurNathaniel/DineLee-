<style>
    .logo{
        margin-top: 25px;
    }
</style>
<div class="sidebar">
    <br>
    <div class="logo">
      
    </div>
    <div class="links">
    <h4>PRODUCTS</h4>
  
    <a href="dashboard.php"> Dashboard </a>
    <div class="dashed"></div>
    <a href="add_product.php"> Add Products </a>
    <div class="dashed"></div>
    <a href="view_products.php"> View Products </a>
    <div class="dashed"></div>
    <a href="update_price.php"> Update Product Price </a>
    <div class="dashed"></div>
    <a href="refill_product.php"> Refill Product </a>
    <div class="dashed"></div>
    <a href="remove_product.php"> Remove Product </a>
    <h4>CASHIERS</h4>
    <a href="register_cashier.php">Register Cashiers</a>
    <div class="dashed"></div>
    <a href="view_cashiers.php">View Cashiers</a>
    <h4>CUSTOMERS</h4>
    <a href="register_customer.php">Register Customers</a>
    <div class="dashed"></div>
    <a href="view_customers.php">View  Customers</a>
    <h4>INCOME</h4>
        <a href="daily_admin.php">Daily</a>
        <div class="dashed"></div>
        <a href="monthly_admin.php"> Monthly / Yearly </a>

        <h4>RANKING</h4>
        <a href="daily_product_rank.php">Daily Product Ranking</a>
        <div class="dashed"></div>
        <a href="monthly_product_ranking.php"> Monthly Product Ranking </a>

        <h4>EXPENSES</h4>
        <a href="record_expense_type.php">Add Expenses Type</a>
        <div class="dashed"></div>
        <a href="record_expense_admin.php">Record Expenses</a>
        <div class="dashed"></div>
        <a href="expenses_history_admin.php">Expenses History</a>
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