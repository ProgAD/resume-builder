<?php
session_start();
require '../../config/db.php'; // adjust path if needed

header('Content-Type: application/json');

// Ensure user is logged in
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

$user_id = $_SESSION['id'];
$resume_id = $_POST['resumeId'] ?? null;

if (!$resume_id) {
    echo json_encode(['success' => false, 'message' => 'Missing resume ID.']);
    exit;
}

// Collect form data
$full_name = $_POST['fullName'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$address = $_POST['address'] ?? '';

$objective = $_POST['objective'] ?? '';

$father_name = $_POST['fatherName'] ?? '';
$dob = $_POST['dateOfBirth'] ?? null;
$gender = $_POST['gender'] ?? '';
$marital_status = $_POST['maritalStatus'] ?? '';
$nationality = $_POST['nationality'] ?? '';
$languages_known = $_POST['languagesKnown'] ?? '';
$strengths = $_POST['strengths'] ?? '';
$hobbies = $_POST['hobbies'] ?? '';

$declaration_text = $_POST['declarationText'] ?? 'I hereby declare that the above information is true to the best of my knowledge.';
$declaration_city = $_POST['city'] ?? '';

// Rebuild education array
$education_data = [];
$quals   = $_POST['educationQualification'] ?? [];
$insts   = $_POST['educationInstitution']   ?? [];
$years   = $_POST['educationYear']          ?? [];
$statuss = $_POST['educationStatus']        ?? [];
$results = $_POST['educationResult']        ?? [];

$count = max(count($quals), count($insts), count($years), count($statuss));
for ($i = 0; $i < $count; $i++) {
    $education_data[] = [
        'qualification' => $quals[$i]   ?? '',
        'institution'   => $insts[$i]   ?? '',
        'year'          => $years[$i]   ?? '',
        'status'        => $statuss[$i] ?? '',
        'result'        => $results[$i] ?? ''
    ];
}

$work_experience_data = $_POST['workExperience'] ?? [];
$skills_data = $_POST['keySkill'] ?? [];
$competencies_data = $_POST['keyCompetency'] ?? [];

// Handle photo upload
$photo_path = null;
if (isset($_FILES['profilePhoto']) && $_FILES['profilePhoto']['error'] === UPLOAD_ERR_OK) {
    $targetDir = "../../uploads/resume_photos/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    $fileName = time() . "_" . basename($_FILES['profilePhoto']['name']);
    $targetFile = $targetDir . $fileName;

    if (move_uploaded_file($_FILES['profilePhoto']['tmp_name'], $targetFile)) {
        $photo_path = "uploads/resume_photos/" . $fileName;
    }
}

// Build update query
$query = "UPDATE resumes SET 
    full_name=?, address=?, phone=?, email=?, 
    objective=?, father_name=?, date_of_birth=?, gender=?, marital_status=?, nationality=?, 
    languages_known=?, strengths=?, hobbies=?, 
    declaration_text=?, declaration_city=?, 
    updated_at=CURRENT_TIMESTAMP" . 
    ($photo_path ? ", profile_photo=?" : "") . 
    " WHERE id=? AND user_id=?";

if ($photo_path) {
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "sssssssssssssssssii",
        $full_name, $address, $phone, $email,
        $objective, $father_name, $dob, $gender, $marital_status, $nationality,
        $languages_known, $strengths, $hobbies,
        $declaration_text, $declaration_city,
        $photo_path, $resume_id, $user_id
    );
} else {
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "sssssssssssssssii",
        $full_name, $address, $phone, $email,
        $objective, $father_name, $dob, $gender, $marital_status, $nationality,
        $languages_known, $strengths, $hobbies,
        $declaration_text, $declaration_city,
        $resume_id, $user_id
    );
}

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Failed to update resume.', 'error' => $stmt->error]);
    exit;
}
$stmt->close();

// ---------- Delete existing child data ----------
$tables = ['education', 'work_experience', 'skills', 'competencies'];
foreach ($tables as $table) {
    $del_stmt = $conn->prepare("DELETE FROM $table WHERE resume_id = ?");
    $del_stmt->bind_param("i", $resume_id);
    $del_stmt->execute();
    $del_stmt->close();
}

// ---------- Re-insert updated data ----------

// Education
if (!empty($education_data)) {
    $edu_stmt = $conn->prepare("INSERT INTO education (resume_id, qualification, institution, year, status, result) VALUES (?,?,?,?,?,?)");
    foreach ($education_data as $edu) {
        $edu_stmt->bind_param("isssss", $resume_id, $edu['qualification'], $edu['institution'], $edu['year'], $edu['status'], $edu['result']);
        $edu_stmt->execute();
    }
    $edu_stmt->close();
}

// Work Experience
if (!empty($work_experience_data)) {
    $exp_stmt = $conn->prepare("INSERT INTO work_experience (resume_id, description) VALUES (?,?)");
    foreach ($work_experience_data as $exp) {
        $desc = is_array($exp) ? ($exp['description'] ?? '') : $exp;
        $exp_stmt->bind_param("is", $resume_id, $desc);
        $exp_stmt->execute();
    }
    $exp_stmt->close();
}

// Skills
if (!empty($skills_data)) {
    $skill_stmt = $conn->prepare("INSERT INTO skills (resume_id, skill_name) VALUES (?,?)");
    foreach ($skills_data as $skill) {
        $sname = is_array($skill) ? ($skill['skill_name'] ?? '') : $skill;
        $skill_stmt->bind_param("is", $resume_id, $sname);
        $skill_stmt->execute();
    }
    $skill_stmt->close();
}

// Competencies
if (!empty($competencies_data)) {
    $comp_stmt = $conn->prepare("INSERT INTO competencies (resume_id, competency_name) VALUES (?,?)");
    foreach ($competencies_data as $comp) {
        $cname = is_array($comp) ? ($comp['competency_name'] ?? '') : $comp;
        $comp_stmt->bind_param("is", $resume_id, $cname);
        $comp_stmt->execute();
    }
    $comp_stmt->close();
}

header("Location: ../../resume.php?id=$resume_id&updated=1");
$conn->close();
exit;
?>
