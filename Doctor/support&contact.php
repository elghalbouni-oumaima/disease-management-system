<?php
require_once "Home.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Form submission
if (isset($_POST['submit_ticket'])) {
    $subject = $conn->real_escape_string($_POST['subject']);
    $description = $conn->real_escape_string($_POST['description']);
    $priority = $conn->real_escape_string($_POST['priority']);
    $username = $_SESSION['username'];

    $sql = "INSERT INTO support_requests (username, subject, description, priority, status, created_at) 
            VALUES ('$username', '$subject', '$description', '$priority', 'Pending', NOW())";

    $message = $conn->query($sql) === TRUE
        ? "Support request sent successfully!"
        : "Error: " . $conn->error;
}

$username = $_SESSION['username'];
$result = $conn->query("SELECT * FROM support_requests WHERE username='$username' ORDER BY created_at DESC");
$adminContacts = $conn->query("SELECT full_name, email, contact_info FROM doctors WHERE role = 'admin' AND status = 'actif'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Support & Contact</title>
  <link rel="stylesheet" href="../css/support_contact.css">
</head>
<body>

<div style="margin-top: 100px;" class="container">
  <h1>Support & Contact</h1>

  <?php if (isset($message)): ?>
    <div class="message"><?php echo htmlspecialchars($message); ?></div>
  <?php endif; ?>

  <!-- Section 1 -->
  <div class="accordion-section">
    <div class="accordion-header" >
      <h2 style="font-size:25px">Send a Support Request</h2>
      <span class="arrow"></span>
    </div>
    <div class="accordion-content" style="display:block">
      <form action="" method="post">
      <label>Subject:</label>
        <select name="subject" required>
        <option disabled selected value="">-- Select a request type --</option>

        <optgroup label="Human Resources">
            <option value="Work Certificate Request">Work Certificate Request</option>
            <option value="Resignation Request">Resignation Request</option>
            <option value="Leave of Absence Request">Leave of Absence Request</option>
            <option value="Contract Renewal Request">Contract Renewal Request</option>
            <option value="Salary Certificate Request">Salary Certificate Request</option>
            <option value="Work Schedule Change">Work Schedule Change</option>
        </optgroup>

        <optgroup label="Administrative Support">
            <option value="Internship Request">Internship Request</option>
            <option value="Recommendation Letter Request">Recommendation Letter Request</option>
            <option value="Document Translation Request">Document Translation Request</option>
            <option value="General Support Request">General Support Request</option>
        </optgroup>
        </select>

        <label>Description:</label>
        <textarea name="description" ></textarea>

        <label>Priority:</label>
        <select name="priority" required>
          <option value="Low">Low</option>
          <option value="Normal" selected>Normal</option>
          <option value="Urgent">Urgent</option>
        </select>

        <button type="submit" name="submit_ticket">Submit</button>
      </form>
    </div>
  </div>

  <!-- Section 2
  <div class="accordion-section">
    <div class="accordion-header" onclick="toggleAccordion(this)">
      <h2>My Requests History</h2>
      <span class="arrow">▼</span>
    </div>
    <div class="accordion-content">
        
      <table class="requests-history">
        <thead>
          <tr>
            <th style="width: 31.76%;" >Subject</th>
            <th>Priority</th>
            <th>Status</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td style="width: 31.76%;"><?php echo htmlspecialchars($row['subject']); ?></td>
              <td><?php echo htmlspecialchars($row['priority']); ?></td>
              <td><?php echo htmlspecialchars($row['status']); ?></td>
              <td><?php echo htmlspecialchars($row['created_at']); ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div> -->

  <!-- Section 3 -->
  <div class="accordion-section">
    <div class="accordion-header" onclick="toggleAccordion(this)">
      <h2>Contact Information</h2>
      <span class="arrow">▼</span>
    </div>
    <div class="accordion-content contact-section">
        <?php if ($adminContacts->num_rows > 0): ?>
            <?php while ($admin = $adminContacts->fetch_assoc()): ?>
            <p style="color: #111;"><strong><?php echo htmlspecialchars($admin['full_name']); ?></strong><br>
            📧 <a href="mailto:<?php echo htmlspecialchars($admin['email']); ?>">
                <?php echo htmlspecialchars($admin['email']); ?>
                </a><br>
            📞 <?php echo htmlspecialchars($admin['contact_info']); ?></p>
            <hr style="border: 0; border-top: 1px solid #eee;">
            <?php endwhile; ?>
        <?php else: ?>
            <p>No admin contacts available at the moment.</p>
        <?php endif; ?>
    </div>
  </div>


  <!-- Section 4 -->
  <div class="accordion-section">
    <div class="accordion-header" onclick="toggleAccordion(this)">
      <h2>Frequently Asked Questions</h2>
      <span class="arrow">▼</span>
    </div>
    <div class="accordion-content faq-section">
      <h3>How to reset my password?</h3>
      <p>Go to your account settings and click "Change Password".</p>

      <h3>How to add a new patient?</h3>
      <p>Click "Add Patient" in the sidebar or dashboard menu.</p>

      <h3>What if I can’t log in?</h3>
      <p>Use the "Forgot Password" link or contact support by email.</p>
    </div>
  </div>
</div>

<script>
function toggleAccordion(header) {
  header.classList.toggle("active");
  const content = header.nextElementSibling;
  const arrow = header.querySelector(".arrow");

  if (content.style.display === "block") {
    content.style.display = "none";
    arrow.textContent = "▼";
  } else {
    content.style.display = "block";
    arrow.textContent = "▲";
  }
}
</script>

</body>
</html>
