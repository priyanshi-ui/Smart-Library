<?php
include('database.php');

$reportType = $_GET['report_type'] ?? 'issue';

$tableMap = [
    'issue' => 'issue_book',
    'return' => 'return_book',
    'reserve' => 'book_reserve',
    'fine' => 'fines',
    'most_borrowed' => 'issue_book' // Added for most borrowed
];

$dateColumnMap = [
    'issue' => 'date_of_issue',
    'return' => 'date_of_return',
    'reserve' => 'reserve_date',
    'fine' => 'date_of_fine'
];

if (isset($_GET['fetch_years'])) {
    $table = $tableMap[$reportType] ?? 'issue_book';
    $dateColumn = $dateColumnMap[$reportType] ?? 'date_of_issue';

    $query = "SELECT MIN(YEAR($dateColumn)) AS min_year, MAX(YEAR($dateColumn)) AS max_year FROM $table";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();

    $years = [];
    if ($row['min_year'] && $row['max_year']) {
        for ($i = $row['max_year']; $i >= $row['min_year']; $i--) {
            $years[] = $i;
        }
    }
    echo json_encode($years);
    exit();
}

if (isset($_GET['fetch_data'])) {
    $filter = $_GET['filter'] ?? 'day';
    $from_date = $_GET['from_date'] ?? null;
    $to_date = $_GET['to_date'] ?? null;
    $year = $_GET['year'] ?? date('Y');

    $table = $tableMap[$reportType] ?? 'issue_book';
    $dateColumn = $dateColumnMap[$reportType] ?? 'date_of_issue';

    switch ($filter) {
        case 'day':
            if ($from_date && $to_date) {
                $query = "SELECT DATE($dateColumn) AS period, COUNT(*) AS total FROM $table WHERE $dateColumn BETWEEN '$from_date' AND '$to_date' GROUP BY DATE($dateColumn)";
            }
            break;
        case 'month':
            $query = "SELECT DATE_FORMAT($dateColumn, '%Y-%m') AS period, COUNT(*) AS total FROM $table WHERE YEAR($dateColumn) = '$year' GROUP BY DATE_FORMAT($dateColumn, '%Y-%m')";
            break;
        default:
            $query = "SELECT YEAR($dateColumn) AS period, COUNT(*) AS total FROM $table GROUP BY YEAR($dateColumn)";
    }

    $result = $conn->query($query);
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
    exit();
}

if (isset($_GET['fetch_fine_data'])) {
    $filter = $_GET['filter'] ?? 'day';
    $from_date = $_GET['from_date'] ?? null;
    $to_date = $_GET['to_date'] ?? null;
    $year = $_GET['year'] ?? date('Y');

    $query = "SELECT 
        DATE_FORMAT(date_of_fine, '%Y-%m-%d') AS period_day,
        DATE_FORMAT(date_of_fine, '%Y-%m') AS period_month,
        YEAR(date_of_fine) AS period_year,
        SUM(CASE WHEN status = 'Paid' THEN amount ELSE 0 END) AS paid_fine, 
        SUM(CASE WHEN status = 'Unpaid' THEN amount ELSE 0 END) AS unpaid_fine
    FROM fines ";

    $where = [];
    if ($filter === 'day' && $from_date && $to_date) {
        $where[] = "date_of_fine BETWEEN '$from_date' AND '$to_date'";
    } elseif ($filter === 'month' && $year) {
        $where[] = "YEAR(date_of_fine) = '$year'";
    }

    if (!empty($where)) {
        $query .= " WHERE " . implode(" AND ", $where);
    }

    switch ($filter) {
        case 'day':
            $query .= " GROUP BY period_day";
            break;
        case 'month':
            $query .= " GROUP BY period_month";
            break;
        case 'year':
            $query .= " GROUP BY period_year";
            break;
    }

    $query .= " ORDER BY date_of_fine ASC";

    $result = $conn->query($query);
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
    exit();
}

if (isset($_GET['fetch_most_borrowed'])) {
    $query = "SELECT book.book_id, book.book_title, COUNT(issue_book.book_id) AS borrow_count 
              FROM issue_book 
              JOIN book ON issue_book.book_id = book.book_id
              /*WHERE issue_book.status = 'returned'  -- Only count returned books
              */GROUP BY book.book_id
              ORDER BY borrow_count DESC
              LIMIT 10";   // Fetch the most borrowed books

    $result = $conn->query($query);
   
    $books = [];
    $counts = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $books[] = $row["book_title"];
            $counts[] = $row["borrow_count"];
        }
    } else {
        echo json_encode(['books' => [], 'counts' => []]);
        exit();
    }

    // Prepare the data to return
    $data = [
        'books' => $books,
        'counts' => $counts
    ];

    echo json_encode($data);
    exit();
}

