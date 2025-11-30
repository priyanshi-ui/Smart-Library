-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2025 at 04:58 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smartlibrary`
--

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE `author` (
  `author_id` varchar(20) NOT NULL,
  `author_name` varchar(30) NOT NULL,
  `book_id` int(20) NOT NULL,
  `publish_data` date NOT NULL,
  `author_specialization` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `book_id` int(11) NOT NULL,
  `book_title` varchar(100) NOT NULL,
  `book_edition` varchar(5) NOT NULL,
  `book_isbn` varchar(17) NOT NULL,
  `author_name` varchar(255) NOT NULL,
  `no_of_copy` int(50) NOT NULL,
  `domain_name` varchar(100) NOT NULL,
  `book_image` varchar(255) NOT NULL,
  `book_pdf` varchar(255) NOT NULL,
  `rack_no` varchar(10) DEFAULT NULL,
  `book_price` decimal(10,1) NOT NULL DEFAULT 0.0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`book_id`, `book_title`, `book_edition`, `book_isbn`, `author_name`, `no_of_copy`, `domain_name`, `book_image`, `book_pdf`, `rack_no`, `book_price`) VALUES
(4, 'Python Basics', '4th', '978-17-75093-32-9', 'Dan Bader, Joanna Jablonski, Fletcher Heisler ', 13, 'Programming Language', 'uploads/python_basics.jpg', 'ebooks/python-basics.pdf', 'C1', 3274.0),
(5, 'HTML 5 Black Book, Covers CSS 3, JavaScript, XML, XHTML, AJAX', '6th', '158-52-65241-21-2', 'Frozen', 7, 'Web Development ', 'uploads/black_book.jpg', '', 'B1', 899.0),
(6, 'Android Programming for Beginners ', '3rd', '788-85-52361-12-1', 'John Horton', 7, 'Mobile Development ', 'uploads/b_android.jpg', 'ebooks/android developer fundamentals course.pdf', 'A1', 3273.0),
(8, 'Computer Networks', '1st', '852-96-74123-96-5', 'Gupta Gaurav', 7, 'Computer Network', 'uploads/networks.jpg', 'ebooks/computer_network_and_internet.pdf', 'D1', 270.0),
(9, 'Beginning Angular with Typescript ', '5th', '188-52-65241-21-2', 'Lim, Greg', 6, 'Web Development', 'uploads/angular.jpg', 'ebooks/Beginning Angular 2 with Typescript.pdf', 'B2', 1107.0),
(11, 'The Complete Software Tester', '2nd', '598-87-96542-54-5', ' Kristin Jackvony', 4, ' Software Testing ', 'uploads/software_testing.jpg', 'ebooks/Complete-Software-Testing.pdf', 'E1', 449.0),
(12, 'PHP for Absolute Beginners', '2nd', '978-14-30268-15-4', ' Lengstorf Jason', 4, 'Web Development ', 'uploads/php-for-absolute-beginners.jpg', 'ebooks/php-for-absolute-beginners.pdf', 'B3', 3730.0),
(15, 'Computer Fundamentals and Operating  System', '1st', '978-93-84139-54-4', 'Dr Rajkumar R Rathod', 3, ' Operating Systems', 'uploads/c_os.jpg', '', 'F1', 1153.0),
(16, 'The Complete Reference PHP', '1st', '187-99-85697-47-0', 'Steven Holzner', 15, 'Web Development ', 'uploads/c_php.jpg', '', 'B4', 1115.0),
(17, 'Introduction to Database Management System', '2nd', '999-52-89654-87-6', 'Gillenson Mark L.', 3, 'Database', 'uploads/data.jpg', '', 'G1', 1899.0),
(18, 'Architecting Modern Web Applications with ASP.NET Core and Azure', '8th', '000-00-00000-00-0', ' Steve Smith', 6, 'Software Architecture', 'uploads/asp-net.jpg', 'ebooks/Architecting-Modern-Web-Applications-with-ASP.NET-Core-and-Azure.pdf', 'H1', 3800.0),
(19, 'Learning Python', '4th', '978-05-96158-06-4', 'Mark Lutz', 4, 'Programming Language', 'uploads/learining Python.jpg', 'ebooks/Learning_Python.pdf', 'C2', 5786.0),
(20, 'The Swift Programming Language ', '5.7', '852-96-86230-45-8', 'Christopher Arthur Lattne', 10, 'Programming Language', 'uploads/swift.png', 'ebooks/SwiftLanguage.pdf', 'C3', 0.0),
(21, 'Introduction to Computing', '1st', '123-45-96352-87-4', 'David Evas', 4, 'Computer Science', 'uploads/computer_science.jpg', 'ebooks/Intro_to_computing.pdf', 'I1', 1966.0),
(22, 'Database Design and Management ', '1st', '978-93-55851-64-2', 'A. A. Puntambekar', 10, 'Database', 'uploads/db_design.jpg', '', 'G2', 0.0),
(23, 'Java Programming ', '7th', '852-78-96325-52-8', 'Hari Mohan Pandey', 22, 'Programming Language', 'uploads/java begin.jpg', '', 'C4', 0.0),
(24, 'Android Application Development with Kotlin', '1st', '852-61-97456-64-2', 'Hardik Trivedi', 20, 'Mobile Development ', 'uploads/android.jpg', '', 'A2', 0.0),
(25, 'Operating Systems: Three Easy Pieces', '2nd', '145-75-85236-41-2', 'Remzi H. Arpaci-Dusseau and Andrea C. Arpaci-Dusseau', 8, 'Operating Systems', 'uploads/os.jpg', 'ebooks/operating-systems-three-easy-pieces.pdf', 'F2', 2652.0),
(37, 'The Algorithm Design Manual', '6th', '852-96-78965-52-1', 'Steven S. Skiena', 4, 'Algorithms and Data ', 'uploads/a_design.jpg', 'ebooks/the-algorithm-design-manual.pdf', 'J1', 3900.0),
(39, 'Introduction to Algorithms', '3rd', '789-96-85479-41-9', 'Thomas H. Cormen, Charles E. Leiserson, Ronald L. Rivest, and Clifford Stein', 7, 'Algorithms and Data ', 'uploads/algorithm.jpeg', 'ebooks/Introduction_to_algorithms.pdf', 'J2', 1190.0),
(41, 'Modern Operating Systems', '4th', '979-93-84139-54-4', 'Andrew S. Tanenbaum', 7, 'Operating Systems', 'uploads/m_os.jpg', 'ebooks/Modern Operating Systems.pdf', 'F3', 541.5),
(42, 'Operating System Concepts', '10th', '859-87-85948-85-2', 'Abraham Silberschatz, Peter B. Galvin, and Greg Gagne', 9, 'Operating Systems', 'uploads/operating.jpg', 'ebooks/Operating-System-Concepts.pdf', 'F4', 613.0),
(43, 'Data Structures and Algorithms Made Easy', '2nd', '758-98-56847-52-4', ' Narasimha Karumanchi', 5, 'Algorithms and Data ', 'uploads/data_a_easy.jpg', 'ebooks/data_structure.pdf', 'J3', 999.0),
(44, 'Introduction to Algorithms', '5th', '748-85-96325-41-3', 'Thomas H. Cormen, Charles E. Leiserson, Ronald L. Rivest, and Clifford Stein', 7, 'Algorithms and Data ', 'uploads/algorithm.jpeg', 'ebooks/Introduction_to_algorithms.pdf', 'J4', 600.0),
(45, 'Computer Networking: A Top-Down Approach', '3rd', '582-96-74112-23-5', 'James Kurose and Keith Ross', 6, 'Computer Network', 'uploads/c_network.jpg', 'ebooks/computer-networks-a-systems-approach.pdf', 'D2', 811.0),
(46, 'Artificial Intelligence: A Modern Approach', '2nd', '659-89-23458-15-7', ' Stuart Russell and Peter Norvig', 4, 'Artificial Intelligent', 'uploads/ai.jpg', 'ebooks/Artificial-intelligence-a-modern-approach.pdf', 'K1', 390.0),
(47, 'Pattern Recognition and Machine Learning', '2nd', '963-85-85211-55-7', 'Christopher M. Bishop', 10, 'Artificial Intelligent', 'uploads/patter_learn.jpg', 'ebooks/pattern-recognition-and-machine-learning.pdf', 'K2', 2700.0),
(48, 'The Mythical Man-Month', '1st', '741-85-96254-21-5', 'Frederick P. Brooks Jr.', 3, ' Software Engineering', 'uploads/mythical.jpg', 'ebooks/mythical-man-month.pdf', 'E1', 3300.0),
(49, 'Deep Learning', '3rd', '256-85-96412-87-3', 'Ian Goodfellow, Yoshua Bengio, and Aaron Courville', 3, 'Artificial Intelligent', 'uploads/deep_learn.jpg', 'ebooks/deep-learning.pdf', 'K3', 5669.0),
(50, 'HTML and CSS: Design and Build Websites', '1st', '258-36-98756-24-5', 'Jon Duckett', 5, 'Web Development ', 'uploads/html.jpg', 'ebooks/html&css.pdf', 'B5', 2051.0),
(51, 'Data Structures and Algorithms', '2nd', '456-89-78453-21-1', 'Alfred V. Aho, John E. Hopcroft, Jeffrey D. Ullman', 2, 'Algorithms and Data ', 'uploads/data_algorithm.jpg', '', 'J5', 500.0),
(52, 'Java: A Complete Practical Solution', '1st', '256-84-87965-15-0', 'Swati Saxena', 8, 'Programming Language', 'uploads/java.jpg', '', 'C5', 459.0),
(53, 'Hacking: The Art of Exploitation', '2nd', '963-80-74139-50-0', 'Jon Erickson', 7, 'Cybersecurity', 'uploads/hacking.jpg', 'ebooks/Hacking - The Art of Exploitation.pdf', 'L1', 2403.0),
(54, 'Cloud Computing: Concepts, Technology & Architecture', '2nd', '978-01-38052-25-6', 'Thomas Er', 11, 'Colud Computing', 'uploads/cloud.jpg', 'ebooks/Cloud Computing.pdf', 'M1', 3587.0),
(55, 'Functional Javascript', '1st', '978-14-49360-72-6', 'Michael Fogus', 4, 'Web Development ', 'uploads/javascript.jpg', 'ebooks/Functional JavaScript.pdf', 'B6', 985.2),
(56, 'A Beginners Guide to Python Programming', '2nd', '978-30-30202-89-7', 'John Hunt', 5, 'Programming Language', 'uploads/python3.jpg', 'ebooks/Python3.pdf', 'C6', 3928.0),
(57, 'The Relational Model for Database Management', '2nd', '978-02-01141-92-4', ' E. F. Codd', 6, 'Database', 'uploads/rdbms.jpg', 'ebooks/rdms-model.pdf', 'G3', 14500.0),
(58, 'Beginning App Development with Flutter', '3rd', '978-14-84251-80-5', ' Rap Payne', 4, 'Mobile Development ', 'uploads/begin_flutter.jpg', 'ebooks/Beginning App Development with Flutter.pdf', 'A3', 4036.0),
(59, 'JAVA- A Beginner Guide', '6th', '978-93-39213-03-9', 'SCHILDT', 2, 'Operating Systems', 'uploads/java_guide.jpg', 'ebooks/java-a-beginners-guide.pdf', 'F5', 390.0),
(60, 'Introduction to Programming Using Python', '1st', '978-01-32747-18-9', ' Liang Y. Daniel', 7, 'Programming Language', 'uploads/python.jpg', 'ebooks/Intro_Programming_Python.pdf', 'C7', 10220.0),
(62, 'Beginning C# and .NET', '2nd', '852-96-86230-45-2', 'Benjamin Perkins, Jon D. Reid', 3, 'Operating Systems', 'uploads/asp-net.jpg', 'ebooks/beginning-c-and-net.pdf', 'F6', 3207.0),
(63, 'Java: the complete reference book', '9th', '985-89-85472-65-1', 'Abc', 2, 'Operating Systems', 'uploads/java_9th.jpg', 'ebooks/javabook.pdf', 'F7', 6524.2);

-- --------------------------------------------------------

--
-- Table structure for table `book_location`
--

CREATE TABLE `book_location` (
  `rack_number` int(11) NOT NULL,
  `book_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book_reserve`
--

CREATE TABLE `book_reserve` (
  `reserve_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `reserve_date` date NOT NULL,
  `member_id` int(11) NOT NULL,
  `status` enum('Reserved','Unreserved','Pending') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_reserve`
--

INSERT INTO `book_reserve` (`reserve_id`, `book_id`, `reserve_date`, `member_id`, `status`) VALUES
(1, 9, '2024-10-19', 40, 'Reserved'),
(2, 5, '2024-10-20', 4, 'Reserved'),
(3, 4, '2024-10-20', 40, 'Reserved'),
(8, 6, '2024-10-20', 40, 'Reserved'),
(9, 8, '2024-10-20', 40, 'Reserved'),
(18, 16, '2024-10-20', 40, 'Reserved'),
(19, 20, '2024-10-20', 40, 'Reserved'),
(20, 4, '2024-10-21', 40, 'Unreserved'),
(21, 4, '2024-12-13', 41, 'Reserved'),
(22, 16, '2024-12-13', 41, 'Unreserved'),
(23, 22, '2024-12-13', 41, 'Unreserved'),
(24, 18, '2025-01-09', 40, 'Reserved'),
(25, 4, '2025-01-09', 40, 'Reserved'),
(26, 4, '2025-01-09', 40, 'Reserved'),
(27, 4, '2025-01-09', 40, 'Reserved'),
(28, 5, '2025-01-09', 40, 'Pending'),
(29, 4, '2025-01-09', 40, 'Reserved'),
(30, 4, '2025-01-09', 40, 'Reserved'),
(31, 4, '2025-01-09', 40, 'Pending'),
(32, 4, '2025-01-09', 40, 'Reserved'),
(33, 4, '2025-01-09', 40, 'Pending'),
(34, 5, '2025-01-09', 40, 'Reserved'),
(35, 4, '2025-01-09', 40, 'Reserved'),
(36, 4, '2025-01-09', 40, 'Reserved'),
(37, 4, '2025-01-09', 40, 'Reserved'),
(38, 18, '2025-01-09', 40, 'Reserved'),
(39, 6, '2025-01-09', 40, 'Pending'),
(40, 8, '2025-01-09', 40, 'Reserved'),
(41, 16, '2025-01-09', 40, 'Reserved'),
(42, 18, '2025-01-09', 40, 'Pending'),
(43, 49, '2025-03-06', 53, 'Pending'),
(44, 43, '2025-03-08', 40, 'Pending'),
(46, 55, '2025-03-14', 40, 'Pending'),
(47, 4, '2025-03-22', 40, 'Pending'),
(48, 4, '2025-03-22', 60, 'Reserved'),
(49, 4, '2025-05-02', 51, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `chatbot`
--

CREATE TABLE `chatbot` (
  `id` int(11) NOT NULL,
  `queries` text NOT NULL,
  `replies` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chatbot`
