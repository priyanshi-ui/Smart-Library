<?php
session_start();

include('database.php');

// Check if a member or librarian is logged in
$is_member_logged_in = isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'Member';
$is_librarian_logged_in = isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'Librarian';

if (!$is_librarian_logged_in && !$is_member_logged_in) {
    echo "No user is logged in";
    exit();
}

$user = null;
$name = $email = $contact = $address = $gender_display = "";

// Fetch details based on the user type (member or librarian)
if ($is_member_logged_in) {
    $member_id = $_SESSION['user_id']; // Using consistent session variable
    $stmt = $conn->prepare("SELECT * FROM member WHERE member_id = ?");
    $stmt->bind_param('s', $member_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "Member data not found";
        exit();
    }
    $user = $result->fetch_assoc();
    $stmt->close();

    // Set user details for member
    $name = htmlspecialchars($user['m_name']);
    $email = htmlspecialchars($user['m_email']);
    $contact = htmlspecialchars($user['m_contact']);
    $address = htmlspecialchars($user['m_address']);
    $gender = htmlspecialchars($user['m_gender']);
    $gender_display = ($gender == 'M') ? "Male" : (($gender == 'F') ? "Female" : "Other");
} elseif ($is_librarian_logged_in) {
    $librarian_id = $_SESSION['user_id']; // Using consistent session variable
    $stmt = $conn->prepare("SELECT * FROM librarian WHERE librarian_id = ?");
    $stmt->bind_param('s', $librarian_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "Librarian data not found";
        exit();
    }
    $user = $result->fetch_assoc();
    $stmt->close();

    // Set user details for librarian
    $name = htmlspecialchars($user['l_name']);
    $email = htmlspecialchars($user['l_email']);
    $contact = htmlspecialchars($user['l_contact']);
    $address = htmlspecialchars($user['l_address']);
    $gender = htmlspecialchars($user['l_gender']);
    $gender_display = ($gender == 'M') ? "Male" : (($gender == 'F') ? "Female" : "Other");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? $_POST['name'] : $name;
    $email = isset($_POST['email']) ? $_POST['email'] : $email;
    $contact = isset($_POST['phone']) ? $_POST['phone'] : $contact;

    // Update query based on the user type
    if ($is_member_logged_in) {
        $sql_update = "UPDATE member SET 
            m_name = ?, 
            m_email = ?, 
            m_contact = ? 
            WHERE member_id = ?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param('ssss', $name, $email, $contact, $member_id);
    } elseif ($is_librarian_logged_in) {
        $sql_update = "UPDATE librarian SET 
            l_name = ?, 
            l_email = ?, 
            l_contact = ? 
            WHERE librarian_id = ?";
        $stmt->bind_param('ssss', $name, $email, $contact, $librarian_id);
    }

    if ($stmt->execute()) {
        // Update session email to reflect the new login email
        $_SESSION['email'] = $email;
        echo '<script>alert("Data updated successfully");</script>';
    } else {
        echo '<script>alert("Error updating data: ' . $stmt->error . '");</script>';
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
   
    <style> 
        body {
            background-image: url('background/book1.jpg'); /* Replace with your image path */
            background-size: cover;  /* Makes the image cover the entire page */
            background-position: center center; /* Centers the background image */
            background-repeat: no-repeat;  /* Prevents the image from repeating */
            height: auto; /* Sets the height of the body to cover the full viewport */
            font-family: 'Arial', sans-serif;
        }

        .profile-container {
            max-width: 700px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-header h3 {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }

        .profile-body {
            padding: 20px 0;
        }

        .profile-field {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #e3e3e3;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            color: #555;
        }

        .profile-field:focus {
            border-color: #007bff;
            outline: none;
        }

        .btn-container {
            text-align: center;
        }

        .btn {
            width: 45%;
            padding: 12px;
            border-radius: 5px;
            font-size: 16px;
            margin: 5px;
        }

        .btn-primary {
            color: #fff;
        border: 2px solid #4b0082;
        border-radius: 5px;
        background-color:#45076a;
        transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #fff;
            border-color: #45076a;
            color:#45076a;
        }

        .btn-secondary {
            color: #fff;
        border: 2px solid #960018;
        border-radius: 5px;
        background-color:#960018;
        transition: background-color 0.3s;
        }

        .btn-secondary:hover {
            background-color: #fff;
            border-color: #960018;
            color:#960018;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <h3><i class="far fa-clone"></i> Welcome, <?php echo $name; ?></h3>
        </div>
        <div class="profile-body">
            <form action="#" method="post">
                <fieldset>
                    <label for="name">Name:</label>
                    <input type="text" class="profile-field" name="name" id="name" value="<?php echo $name; ?>">
                    <label for="email">Email:</label>
                    <input type="text" class="profile-field" name="email" id="email" value="<?php echo $email; ?>" >
                    <label for="phone">Phone:</label>
                    <input type="tel" class="profile-field" name="phone" id="phone" value="<?php echo $contact; ?>">
                    <label for="address">Address:</label>
                    <textarea class="profile-field" name="address" id="address" readonly><?php echo $address; ?></textarea>
                    <label for="gender">Gender:</label>
                    <input type="text" class="profile-field" name="gender" id="gender" value="<?php echo $gender_display; ?>" readonly>
                    <div class="btn-container">
                        <button class="btn btn-primary">Update</button>
                        <?php
                            if ($is_member_logged_in) {
                                echo '<a href="member_dashboard.php" class="btn btn-secondary">Cancel</a>';
                            } elseif ($is_librarian_logged_in) {
                                echo '<a href="librarian_dashboard.php" class="btn btn-secondary">Cancel</a>';
                            }
                        ?>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</body>
</html>