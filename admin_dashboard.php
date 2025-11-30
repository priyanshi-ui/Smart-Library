<!DOCTYPE html>
<html lang="en">

<head>
	<title>Admin Dashboard </title>
	<link rel="stylesheet" href="dashboard_style.css">
	<link rel="stylesheet" href="manage.css">
</head>
<body>
	
	<header class="head">
		<div class="logosec">
		<div class="logo" >
<img src="logo.png" class="logo" style="margin-top:30px;padding:2px; width: 150px; height: auto;" ></div>
			<img src="Menu.png" class="icn menuicn" id="menuicn" alt="menu-icon">
		</div>
	
		<!--<div class="searchbar">
			<input type="text" placeholder="Search">
			<div class="searchbtn">
			<img src="images/search.png" class="icn srchicn" alt="search-icon">
			</div>
		</div-->
		
		<div class="message">
			<div class="circle"></div>
			<img src="notification.png" class="icn" alt="">
			<div class="dp">
			<a href="profilepage.php">
			<img src="profile.jpg" class="dpicn" alt="dp"></a>
			</div>
		</div>

	</header>

	<div class="main-container">
		<div class="navcontainer">
			<nav class="nav">
				<div class="nav-upper-options">
					<div class="nav-option option1">
						<img src="dashboard.png" class="nav-img" alt="dashboard">
						<h3> Dashboard</h3>
					</div>

					<div id="book" class="option2 nav-option">
						<img src="black book.png" class="nav-img" alt="articles">
							<h3>Books</h3>

						
					</div>
					<div class="nav-option2 nav-option">
						<img src="black book.png" class="nav-img" alt=articles">
						<h3> Reserve Book</h3>
					</div>
				
					<div class="nav-option option3">
						<img src="black user.png" class="nav-img" alt="report">
						<h3>  User Account</h3>
					</div>

					<div class="nav-option option4">
						<img src="catalogue.png" class="nav-img" alt="institution">
						<h3> Generate Reports</h3>
					</div>

					<div class="nav-option option4">
						<img src="notification.png" class="nav-img" alt="institution">
						<h3>User Notification</h3>
					</div>


					<div class="nav-option option6">
						<img src="settings.png" class="nav-img" alt="settings">
						<h3>Settings</h3>
					</div>

					<a href="homepage.php" style="text-decoration:none; color:black;">
					<div class="nav-option logout">
						<img src="log-out.png" class="nav-img" alt="logout">
						<h3>Logout</h3>
					</div>
					</a>
				</div>
			</nav></div>
		
		
			
			<div class="box-container">

				<div class="box box1">
					<div class="text">
						<h2 class="topic-heading"><?php echo $book->getTotalBooks(); ?></h2>
						<h2 class="topic">Total Books</h2>
					</div>

					<img src="Books.png" alt="Books">
				</div>

	<div class="main">

			<div class="searchbar2">
				<input type="text" name="" id="" placeholder="Search">
			
			</div>

			<div class="box-container">

				<div class="box box1">
					<div class="text">
						<h2 class="topic-heading">500</h2>
						<h2 class="topic">Total Books</h2>
					</div>

					<img src="Books.png" alt="Books">
				</div>

				<div class="box box2">
					<div class="text">
						<h2 class="topic-heading">100</h2>
						<h2 class="topic">Members</h2>
					</div>

					<img src="Members.png" alt="Members">
				</div>

				<div class="box box3">
					<div class="text">
						<h2 class="topic-heading">20</h2>
						<h2 class="topic">Complains</h2>
					</div>

					<img src="Complain.png" alt="Complain">
				</div>

				<div class="box box4">
					<div class="text">
						<h2 class="topic-heading">$50</h2>
						<h2 class="topic">Fine</h2>
					</div>

					<img src="	Fine.png" alt="Fine">
				</div>
			</div>

			<div class="report-container">
				<div class="report-header">
					<h1 class="recent-Articles">Recent Book</h1>
					<button class="view">View All</button>
				</div>

				<div class="report-body">
					<div class="report-topic-heading">
						<h3 class="t-op">Members</h3>
						<h3 class="t-op">Books</h3>
						<h3 class="t-op">Complain</h3>
						<h3 class="t-op">Status</h3>
					</div>
					</div>
				</div>
			</div>
		</div>
	

	<script>
			let menuicn = document.querySelector(".menuicn");
let nav = document.querySelector(".navcontainer");

menuicn.addEventListener("click", () => {
	nav.classList.toggle("navclose");
})
	
</script>
</body>
</html>