if (isset($_GET['fetch_gender_distribution'])) {
    $query = "SELECT gender, COUNT(*) as total FROM member GROUP BY gender";
    $result = $conn->query($query);

    $genders = [];
    $counts = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $genders[] = $row['gender'] ?? 'Unknown';
            $counts[] = $row['total'];
        }
    }

    $data = [
        'genders' => $genders,
        'counts' => $counts
    ];

    echo json_encode($data);
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books Report</title>
    <link rel="stylesheet" href="dashboard_style.css">
    <link rel="stylesheet" href="manage.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .container { width: 80%; margin: auto; padding: 20px; height: 100vh; text-align: center; }
        .card { background: #fff; border-radius: 12px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 20px; margin-top: 20px; }
        h2 { color: #4A47A3; }
        label { font-size: 16px; font-weight: 500; }
        select, input { padding: 8px 15px; margin-left: 10px; border: 1px solid #ddd; border-radius: 6px; background: #fff; font-size: 14px; cursor: pointer; transition: 0.3s; }
        select:hover, input:hover { background: #e0e0e0; }
        .filters { display: flex; justify-content: center; gap: 15px; margin-top: 15px; }
        .date-filters, .year-filter { display: none; margin-top: 15px; }
        .btn { padding: 8px 15px; background: #4A47A3; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; transition: 0.3s; }
        .btn:hover { background: #3330a3; }
        canvas { width: 100% !important; height: 500px !important; }
    </style>
</head>
<body>
    <header class="head">
        <div class="logosec">
            <div class="logo">
                <img src="images/logo.png" class="logo" style="margin-top:30px;padding:2px; width: 150px; height: auto;">
            </div>
            <img src="images/Menu.png" class="icn menuicn" id="menuicn" alt="menu-icon">
        </div>
        
        <div class="dp">
                    <a href="profilepage.php">
                        <img src="images/profile.jpg" class="dpicn" alt="dp">
                    </a>
                </div>
    </header>

    <div class="main-container">
        <div class="navcontainer">
            <nav class="nav">
                <div class="nav-upper-options">
                    <a href="librarian_dashboard.php" style="text-decoration:none; color:black;">
                        <div class="nav-option option1">
                            <img src="images/dashboard.png" class="nav-img" alt="Dashboard">
                            <h3>Dashboard</h3>
                        </div>
                    </a>
                    <a href="book.php" style="text-decoration:none; color:black;">
                        <div id="book" class="option2 nav-option">
                            <img src="images/book.png" class="nav-img" alt="Books">
                            <h3>Books</h3>
                        </div>
                    </a>
                    <a href="member.php" style="text-decoration:none; color:black;">
                        <div class="nav-option option3 dropdown">
                            <img src="images/member.png" class="nav-img" alt="Members">
                            <h3>Members</h3>
                        </div>
                    </a>
                    <a href="category.php" style="text-decoration:none; color:black;">
                        <div class="nav-option option4">
                            <img src="images/catalogue.png" class="nav-img" alt="Catalogue">
                            <h3>Catalogue</h3>
                        </div>
                    </a>
                    <div class="nav-option option6 dropdown">
                        <img src="images/member.png" class="nav-img" alt="report">
                        <h3> Issue/Return</h3>
                        <div class="dropdown-content">
                            <a href="view_issue_book.php">Issue</a>
                            <a href="view_return_book.php"> Return</a>
                        </div>
                    </div>
                    <a href="reserve_book.php" style="text-decoration:none; color:black;">
                        <div class="nav-option option4">
                            <img src="images/catalogue.png" class="nav-img" alt="Catalogue">
                            <h3>Reserve</h3>
                        </div>
                    </a>
                    <a href="view_fine.php" style="text-decoration:none; color:black;">
                        <div class="nav-option option5">
                            <img src="images/fines.png" class="nav-img" alt="blog">
                            <h3> Fine</h3>
                        </div>
                    </a>
                    <a href="report.php" style="text-decoration:none; color:black;">
                        <div class="nav-option option5">
                            <img src="images/analytics.png" class="nav-img" alt="blog">
                            <h3> Report Generate</h3>
                        </div>
                    </a>
                    <a href="view_complaints.php" style="text-decoration:none; color:black;">
                        <div class="nav-option option6">
                            <img src="images/Complains.png" class="nav-img" alt="Complain">
                            <h3>Complain</h3>
                        </div>
                    </a>
                    <a href="index.php" style="text-decoration:none; color:black;">
                        <div class="nav-option logout">
                            <img src="images/log-out.png" class="nav-img" alt="logout">
                            <h3>Logout</h3>
                        </div>
                    </a>
                </div>
            </nav>
        </div>

        <div class="container">
            <h2>📚 Books Report</h2>
            <div class="filters">
                <label for="reportType">Select Report Type:</label>
                <select id="reportType">
                    <option value="" disabled selected>Report Type</option>
                    <option value="issue">Issued Books</option>
                    <option value="return">Returned Books</option>
                    <option value="reserve">Reserved Books</option>
                    <option value="fine">Fine Collected</option>
                    <option value="most_borrowed">Most Borrowed Book</option>
                    <option value="gender">Member Gender</option>

                </select>

                <label for="filter">Filter by:</label>
                <select id="filter">
                    <option value="" disabled selected>Filter By</option>
                    <option value="day">Day</option>
                    <option value="month">Month</option>
                    <option value="year">Year</option>
                </select>
            </div>

            <div class="date-filters" id="dateFilters">
                <label for="fromDate">From:</label>
                <input type="date" id="fromDate">
                <label for="toDate">To:</label>
                <input type="date" id="toDate">
                <button class="btn" id="applyFilter">Apply</button>
            </div>

            <div class="year-filter" id="yearFilter">
                <label for="yearSelect">Select Year:</label>
                <select id="yearSelect"></select>
                <button class="btn" id="applyYearFilter">Apply</button>
            </div>

            <div class="card">
                <h3 style="color: #444;">📊 Graphical Report</h3>   
                <canvas id="booksIssuedChart"></canvas>
            </div>
        </div>

        <script>
            const ctx = document.getElementById('booksIssuedChart').getContext('2d');
            let booksChart;

            function fetchYears(reportType) {
                fetch(`report.php?fetch_years=true&report_type=${reportType}`)
                    .then(response => response.json())
                    .then(years => {
                        const yearSelect = document.getElementById('yearSelect');
                        yearSelect.innerHTML = '<option value="" disabled selected>Select Year</option>';
                        years.forEach(year => yearSelect.innerHTML += `<option value="${year}">${year}</option>`);
                    });
            }

            function fetchMostBorrowed() {
                fetch(`report.php?fetch_most_borrowed=true`)
                    .then(response => response.json())
                    .then(data => {
                        const labels = data.books; // Get book titles
                        const values = data.counts; // Get borrow counts

                        if (booksChart) booksChart.destroy();
                        booksChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Most Borrowed Books',
                                    data: values,
                                    backgroundColor: 'rgba(147, 106, 228, 0.6)',
                                    borderColor: 'rgba(153, 102, 255, 1)',
                                    borderWidth: 2
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: { y: { beginAtZero: true } }
                            }
                        });
                    });
            }

            function fetchData(reportType, filter, fromDate = '', toDate = '', year = '') {
                let url, params;

                if (reportType === 'fine') {
                    url = `report.php?fetch_fine_data=true&filter=${filter}`;
                    if (filter === 'day' && fromDate && toDate) {
                        url += `&from_date=${fromDate}&to_date=${toDate}`;
                    } else if (filter === 'month' && year) {
                        url += `&year=${year}`;
                    }
                } else {
                    url = `report.php?fetch_data=true&report_type=${reportType}&filter=${filter}`;
                    if (filter === 'day' && fromDate && toDate) url += `&from_date=${fromDate}&to_date=${toDate}`;
                    if (filter === 'month' && year) url += `&year=${year}`;
                }

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        let labels, paidData, unpaidData;

                        if (reportType === 'fine') {
                            labels = data.map(item => {
                                if (filter === 'day') return item.period_day;
                                if (filter === 'month') return item.period_month;
                                return item.period_year;
                            });
                            paidData = data.map(item => parseFloat(item.paid_fine));
                            unpaidData = data.map(item => parseFloat(item.unpaid_fine));

                            if (booksChart) booksChart.destroy();
                            booksChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: labels,
                                    datasets: [
                                        {
                                            label: 'Paid Fine',
                                            data: paidData,
                                            backgroundColor: 'rgba(75, 192, 192, 0.6)',
                                            borderColor: 'rgba(75, 192, 192, 1)',
                                            borderWidth: 2
                                        },
                                        {
                                            label: 'Unpaid Fine',
                                            data: unpaidData,
                                            backgroundColor: 'rgba(255, 99, 132, 0.6)',
                                            borderColor: 'rgba(255, 99, 132, 1)',
                                            borderWidth: 2
                                        }
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: { callback: value => '₹' + value }
                                        }
                                    }
                                }
                            });
                        } else {
                            // Process other report types (Issue, Return, Reserve)
                            labels = data.map(item => item.period);
                            const values = data.map(item => item.total);

                            if (booksChart) booksChart.destroy();

                            booksChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: reportType === 'issue' ? 'Total Books Issued' 
                                            : reportType === 'return' ? 'Total Books Returned' 
                                            : 'Total Books Reserved',
                                        data: values,
                                        backgroundColor: reportType === 'issue' ? 'rgba(75, 192, 192, 0.6)' 
                                            : reportType === 'return' ? 'rgba(255, 99, 132, 0.6)' 
                                            : 'rgba(255, 206, 86, 0.6)',
                                        borderColor: reportType === 'issue' ? 'rgba(75, 192, 192, 1)' 
                                            : reportType === 'return' ? 'rgba(255, 99, 132, 1)' 
                                            : 'rgba(255, 206, 86, 1)',
                                        borderWidth: 2
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                                }
                            });
                        }
                    });
            }

            document.addEventListener('DOMContentLoaded', () => {
                const reportType = document.getElementById('reportType');
                const filter = document.getElementById('filter');
                const dateFilters = document.getElementById('dateFilters');
                const yearFilter = document.getElementById('yearFilter');
                const fromDate = document.getElementById('fromDate');
                const toDate = document.getElementById('toDate');
                const yearSelect = document.getElementById('yearSelect');
                const applyFilter = document.getElementById('applyFilter');
                const applyYearFilter = document.getElementById('applyYearFilter');

                // Disable filter selection initially
                filter.disabled = true;

                reportType.addEventListener('change', function () {
                    if (this.value) {
                        filter.disabled = false; // Enable filter when report type is selected
                        fetchYears(this.value);
                        
                        if (this.value === 'most_borrowed') {
                            fetchMostBorrowed();
                        } else if (this.value !== 'fine') {
                            fetchData(this.value, 'day');
                        }
                    } else {
                        filter.disabled = true;
                    }

                    // Reset filters on change
                    filter.selectedIndex = 0;
                    dateFilters.style.display = 'none';
                    yearFilter.style.display = 'none';
                });

                filter.addEventListener('change', function () {
                    dateFilters.style.display = this.value === 'day' ? 'block' : 'none';
                    yearFilter.style.display = this.value === 'month' ? 'block' : 'none';

                    if (this.value === 'year') {
                        fetchData(reportType.value, 'year');
                    }
                });

                applyFilter.addEventListener('click', () => {
                    if (reportType.value && filter.value === 'day') {
                        fetchData(reportType.value, 'day', fromDate.value, toDate.value);
                    }
                });

                applyYearFilter.addEventListener('click', () => {
                    if (reportType.value && filter.value === 'month') {
                        fetchData(reportType.value, 'month', '', '', yearSelect.value);
                    }
                });

     // Initially disable the toDate input
     toDate.disabled = true;

fromDate.addEventListener('change', () => {
    // Enable toDate input when fromDate is selected
    toDate.disabled = false;

    // Set the minimum value of toDate to the selected fromDate
    toDate.min = fromDate.value;

    // If toDate is already set and is before fromDate, reset it
    if (toDate.value && new Date(toDate.value) < new Date(fromDate.value)) {
        toDate.value = ''; // Reset to date if it's before from date
    }
});

toDate.addEventListener('change', () => {
    // If the user tries to select a date before fromDate, reset it
    if (new Date(toDate.value) < new Date(fromDate.value)) {
        toDate.value = ''; // Reset to date
    }
});
                // Default state
                fetchYears('issue');
                fetchData('issue', 'day');
            });


            function fetchGenderDistribution() {
    fetch('report.php?fetch_gender_distribution=true')
        .then(response => response.json())
        .then(data => {
            const labels = data.genders;
            const values = data.counts;

            if (booksChart) booksChart.destroy();
            booksChart = new Chart(ctx, {
                type: 'pie',  // Pie chart is perfect for gender distribution
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Gender Distribution',
                        data: values,
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.6)', // Blue (Male)
                            'rgba(255, 99, 132, 0.6)', // Pink (Female)
                            'rgba(255, 206, 86, 0.6)', // Yellow (Others)
                            'rgba(153, 102, 255, 0.6)' // Purple (Unknown)
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(153, 102, 255, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        });
}

        </script>
    </body>
</html>