--

INSERT INTO `chatbot` (`id`, `queries`, `replies`) VALUES
(1, 'hello', 'Hello! How can I assist you today?'),
(2, 'how are you?', 'I am just a bot, but I am doing great! How can I help you?'),
(3, 'what is your name?', 'I am a simple chatbot created to assist you with your queries.'),
(4, 'how can I contact support?', 'You can contact support via email at smartlibrary25@gmail.com'),
(5, 'what is PHP?', 'PHP is a popular general-purpose scripting language that is especially suited to web development.'),
(6, 'what is your purpose?', 'My purpose is to answer your queries and provide helpful information on various topics.'),
(7, 'what are your working hours?', 'I am available 24/7, ready to assist you at any time!'),
(8, 'where are you located?', 'I am a virtual assistant, so I don’t have a physical location. I’m here to help online.'),
(9, 'what can I ask you?', 'You can ask me anything related to our website or information related various programming books.'),
(10, 'What is Smart Library?', 'Smart Library is an automated web application that manages library activities like book issuance, returns, notifications, and record management, making library management more efficient.'),
(11, 'How can I access Smart Library?', 'You can access Smart Library by logging in with your Google account. It’s quick and easy!'),
(12, 'How can I search for a book in the library?', 'You can search for any book by its title, author, or subject. If the book is available, you can issue it.'),
(13, 'How can I contact the library for support?', 'You can contact the library through the \"Contact Us\" page or by emailing smartlibrary25@gmail.com for assistance.'),
(14, 'Information related to Books', 'SmartLibrary will provide the book info like Book name, Author name, Book Edition'),
(15, 'Recommendations of Various Books', 'Programming Books: The Complete Reference-PHP,Database Management System, Java, Android Application Development-Kotlin, Python for Begineers'),
(16, 'How do I check my due date?', 'Go to \"My Books\" on your dashboard.'),
(17, 'What happens if I return a book late?', 'A fine is charged per late day.'),
(18, 'Can I download e-books?', 'Yes, after earning five points.'),
(19, 'How do I earn points?', 'Read e-books for a set time.'),
(20, 'How do I access e-books?', 'Go to My Books and download if eligible.'),
(21, 'Where can I find books on AI?', 'Artificial Intelligence: A Modern Approach by Stuart Russell & Peter Norvig.'),
(22, 'Best book for learning Kotlin?', 'Android Application Development with Kotlin by Hardik Trivedi.'),
(23, 'Which book covers data structures well?', 'Data Structures and Algorithms Made Easy by Narasimha Karumanchi.'),
(24, 'Any books for computer networking?', 'Computer Networking: A Top-Down Approach by Kurose & Ross.'),
(25, 'Can I find books on ethical hacking?', 'Yes, Hacking: The Art of Exploitation is a great start.'),
(26, 'What’s a good book for JavaScript?', 'Functional JavaScript by Michael Fogus.'),
(27, 'Best book on algorithm design?', 'The Algorithm Design Manual by Steven S. Skiena.'),
(28, 'Any books on cloud computing?', 'Cloud Computing: Concepts, Technology & Architecture by Thomas Er.'),
(29, 'How can I improve my coding skills?', 'Read The Mythical Man-Month by Frederick P. Brooks Jr.'),
(30, 'How can I start learning Python?', 'Python Basics by Dan Bader or Learning Python by Mark Lutz.'),
(31, 'Best book for web development?', 'HTML and CSS: Design and Build Websites by Jon Duckett.'),
(32, 'Book on software testing?', 'The Complete Software Tester by Kristin Jackvony.'),
(33, 'Guide for machine learning?', 'Pattern Recognition and Machine Learning by Christopher M. Bishop.'),
(34, 'Best book for operating systems?', 'Operating Systems: Three Easy Pieces by Remzi H. Arpaci-Dusseau.'),
(35, 'Learn Swift programming?', 'The Swift Programming Language by Christopher Arthur Lattner.'),
(36, 'Book for database design?', 'Database Design and Management by A. A. Puntambekar.'),
(37, 'Guide to Java programming?', 'Java: A Complete Practical Solution by Swati Saxena.'),
(38, 'Best cybersecurity book?', 'Hacking: The Art of Exploitation by Jon Erickson.'),
(39, 'Learn cloud computing?', 'Cloud Computing: Concepts, Technology & Architecture by Thomas Er.');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `complaint_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `complaint_text` text NOT NULL,
  `complaint_date` datetime DEFAULT current_timestamp(),
  `status` enum('pending','solved') DEFAULT 'pending',
  `complaint_title` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `priority` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`complaint_id`, `member_id`, `complaint_text`, `complaint_date`, `status`, `complaint_title`, `category`, `priority`) VALUES
(1, 39, 'Very Bad Facility', '2024-10-11 13:45:14', 'solved', 'Not Proper', 'Service Issue', 'Medium '),
(2, 39, 'Good Services', '2024-10-11 13:46:11', 'solved', 'Good', 'Other', 'High'),
(3, 40, 'Good', '2024-10-11 18:48:29', 'solved', 'Good', 'Library Facility ', 'Medium '),
(4, 4, 'Website is good easy to use..', '2024-10-11 19:00:42', 'solved', 'Website\r\n', 'Other', 'High'),
(5, 41, 'I love how easy it is to find and borrow books!', '2024-10-11 19:02:01', 'solved', 'Book', 'Book Condition', 'High'),
(6, 40, 'The automated smart library system has made managing our library much easier. Highly recommended!', '2024-10-12 12:34:26', 'solved', 'Service', 'Library Facility', 'High'),
(11, 40, 'First Page of the Book is Very Bad.', '2024-10-12 19:15:26', 'pending', 'Book', 'Book Condition', 'Low'),
(12, 4, 'Not Interested in Book.\r\n', '2024-10-13 19:44:43', 'solved', 'Other', 'Other', 'Low'),
(13, 44, 'I am not able to issue book.', '2025-01-09 12:39:59', 'solved', 'Issue Book', 'Service Issue', 'Medium'),
(14, 5, 'Book not Proper.', '2025-02-05 19:51:34', 'pending', 'Book', 'Service Issue', 'Low'),
(15, 26, 'I am not able to reserve my book properly.', '2025-03-03 18:50:27', 'pending', 'Reservation Problem', 'Other', 'Medium');

-- --------------------------------------------------------

--
-- Table structure for table `fines`
--

CREATE TABLE `fines` (
  `fine_id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `paid_date` date DEFAULT NULL,
  `status` enum('unpaid','paid') DEFAULT 'unpaid',
  `issue_id` int(11) DEFAULT NULL,
  `date_of_fine` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fines`
--

INSERT INTO `fines` (`fine_id`, `member_id`, `book_id`, `amount`, `paid_date`, `status`, `issue_id`, `date_of_fine`) VALUES
(1, 4, 6, 34.00, '2025-03-02', 'paid', NULL, '2024-10-02'),
(2, 4, 5, 60.00, '2024-12-02', 'paid', NULL, '2024-11-15'),
(3, 1, 4, 12.00, '2024-10-20', 'paid', NULL, '2024-08-12'),
(4, 5, 9, 50.00, '2024-11-11', 'paid', NULL, '2024-02-19'),
(5, 1, 11, 73.00, NULL, 'unpaid', NULL, '2024-07-12'),
(6, 40, 4, 150.00, '2023-10-12', 'paid', NULL, '2023-02-21'),
(7, 4, 17, 50.00, '2023-10-21', 'paid', NULL, '2023-05-26'),
(8, 4, 4, 76.00, '2023-12-02', 'paid', NULL, '2023-09-28'),
(9, 5, 5, 194.00, '2025-02-06', 'paid', NULL, '2024-12-31'),
(10, 37, 6, 200.00, '2024-12-02', 'paid', NULL, '2024-05-22'),
(11, 40, 8, 54.00, '2024-12-09', 'paid', NULL, '2024-11-05'),
(12, 4, 9, 18.00, '2025-03-22', 'paid', NULL, '2024-11-23'),
(13, 4, 11, 119.00, '2024-02-06', 'paid', NULL, '2024-01-25'),
(14, 1, 4, 182.00, NULL, 'unpaid', NULL, '2025-02-18'),
(15, 4, 4, 120.00, '2025-03-02', 'paid', NULL, '2025-01-03'),
(16, 4, 5, 112.00, '2023-12-10', 'paid', NULL, '2023-08-29'),
(17, 4, 8, 123.00, '2024-12-02', 'paid', NULL, '2024-10-25'),
(18, 41, 19, 60.00, '2024-12-02', 'paid', NULL, '2024-08-12'),
(19, 4, 9, 105.00, '2024-12-10', 'paid', NULL, '2024-06-18'),
(20, 42, 20, 120.00, '2024-12-10', 'paid', NULL, '2024-12-18'),
(22, 1, 4, 14.00, '2025-02-06', 'paid', NULL, '2025-01-15'),
(23, 4, 5, 119.00, '2025-04-27', 'paid', NULL, '2024-12-31'),
(24, 6, 22, 59.00, '2025-02-06', 'paid', NULL, '2024-11-30'),
(25, 26, 5, 45.00, '2025-02-17', 'paid', NULL, '2024-12-29'),
(26, 7, 12, 36.00, NULL, 'unpaid', NULL, '2025-02-02'),
(27, 4, 12, 186.00, '2025-02-17', 'paid', NULL, '2025-02-17'),
(29, 42, 8, 129.00, NULL, 'unpaid', NULL, '2025-02-17'),
(30, 4, 8, 213.00, '2025-03-22', 'paid', NULL, '2025-03-02'),
(31, 26, 16, 74.00, NULL, 'unpaid', NULL, '2025-04-05'),
(32, 51, 22, 44.00, NULL, 'unpaid', NULL, '2025-04-11'),
(33, 51, 25, 24.00, NULL, 'unpaid', NULL, '2025-04-11'),
(34, 51, 53, 44.00, NULL, 'unpaid', NULL, '2025-04-11'),
(35, 5, 20, 112.00, NULL, 'unpaid', NULL, '2025-04-27'),
(36, 53, 53, 43.00, '2025-05-02', 'paid', NULL, '2025-05-02');

-- --------------------------------------------------------

--
-- Table structure for table `issue_book`
--

CREATE TABLE `issue_book` (
  `issue_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `date_of_issue` date NOT NULL,
  `book_id` int(11) NOT NULL,
  `due_date` date NOT NULL,
  `status` enum('issued','returned') DEFAULT 'issued'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issue_book`
--

INSERT INTO `issue_book` (`issue_id`, `member_id`, `date_of_issue`, `book_id`, `due_date`, `status`) VALUES
(10, 1, '2025-01-25', 11, '2025-07-30', 'returned'),
(11, 1, '2023-12-25', 4, '2024-11-30', 'returned'),
(12, 4, '2023-10-28', 8, '2024-08-02', 'returned'),
(13, 4, '2022-05-12', 5, '2024-08-13', 'returned'),
(14, 4, '2022-08-19', 9, '2024-08-20', 'returned'),
(15, 4, '2023-10-25', 4, '2024-08-07', 'returned'),
(16, 4, '2022-10-25', 12, '2024-08-15', 'returned'),
(17, 4, '2025-02-25', 11, '2025-08-06', 'issued'),
(18, 4, '2022-08-25', 17, '2024-10-02', 'returned'),
(23, 5, '2024-05-15', 9, '2024-08-23', 'returned'),
(24, 4, '2025-01-25', 8, '2024-08-30', 'returned'),
(25, 5, '2024-10-25', 5, '2024-12-31', 'returned'),
(26, 4, '2023-10-18', 8, '2024-08-22', 'returned'),
(33, 4, '2024-02-29', 6, '2024-09-08', 'returned'),
(43, 41, '2025-01-07', 19, '2025-10-04', 'returned'),
(44, 42, '2024-08-12', 8, '2024-10-12', 'returned'),
(45, 42, '2024-05-15', 8, '2024-10-12', 'returned'),
(46, 42, '2023-02-04', 16, '2024-10-04', 'returned'),
(48, 40, '2022-04-08', 8, '2024-10-10', 'returned'),
(49, 37, '2022-01-16', 6, '2025-02-21', 'returned'),
(50, 40, '2023-11-22', 4, '2025-03-31', 'returned'),
(51, 42, '2024-12-15', 20, '2024-12-01', 'returned'),
(52, 41, '2024-09-18', 21, '2025-01-29', 'issued'),
(53, 6, '2023-10-31', 22, '2024-11-12', 'returned'),
(54, 5, '2024-02-13', 20, '2025-01-06', 'returned'),
(55, 7, '2024-12-25', 12, '2025-01-11', 'returned'),
(56, 26, '2024-11-25', 5, '2024-12-24', 'returned'),
(57, 6, '2025-01-25', 22, '2025-02-12', 'returned'),
(58, 51, '2025-01-25', 22, '2025-02-27', 'returned'),
(59, 51, '2025-01-02', 23, '2025-03-27', 'issued'),
(60, 44, '2025-01-02', 23, '2025-03-07', 'issued'),
(61, 26, '2024-08-05', 16, '2025-01-22', 'returned'),
(62, 51, '2025-02-25', 25, '2025-03-19', 'returned'),
(63, 5, '2025-03-01', 23, '2025-03-20', 'issued'),
(64, 41, '2025-03-01', 25, '2025-04-03', 'issued'),
(65, 44, '2025-03-01', 51, '2025-03-16', 'issued'),
(66, 51, '2025-03-01', 53, '2025-02-27', 'returned'),
(67, 40, '2025-03-01', 48, '2025-03-20', 'issued'),
(68, 53, '2025-03-06', 53, '2025-03-21', 'returned'),
(69, 44, '2025-03-06', 52, '2025-03-27', 'issued'),
(72, 60, '2025-04-08', 37, '2025-04-23', 'issued'),
(73, 41, '2025-04-08', 17, '2025-04-24', 'issued'),
(74, 53, '2025-04-08', 50, '2025-05-03', 'issued'),
(75, 58, '2025-04-08', 54, '2025-04-23', 'issued'),
(76, 54, '2025-04-08', 48, '2025-04-23', 'issued'),
(77, 59, '2025-04-08', 53, '2025-04-23', 'issued'),
(78, 59, '2025-04-09', 49, '2025-04-24', 'issued'),
(79, 62, '2025-05-02', 46, '2025-05-17', 'issued');

-- --------------------------------------------------------

--
-- Table structure for table `librarian`
--

CREATE TABLE `librarian` (
  `librarian_id` int(11) NOT NULL,
  `l_name` varchar(30) NOT NULL,
  `l_email` varchar(30) NOT NULL,
  `l_contact` varchar(10) NOT NULL,
  `l_address` varchar(50) DEFAULT NULL,
  `l_gender` char(1) DEFAULT NULL,
  `l_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `librarian`
