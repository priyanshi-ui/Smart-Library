<?php
include('database.php');
// Query to get the total number of books
$totalBooksQuery = "SELECT COUNT(*) AS total_books FROM book";
$totalBooksResult = $conn->query($totalBooksQuery);
$totalBooks = $totalBooksResult->fetch_assoc()['total_books'];

// Query to get the total number of members
$totalMembersQuery = "SELECT COUNT(*) AS total_members FROM member";
$totalMembersResult = $conn->query($totalMembersQuery);
$totalMembers = $totalMembersResult->fetch_assoc()['total_members'];

// Function to fetch chatbot replies based on the id
function getChatbotReplies($id) {
    global $conn;
    $query = "SELECT replies FROM chatbot WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $replies = [];

    while ($row = $result->fetch_assoc()) {
        $replies[] = $row['replies'];
    }

    $stmt->close();
    return $replies;
}

// Get replies for Information related to Books and Recommendations of various books
$infoReplies = getChatbotReplies(14);
$recommendReplies = getChatbotReplies(15);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <title>Online Library Management System | Easy Book Borrowing & Management – Smart Library</title>
    
    <head>
    <!-- Basic -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- Mobile Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Site Metas -->
    <meta name="keywords" content="Library Management System, Online Library, Book Issuing, Member Management, Digital Library, Library Software" />
    <meta name="description" content="Manage library books, issue and return transactions, track overdue fines, and maintain member records with our efficient Library Management System." />
    <meta name="author" content="Smart Library" />
    <meta name="robots" content="index, follow" />

    <!-- Open Graph Meta Tags (For Social Media Sharing) -->
    <meta property="og:title" content="Smart Library" />
    <meta property="og:description" content="A comprehensive Library Management System for book issuing, member management, fine calculations, and more." />
    <meta property="og:image" content="URL_to_library_image.jpg" />
    <meta property="og:url" content="https://www.smartlibrary.com/" />
    <meta property="og:type" content="website" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        body {
            padding-top: 56px;
            background-color: #DBD8E3;
        }

        .jumbotron {
            background: #5C5470;
            color: #DBD8E3;
            border-radius: 10px;
            padding: 40px;
            margin-top: 20px;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .jumbotron.fade-in {
            opacity: 1;
        }

        .navbar {
            background-color: #DBD8E3;
        }

        .navbar-brand img {
            margin-right: 10px;
        }

        .navbar-nav .nav-item .nav-link {
            font-size: 18px;
            margin-left:25px;
            color: #352F44;
        }

        .navbar-shrink {
            padding-top: 8px;
            padding-bottom: 8px;
            background-color: #2A2438 !important;
            transition: padding 0.3s, background-color 0.3s;
        }

        .navbar-shrink .navbar-brand, .navbar-shrink .navbar-nav .nav-link {
            color: #DBD8E3 !important;
        }

        .navbar-shrink .navbar-brand img {
            filter: brightness(0) invert(1);
        }

        .hidden-info {
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .hidden-info.show {
            opacity: 1;
        }

        .info-section {
            padding: 20px;
            background-color: #352F44;
            color: #DBD8E3;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        .carousel-item img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }

        footer {
            margin-top: 20px;
            background-color: #2A2438;
            color: #DBD8E3;
            padding: 20px 0;
            position: relative;
            bottom: 0;
            width: 100%;
        }

        footer a {
            color: #DBD8E3;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        .hover-box {
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s, opacity 0.6s ease-out;
           
            color: #DBD8E3;
            opacity: 0;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: auto;
        }

        .hover-box.show {
            transform: translateY(0px);
            opacity: 1;
        }

        .hover-box img {
            width: 90%;
            height: 70%;
            transition: transform 0.3s;
        }

        .hover-box:hover img {
            transform: scale(1.05);
        }

        .hover-description {
            color: white;
            padding: 10px;
            border-radius: 10px;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.7);
            text-align: center;
            display: none;
        }

        .hover-box:hover .hover-description {
            display: block;
        }

        .testimonials {
            padding: 40px 0;
            color: #DBD8E3;
        }

        .testimonials h2 {
            margin-bottom: 20px;
        }

        .testimonial-item {
            background: #352F44;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .info-section {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        margin-top: 30px;
    }

    .objective-section,
    .statistics-section {
        text-align: center;
        margin-bottom: 20px;
    }

    .section-divider {
        border: 1px solid #ddd;
        margin: 20px auto;
        width: 80%;
    }

    .objective-section h2,
    .statistics-section h2 {
        font-size: 1.8rem;
        color: #6c757d;
        margin-bottom: 10px;
        font-weight: bold;
    }

    .objective-section p,
    .statistics-section p {
        font-size: 1.1rem;
        color: #555;
    }
        .read-more {
            text-align: center;
            margin-top: 20px;
        }

        .display-4 {
            font-family: 'Papyrus', fantasy;
            font-weight: bold;
            font-size: 3rem;
            color: #FFFFFF;
        }

        .h5_about {
            font-family: 'Times New Roman', sans-serif;
            font-style: italic;
            font-size: 1.25rem;
            color: #F0F0F0;
            line-height: 1.6;
            margin-top: 1rem;
            margin-bottom: 2rem;
        }

        /* Chatbot Widget */
.chatbot-widget {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
}

/* Chatbot Button */
.chatbot-btn {
    background-color: #5C5470;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 25px;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.chatbot-btn:hover {
    background-color: #3C2A4D;
    transform: scale(1.05);
}

/* Chatbox */
.chatbox {
    display: none;
    width: 320px;
    position: fixed;
    bottom: 80px;
    right: 20px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    font-family: Arial, sans-serif;
}

/* Chatbox Header */
.chatbox-header {
    background-color: #5C5470;
    color: white;
    padding: 12px;
    text-align: center;
    font-size: 16px;
    font-weight: bold;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.close-chat-btn {
    background: none;
    border: none;
    color: white;
    font-size: 18px;
    cursor: pointer;
}

/* Chatbox Body */
#messages {
    max-height: 250px;
    overflow-y: auto;
    padding: 10px;
    background: #F9F9F9;
    display: flex;
    flex-direction: column;
}

/* User & Bot Messages */
.user-message {
    background: #5C5470;
    color: white;
    padding: 8px 12px;
    border-radius: 15px 15px 0 15px;
    align-self: flex-end;
    max-width: 75%;
    margin: 5px 0;
}

.bot-message {
    background: #E0E0E0;
    color: black;
    padding: 8px 12px;
    border-radius: 15px 15px 15px 0;
    align-self: flex-start;
    max-width: 75%;
    margin: 5px 0;
}

/* Chatbox Footer */
.chatbox-footer {
    display: flex;
    padding: 10px;
    background: #EEE;
    border-top: 1px solid #CCC;
}

.chatbox-footer input {
    flex: 1;
    padding: 8px;
    border: 1px solid #CCC;
    border-radius: 4px;
    font-size: 14px;
}

.chatbox-footer button {
    background: #5C5470;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 4px;
    margin-left: 8px;
    cursor: pointer;
    transition: 0.3s;
}

.chatbox-footer button:hover {
    background: #3C2A4D;
    transform: scale(1.05);
}

/* Book List */
.book-list {
    list-style: none;
    padding: 0;
    margin: 10px 0;
}

.book-list li {
    background: #F1F1F1;
    padding: 8px;
    margin: 5px 0;
    border-radius: 5px;
}

ul.book-list {
            list-style-type: disc;
            padding-left: 20px;
        }

/* Responsive Design */
@media (max-width: 480px) {
    .chatbox {
        width: 85%;
        right: 5%;
    }
}

    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <a class="navbar-brand" href="#">
        <img src="images/logo.png" alt="Library Logo" width="120" height="80" class="d-inline-block align-top">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="#">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#aboutus">About Us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#contactus">Contact</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="registration.php">Log-In/Sign-Up</a>
            </li>
        </ul>
    </div>
</nav>

<div id="carouselExampleCaptions" class="carousel slide mt-5" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
        <li data-target="#carouselExampleCaptions" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner" id="aboutus">
        <div class="carousel-item active">
            <img src="background/4.jpg" class="d-block w-100" alt="...">
            <div class="carousel-caption d-none d-md-block">
                <h5>Welcome to Our Library</h5>
                <p>Discover a world of knowledge and resources.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="background/homepage_photo.jpg" class="d-block w-100" alt="...">
            <div class="carousel-caption d-none d-md-block">
                <h5>Extensive Collection of Books</h5>
                <p>Explore our diverse collection across various genres.</p>
            </div>
        </div>
        <div class="carousel-item" >
            <img src="background/6.jpg" class="d-block w-100" alt="...">
            <div class="carousel-caption d-none d-md-block">
                <h5>Join Our Community</h5>
                <p>Become a member and enjoy exclusive benefits.</p>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5" >
    <div class="jumbotron text-center fade-in" >
        <h1 class="display-4 text-white animate__animated animate__slideInLeft">About Us</h1>
        <h5 class="h5_about text-white animate__animated animate__slideInRight">
            Smart Library makes library life easier! We use computers to keep track of all your books and who has borrowed them. This helps librarians find books quickly and reminds you when your books are due. We also help librarians keep the library organized and find lost books. With Smart Library, everyone saves time and can enjoy the library more!
        </h5>
    </div>
</div>

<!--div class="container my-5 text-center">
    <button id="toggleContentBtn" class="btn btn-secondary">Toggle Additional Content</button>
    <div id="additionalContent" class="mt-4" style="display: none;">
        <p>This is some additional content that can be toggled.</p>
    </div>
</div-->

<div class="container mt-5">
    <div class="text-center animate__animated animate__fadeInUp">
        <h1 style='color:#5C5470'>Our Services</h1><br>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="hover-box slide-up" id="box2">
                    <img src="images/reserve_book.png" alt="Book 2" class="img-fluid mb-3">
                    <div class="hover-description animate__animated animate__fadeInUp">
                        <h4>Online Catalog</h4>
                        <p>Access our extensive catalog of books and resources online.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="hover-box slide-up" id="box3">
                    <img src="images/Members.png" alt="Book 3" class="img-fluid mb-3">
                    <div class="hover-description animate__animated animate__fadeInUp">
                        <h4>Member Management</h4>
                        <p>Efficient management of member accounts and records.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="hover-box slide-up" id="box4">
                    <img src="images/Books.png" alt="Book 4" class="img-fluid mb-3">
                    <div class="hover-description animate__animated animate__fadeInUp">
                        <h4>Book Lending</h4>
                        <p>Easy and convenient borrowing of books and resources.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container testimonials">
    <div class="text-center">
        <h2 style='color:#5C5470'>What Our Members Say</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="testimonial-item">
                    <p>"Smart library system is user-friendly and efficient. I love how easy it is to find and borrow books!"</p>
                    <small>- Mirali</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-item">
                    <p>"The automated smart library system has made managing our library much easier. Highly recommended!"</p>
                    <small>- Harshita</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-item">
                    <p>"A fantastic resource for book lovers. The online catalog is especially helpful."</p><br>
                    <small>- Dhruv</small>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container info-section" id="infoSection">
    <!-- Objective Section -->
    <div class="objective-section">
        <h2>Our Objective</h2>
        <p>
            To provide comprehensive resources and services in support of the research, teaching, and learning needs of the community.
        </p>
    </div>

    <!-- Divider -->
    <hr class="section-divider">

    <!-- Library Statistics Section -->
    <div class="statistics-section">
        <h2>Library Statistics</h2>
        <p>Total Books: <?php echo $totalBooks; ?></p>
        <p>Total Members: <?php echo $totalMembers; ?></p>
    </div>

</div>

<footer class="text-center">
    <div id="contactus" class="container">
    <h2 >Contact Us</h2>
    <p>Email: smartlibrary12@gmail.com</p>
    <p>Phone: +123 456 7890</p>

        <p>&copy; 2024 Library Management System. All rights reserved.</p>
        <p>
            <a href="privacy.php">Privacy Policy</a> | 
            <a href="terms.php">Terms of Service</a> | 
            <a href="https://www.facebook.com/yourlibrary" target="_blank">Facebook</a> | 
            <a href="https://twitter.com/yourlibrary" target="_blank">Twitter</a> | 
            <a href="https://www.instagram.com/yourlibrary" target="_blank">Instagram</a>
        </p>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        // Fade-in effect for jumbotron
        $('.jumbotron').addClass('fade-in');

        // Scroll event for navbar and showing hidden info section
        $(window).scroll(function() {
            if ($(this).scrollTop() > 50) {
                $('.navbar').addClass('navbar-shrink');
                $('#infoSection').addClass('show');
            } else {
                $('.navbar').removeClass('navbar-shrink');
                $('#infoSection').removeClass('show');
            }

            // Add sliding effect to boxes with delay
            $('.hover-box').each(function(index) {
                var boxOffset = $(this).offset().top;
                var scrollPos = $(window).scrollTop() + $(window).height();

                if (scrollPos > boxOffset) {
                    // Apply delay based on the index
                    setTimeout(() => {
                        $(this).addClass('show');
                    }, index * 500); // 500ms delay for each box
                }
            });
        });

        // Toggle additional content
        $('#toggleContentBtn').click(function() {
            $('#additionalContent').fadeToggle();
        });
        $('#contactBtn').click(function(event) {
            event.preventDefault(); // Prevent the default link behavior
            $('#infoSection').fadeToggle(); // Toggle visibility of contact info section
        });
    });
