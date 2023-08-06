<?php include '../inc/dashHeader.php'; ?>
<?php
// Include config file
require_once "../config.php";

$input_email = $email_err = $email = "";
$input_register_date = $register_date_err = $register_date = "";
$input_phone_number = $phone_number_err = $phone_number = "";
$input_password = $password_err = $password = "";
$input_membership_id = $membership_id = "";
$input_staff_id = $staff_id = "";

// Processing form data when form is submitted
if(isset($_POST['submit'])){
    // Validate and sanitize email
    if (empty($_POST['email'])) {
        $email_err = 'Email is required';
    } else {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        if (!$email) {
            $email_err = 'Invalid email format';
        }
    }

    // Validate and sanitize register date
    if (empty($_POST['register_date'])) {
        $register_date_err = 'Register date is required';
    } else {
        $register_date = $_POST['register_date'];
    }

    // Validate and sanitize phone number
    if (empty($_POST['phone_number'])) {
        $phone_number_err = 'Phone number is required';
    } else {
        $phone_number = filter_input(INPUT_POST, 'phone_number', FILTER_SANITIZE_STRING);
    }

    // Validate and sanitize password
    if (empty($_POST['password'])) {
        $password_err = 'Password is required';
    } else {
        $password = $_POST['password'];
    }

    // Sanitize membership and staff IDs
    $membership_id = $_POST['membership_id'] ?? null;
    $staff_id = $_POST['staff_id'] ?? null;

    // If there are no errors, insert the data into the database
    if (empty($email_err) && empty($register_date_err) && empty($phone_number_err) && empty($password_err)) {
        $insert_query = "INSERT INTO Account (email, register_date, phone_number, password, membership_id, staff_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ssssii", $email, $register_date, $phone_number, $password, $membership_id, $staff_id);

        if ($stmt->execute()) {
            // Success, redirect to success page or do something else
            header("location: success_create_account.php");
            exit();
        } else {
            echo "Error: " . $insert_query . "<br>" . $conn->error;
        }

        $stmt->close();
    }
}
?>
<head>
    <meta charset="UTF-8">
    <title>Create New Account</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 1300px; padding-left: 200px; padding-top: 80px  }
    </style>
</head>

<div class="wrapper">
    <h1>Johnny's Dining & Bar</h1>
    <h3>Create New Account</h3>
    <p>Please fill in Account Information Properly</p>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="ht-600 w-50">
        <div class="form-group">
            <label for="email" class="form-label">Email :</label>
            <input type="text" name="email" class="form-control <?php echo $email_err ? 'is-invalid' : ''; ?>" id="email" required placeholder="john@example.com" value="<?php echo $email; ?>"><br>
            <div class="invalid-feedback">
                <?php echo $email_err; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="register_date">Register Date :</label>
            <input type="date" name="register_date" id="register_date" required class="form-control <?php echo $register_date_err ? 'is-invalid' : ''; ?>" value="<?php echo $register_date; ?>"><br>
            <div class="invalid-feedback">
                <?php echo $register_date_err; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="phone_number">Phone Number :</label>
            <input type="text" name="phone_number" id="phone_number" required class="form-control <?php echo $phone_number_err ? 'is-invalid' : ''; ?>" value="<?php echo $phone_number; ?>"><br>
            <div class="invalid-feedback">
                <?php echo $phone_number_err; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="password">Password :</label>
            <input type="password" name="password" id="password" required class="form-control <?php echo $password_err ? 'is-invalid' : ''; ?>"><br>
            <div class="invalid-feedback">
                <?php echo $password_err; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="membership_id">Membership ID:</label>
            <input type="number" name="membership_id" id="membership_id" class="form-control" value="<?php echo $membership_id; ?>">
        </div>

        <div class="form-group">
            <label for="staff_id">Staff ID:</label>
            <input type="number" name="staff_id" id="staff_id" class="form-control" value="<?php echo $staff_id; ?>">
        </div>

        <div class="form-group">
            <input type="submit" name="submit" class="btn btn-primary" value="Create Account">
        </div>
    </form>
</div>

<?php include '../inc/dashFooter.php'; ?>