--

INSERT INTO `librarian` (`librarian_id`, `l_name`, `l_email`, `l_contact`, `l_address`, `l_gender`, `l_password`) VALUES
(2, 'Harshita', 'harshita12@gmail.com', '9478563150', 'Navsari', 'F', '$2y$10$8HQPYCiH9R2sgKgK5Q6cuO0rFbY6o59ICubeNDHQa5ve/lD7ZKeZC'),
(3, 'Priya', 'priya12@gmail.com', '9478523150', 'Valsad', 'F', '$2y$10$3yYPMC4QK5wYGycPYx6eK.t//BH.bKj7rppD/Z8YFV5Uk7NbLcDha'),
(4, 'Priya Patel', 'priya12@gmail.com', '9632587410', 'Valsad', 'F', '$2y$10$UzJqlcqGhIFlguoNLuISkuLPuz6IuX5OraPN4JHLkED9e2NaxOFLi'),
(5, 'Daksh', 'daksh@gmail.com', '7896541230', 'Surat', 'M', '$2y$10$PEWK7BqMy8ziySLVkXiEbOqNmFCXCF6dgZTmImmYi.ExfAAt.h1Ai'),
(6, 'Priyanshi', 'priyanshi@gmail.com', '9712773470', 'Surat', 'F', '$2y$10$hSC8zQzW08BEHimASYw/Ou1VR.msjEmeMULZcsupghP/v7gXUWP3m'),
(7, 'Mirali', 'mirali@gmail.com', '9879754140', '43,Vrudavan', 'M', '$2y$10$gI6Wnmyv4kPOTfaRn681k.znS3Hlk6ky9Ok1JJlhALsixlhNJCx5u'),
(17, 'Samrita', 'smartlibrary3456@gmail.com', '7410852963', 'Kamrej', 'O', '$2y$10$J/ht7vnvgELjA47mOAFcYujuZAWX8fivL2HWVf0AGrHEbLJNaioHe'),
(18, 'Mirali', 'www.ppatel3831@gmail.com', '9879754140', 'Kamrej', 'M', '$2y$10$C5KKjbTklT99rWuZodmONuQ28s6udg4FzOwaYZS8qWP8iWIY0JrvK'),
(19, 'Shrushti', 'shrushtihirani25@gmail.com', '9712789654', 'Sachin', 'F', '$2y$10$BeC8i.QcZxGrl.m8FrvdhuWfXG8lgLEeZlcNcFE1WChGjqXxQXr5.'),
(22, '', '', '', '', '', '$2y$10$Kk89R6N4J3B8oyhmoYJxo.jDiZ.S9GafoEkhY.fuhrUJNis0psUry');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `member_id` int(11) NOT NULL,
  `m_name` varchar(30) DEFAULT NULL,
  `m_email` varchar(50) DEFAULT NULL,
  `m_address` varchar(50) DEFAULT NULL,
  `m_contact` char(10) DEFAULT NULL,
  `m_gender` char(1) NOT NULL,
  `institute_name` varchar(50) DEFAULT NULL,
  `m_password` varchar(255) DEFAULT NULL,
  `status` enum('1','0') NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `points` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`member_id`, `m_name`, `m_email`, `m_address`, `m_contact`, `m_gender`, `institute_name`, `m_password`, `status`, `last_login`, `points`) VALUES