</script>

<div class="chatbot-widget" id="chatbotWidget">
        <button id="chatbotBtn" class="chatbot-btn">
            <i class="fas fa-comment-alt"></i> How can I help you?
        </button>
    <div id="chatbox" class="chatbox">
            <div class="chatbox-header">
                <h5>Chat with Us</h5>
                <button id="closeChat" class="close-chat-btn">X</button>
            </div>
            <div id="chatbox">
        <div id="messages"></div>
        <input type="text" id="userInput" placeholder="Ask something...">
        <button onclick="sendMessage()">Send</button> <br><br>
        <button onclick="sendQuery('Information related to Books')">Information related to books</button> <br><br> 
        <button onclick="sendQuery('Recommendations of Various Books')">Book Recommendations</button>
        
        
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    
        $(document).ready(function() { 
        // Open the chatbot when the button is clicked
        $("#chatbotBtn").click(function() {
            $("#chatbox").toggle();
        });

        // Close the chatbot when the close button is clicked
        $("#closeChat").click(function() {
            $("#chatbox").hide();
        });
        });
        
    function sendMessage() {
        var userText = $("#userInput").val().trim();
        if (userText === "") return;
        
        $("#messages").append("<div><b>User:</b> " + userText + "</div>");
        $("#userInput").val("");  // Clear input field

        $.ajax({
            url: 'message.php',
            type: 'POST',
            data: { message: userText },
            success: function(response) {
                $("#messages").append("<div><b>Bot:</b> " + response + "</div>");
                scrollChatToBottom();
            }
        });
    }

    function sendQuery(queryText) {
        $("#messages").append("<div><b>User:</b> " + queryText + "</div>");

        $.ajax({
            url: 'message.php',
            type: 'POST',
            data: { message: queryText },
            success: function(response) {
                $("#messages").append("<div><b>Bot:</b> " + response + "</div>");
                scrollChatToBottom();
            }
        });
    }
    

function scrollToBottom() {
    var chatBox = document.querySelector(".chatbot-content");
    chatBox.scrollTop = chatBox.scrollHeight;
}
function displayBookList(bookList) {
            let books = bookList.split('\n');
            let bookHtml = '<ul class="book-list">';
            for (let i = 1; i < books.length; i++) { // Skip first line "Recommended Books:"
                bookHtml += '<li>' + books[i] + '</li>';
            }
            bookHtml += '</ul>';
            $('#chat-box').append(bookHtml);
            $("#chat-box").scrollTop($("#chat-box")[0].scrollHeight);
        }

    </script>

</body>
</html>