(1, 'Dhruv', 'dhruv@gmail.com', 'Nana Varcha', '9874563210', 'M', 'bvpics', '$2y$10$RrfJRiJR9cjVpoPGYDX7PO9GFsGo3X8jrXOAF.cR1SlCVYSzHY.Pq', '', '2023-03-02 19:03:18', 0),
(4, 'Nidhi', 'nidhi@gmail.com', 'Surat', '9854763210', 'F', 'BVPICS', '$2y$10$FqBbi2n7dkXkivndpSmvluQoioBbf85HOoBZcdWu2Hej05QTyn2LK', '', '2023-03-02 12:51:11', 0),
(5, 'Grenshi', 'kumbhanigrenshi@gmail.com', 'Kamrej', '9879754140', 'F', 'Srimca', '$2y$10$ez8y0c89bVlENIu9E07FN.bWOTDE1yGwyYluS1svJatJ2.3SVsK0a', '', '2023-05-02 08:56:50', 4),
(6, 'Dax', 'daksh@gmail.com', 'Surat', '9712773470', 'M', 'vnsgu', '$2y$10$EhUewS55iMMJYL34YgrkxeVPTUx2lyKp1zKw3Z9s3Opwah3XBTLOq', '1', '2024-11-08 11:22:54', 0),
(7, 'Preet', 'preet@gmail.com', 'Amreli', '7896541230', 'M', 'Parul University', '$2y$10$72dgmF0v1SG.2ADX2LmKXu6/zRI3R8px5343Ct0Nw90koF9HkQ3/q', '1', '2025-04-11 12:50:16', 0),
(10, 'Krupa', 'krupa@gmail.com', 'Bardoli', '7896541230', 'O', 'SRIMCA', '$2y$10$1UxGQPBNyrw9bzukHOdEPOB47QGEn8ZNZ3m5O8GW7Pim0Il9nYDHK', '1', '2025-03-02 12:59:57', 0),
(26, 'Rahil', 'rahil@gmail.com', 'Navsari', '9712773470', 'M', 'BVPICS', '$2y$10$sPcC7vce8em.fcLIWz44CeS1sw5SlgRWNgW0WDmiAAps8xwNcJ9Na', '1', '2025-03-16 17:53:06', 0),
(37, 'Pinal', 'pinal@gmail.com', 'Ahmedabad ', '9712773470', 'F', 'Srimca', '$2y$10$kueSGH804V2nwJIXOQPoEOxgUDhLUgLInKVu/StF9arjBh8CVsc1.', '1', '2024-12-29 04:29:46', 0),
(39, 'Ansh', 'ansh@gmail.com', 'Sachin', '7896541230', 'O', 'Aakash', '$2y$10$fQn0Q44hfyzWlwD.Tukn.eKOZ8hB7hzF0dn3HXBtPwarIWiBm8m7e', '1', '2025-04-11 12:50:01', 0),
(40, 'Priyanshi', 'pihupatel7806@gmail.com', 'Sachin', '7990411590', 'F', 'Srimca', '$2y$10$9NySk1ca4.zdrOrVvns19eFmGGoIwx8OA9EHmCSfdgCcwAXdNUX8C', '1', '2025-08-01 08:43:59', 23),
(41, 'Dhruv', 'dhruvkheni321@gmail.com', 'Surat', '8569741130', 'M', 'Srimca', '$2y$10$5ymnMmvcVqjNq2ndj74.pupb2ST57EVv1WrzGyQw.9eBCG117MeCq', '1', '2025-03-27 11:07:42', 0),
(42, 'Priyanshi', 'ramanipriyanshi7@gmail.com', 'Kosamada', '6988744123', 'F', 'Srimca', '$2y$10$w7SC3gtouqoCaJan6zBj7eqRtAF98lncOe8QNOJgdQAhkGgkTtXvi', '1', '2025-02-18 04:47:17', 0),
(44, 'Krishna', 'kishupatel1699@gmail.com', 'Surat', '9658741230', 'F', 'Parul University', '$2y$10$1sqoJopkJCMJ.uR.L78PGOrA181sJWiIZZS78MQCHrUGRZ/W5pBQ2', '1', '2023-08-04 00:00:00', 0),
(51, 'Viren', 'virenvora999@gmail.com', 'Surat', '9630258741', 'M', 'Srimca', '$2y$10$ZEFdUOqHivNKjpbUN.T60ORc1wyafo.6H2aj5/EwqCuG3c.nE38Fm', '1', '2025-05-02 12:26:25', 18),
(52, 'Zalak', '23bmiit0050@gamil.com', 'Surat', '7896541230', 'M', 'Bmiit', '$2y$10$W0ooM7QzuepL7T3ZuHQ4ruLbvVMqbPRH5PQNni2e4fA7o9DPjH.5G', '1', '2024-08-12 00:00:00', 0),
(53, 'Yash ', 'virenvora45@gmail.com', 'Surat', '8521479630', 'M', 'vnsgu', '$2y$10$166YXGJRGzwcB4iaDc.A5eerOlTRE5CMI02lj30JjBT6qAd7hl9WW', '1', '2025-03-21 19:32:53', 0),
(54, 'Shrushti', 'shrushtihirani08@gmail.com', 'Sachin', '9712750140', 'F', 'Srimca', '$2y$10$2Ei.v4SS8d0eqfboE21/3.BlIkBNHL1q7GGY12SLpv7N/fFM5YgH2', '1', '2025-04-27 21:39:31', 5),
(56, 'Aarti', 'ashvinipadsala@gmail.com', 'Surat', '8529630741', 'F', 'Parul University', '$2y$10$SHm/SMF0kcrRa8G13zbeaOnheA0Bf394eecXsNVfB3NOw0EQovMEC', '1', NULL, 0),
(57, 'Viral', 'viralgondaliya88@gmail.com', 'Kamrej', '9638520741', 'F', 'Saurashtra', '$2y$10$UVMSrGVdsGDgLd.p2iuupeTK5aBsQCr2boyQYWFSCHGWGm/gtNw5W', '1', '2024-05-21 00:00:00', 0),
(58, 'Renuka', 'renukahirani4@gmail.com', 'Rajkot', '9658741230', 'F', 'BVPICS', '$2y$10$A/5CdxMrH3T6H2WE9K7cJOVeyoXr8GjposKYp93mYGviaDBrdyT8.', '1', '2025-04-09 17:35:23', 0),
(59, 'Zeel', 'zeelhirani8@gmail.com', 'Surat', '9712750140', 'M', 'Saurashtra', '$2y$10$2ZMeH8ZuP.T3Is75vOrk8OFypREN9tcn8QmwsPmbDpRzbCZJOrrFW', '1', '2025-04-27 23:24:21', 68),
(60, 'Harshita', 'najkaniharshita28@gmail.com', '43,Vrudavan', '9712773470', 'F', 'Saurashtra', '$2y$10$t/IU2k/Z97YLfj9B9364YeSBvqv88PFhUUSJcLrVOotMrNu4Ppg02', '1', '2025-03-22 11:32:04', 1),
(61, 'Pratham', 'prathamantala2005@gmail.com', 'Kamrej', '7859874526', 'M', 'Saurashtra', '$2y$10$8I4jBozX498imjKzK7v67OEAvNXa13DNpRwsWHQFzc/Iz5ZXHYgbm', '1', '2025-05-02 11:21:24', 0),
(62, 'Payal', 'payalptl1004@gmail.com', 'Bardoli', '9712773470', 'F', 'Parul University', '$2y$10$7sU87XH0zu05r/HDztTvyOMibFaqR4yhMq9bvlkTzoLZFeSlUYrp6', '1', '2025-05-02 12:31:39', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reading_time`
--

CREATE TABLE `reading_time` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `reading_time` float NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reading_time`
--

INSERT INTO `reading_time` (`id`, `member_id`, `book_id`, `reading_time`, `timestamp`) VALUES
(1, 40, 4, 9.676, '2025-03-15 09:58:47'),
(2, 40, 4, 2.917, '2025-03-15 09:59:03'),
(5, 40, 4, 6.695, '2025-03-21 21:48:55'),
(7, 40, 4, 6.414, '2025-03-22 08:52:23'),
(8, 40, 4, 6.668, '2025-03-22 08:53:24'),
(9, 40, 4, 16.072, '2025-03-22 09:13:40'),
(10, 40, 4, 4.667, '2025-03-22 09:16:48'),
(11, 60, 4, 15.816, '2025-03-22 11:33:09'),
(13, 59, 4, 7.689, '2025-04-08 20:08:33'),
(14, 59, 4, 5.805, '2025-04-08 20:13:31'),
(15, 59, 4, 4.746, '2025-04-08 20:17:20'),
(16, 59, 4, 5.191, '2025-04-08 22:32:51'),
(19, 51, 12, 11.365, '2025-04-15 19:09:13'),
(20, 51, 4, 1.855, '2025-04-15 19:12:46'),
(21, 51, 4, 1.922, '2025-04-15 19:12:51'),
(22, 51, 4, 3.519, '2025-04-15 21:33:43'),
(23, 51, 8, 5.461, '2025-04-15 21:38:29'),
(24, 40, 4, 6.343, '2025-04-15 22:10:35'),
(25, 40, 4, 2.975, '2025-04-15 22:11:08'),
(26, 59, 4, 2.324, '2025-04-18 10:30:38'),
(27, 59, 4, 10.282, '2025-04-18 10:41:31'),
(28, 59, 4, 6.122, '2025-04-18 10:43:32'),
(29, 59, 4, 21.092, '2025-04-18 10:59:39'),
(30, 59, 4, 6.36, '2025-04-18 10:59:52'),
(31, 59, 8, 4.566, '2025-04-18 11:25:55'),
(32, 40, 4, 15.569, '2025-04-18 12:53:35'),
(33, 54, 4, 19.039, '2025-04-27 21:40:08'),
(34, 5, 4, 6.216, '2025-04-27 23:09:43'),
(36, 5, 4, 6.908, '2025-05-02 08:57:06'),
(37, 5, 4, 2.397, '2025-05-02 08:57:12'),
(38, 5, 4, 1.342, '2025-05-02 09:00:59'),
(39, 5, 4, 10.527, '2025-05-02 09:01:29'),
(40, 5, 4, 6.403, '2025-05-02 10:00:07'),
(41, 5, 4, 1.346, '2025-05-02 10:00:23'),
(42, 5, 12, 3.228, '2025-05-02 10:00:43'),
(43, 5, 4, 3.222, '2025-05-02 10:07:47'),
(44, 5, 4, 6.686, '2025-05-02 10:12:51'),
(45, 51, 4, 23.243, '2025-05-02 12:29:10');

-- --------------------------------------------------------

--
-- Table structure for table `return_book`
--

CREATE TABLE `return_book` (
  `return_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `date_of_return` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `return_book`
--

INSERT INTO `return_book` (`return_id`, `book_id`, `member_id`, `date_of_return`) VALUES
(33, 8, 42, '2024-12-11'),
(34, 6, 4, '2024-11-11'),
(35, 5, 4, '2024-10-11'),
(36, 4, 1, '2024-09-11'),
(37, 9, 5, '2024-08-11'),
(38, 11, 1, '2024-07-11'),
(39, 4, 40, '2024-06-12'),
(40, 17, 4, '2024-05-21'),
(41, 4, 4, '2024-04-21'),
(42, 5, 5, '2024-03-02'),
(43, 6, 37, '2023-12-02'),
(44, 8, 40, '2023-10-02'),
(45, 9, 4, '2023-08-02'),
(46, 11, 4, '2023-02-02'),
(47, 4, 1, '2023-07-02'),
(48, 4, 4, '2023-02-04'),
(49, 5, 4, '2022-12-02'),
(50, 8, 4, '2022-11-02'),
(51, 19, 41, '2022-10-02'),
(52, 9, 4, '2023-12-02'),
(53, 20, 42, '2024-12-02'),
(54, 11, 1, '2022-01-09'),
(55, 16, 42, '2025-02-09'),
(56, 22, 6, '2025-01-10'),
(57, 4, 1, '2024-12-13'),
(58, 5, 4, '2024-12-13'),
(59, 22, 6, '2025-01-09'),
(60, 5, 26, '2025-01-30'),
(61, 12, 7, '2025-02-15'),
(62, 12, 4, '2025-02-16'),
(63, 8, 42, '2025-02-17'),
(64, 8, 42, '2025-02-17'),
(65, 8, 4, '2025-03-02'),
(66, 16, 26, '2025-04-05'),
(67, 22, 51, '2025-04-11'),
(68, 25, 51, '2025-04-11'),
(69, 53, 51, '2025-04-11'),
(70, 20, 5, '2025-04-27'),
(71, 53, 53, '2025-05-02');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `fullname` varchar(30) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(30) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `admin_type` varchar(20) NOT NULL,
  `parent_admin` varchar(20) NOT NULL,
  `username` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `fullname`, `password`, `email`, `phone`, `admin_type`, `parent_admin`, `username`) VALUES
(1, 'Arjun Dhakal', '724d6f12d48ab4d0d57413824305b013', 'ghchnepal@mail.com.np', '9841312498', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`author_id`),
  ADD KEY `Book_ID` (`book_id`);

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`book_id`),
  ADD UNIQUE KEY `unique_isbn` (`book_isbn`);

--
-- Indexes for table `book_location`
--
ALTER TABLE `book_location`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `book_reserve`
--
ALTER TABLE `book_reserve`
  ADD PRIMARY KEY (`reserve_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `chatbot`
--
ALTER TABLE `chatbot`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`complaint_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `fines`
--
ALTER TABLE `fines`
  ADD PRIMARY KEY (`fine_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `fines_ibfk_3` (`issue_id`);

--
-- Indexes for table `issue_book`
--
ALTER TABLE `issue_book`
  ADD PRIMARY KEY (`issue_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `foreign key` (`member_id`);

--
-- Indexes for table `librarian`
--
ALTER TABLE `librarian`
  ADD PRIMARY KEY (`librarian_id`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`member_id`);

--
-- Indexes for table `reading_time`
--
ALTER TABLE `reading_time`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `return_book`
--
ALTER TABLE `return_book`
  ADD PRIMARY KEY (`return_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `book`
--
ALTER TABLE `book`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `book_reserve`
--
ALTER TABLE `book_reserve`
  MODIFY `reserve_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `chatbot`
--
ALTER TABLE `chatbot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `complaint_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `fines`
--
ALTER TABLE `fines`
  MODIFY `fine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `issue_book`
--
ALTER TABLE `issue_book`
  MODIFY `issue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `librarian`
--
ALTER TABLE `librarian`
  MODIFY `librarian_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `reading_time`
--
ALTER TABLE `reading_time`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `return_book`
--
ALTER TABLE `return_book`
  MODIFY `return_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `book_reserve`
--
ALTER TABLE `book_reserve`
  ADD CONSTRAINT `book_reserve_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`),
  ADD CONSTRAINT `book_reserve_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `book` (`book_id`);

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE CASCADE;

--
-- Constraints for table `fines`
--
ALTER TABLE `fines`
  ADD CONSTRAINT `fines_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`),
  ADD CONSTRAINT `fines_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `book` (`book_id`),
  ADD CONSTRAINT `fines_ibfk_3` FOREIGN KEY (`issue_id`) REFERENCES `issue_book` (`issue_id`);

--
-- Constraints for table `issue_book`
--
ALTER TABLE `issue_book`
  ADD CONSTRAINT `foreign key` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`),
  ADD CONSTRAINT `issue_book_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `book` (`book_id`);

--
-- Constraints for table `reading_time`
--
ALTER TABLE `reading_time`
  ADD CONSTRAINT `reading_time_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`),
  ADD CONSTRAINT `reading_time_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `book` (`book_id`);

--
-- Constraints for table `return_book`
--
ALTER TABLE `return_book`
  ADD CONSTRAINT `return_book_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `book` (`book_id`),
  ADD CONSTRAINT `return_book_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
