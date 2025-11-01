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

// Collect form data
$full_name = $_POST['fullName'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$address = $_POST['address'] ?? '';

$objective = $_POST['objective'] ?? '';

$father_name = $_POST['fatherName'] ?? '';
$dob = $_POST['dateOfBirth'] ?? null;
$gender = $_POST['gender'] ?? '';
$marital_status = $_POST['maritalStatus'];
$nationality = $_POST['nationality'] ?? '';
$languages_known = $_POST['languagesKnown'] ?? '';
$strengths = $_POST['strengths'] ?? '';
$hobbies = $_POST['hobbies'] ?? '';

$declaration_text = $_POST['declarationText'] ?? 'I hereby declare that the above information is true to the best of my knowledge.';
$declaration_city = $_POST['city'] ?? '';

// Rebuild education array from individual field arrays
$education_data = [];
$quals   = $_POST['educationQualification'] ?? [];
$insts   = $_POST['educationInstitution']   ?? [];
$years   = $_POST['educationYear']         ?? [];
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

// Handle photo upload if available
$photo_path = null;
if (isset($_FILES['profilePhoto']) && $_FILES['profilePhoto']['error'] === UPLOAD_ERR_OK) {
    $targetDir = "../../uploads/resume_photos/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName = time() . "_" . basename($_FILES['profilePhoto']['name']);
    $targetFile = $targetDir . $fileName;

    if (move_uploaded_file($_FILES['profilePhoto']['tmp_name'], $targetFile)) {
        $photo_path = "uploads/resume_photos/" . $fileName;
    }
}

// Insert into resumes
$query = "INSERT INTO resumes (
    user_id, full_name, address, phone, email, profile_photo, objective,
    father_name, date_of_birth, gender, marital_status, nationality,
    languages_known, strengths, hobbies, declaration_text, declaration_city
) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

$stmt = $conn->prepare($query);
$stmt->bind_param(
    "issssssssssssssss",
    $user_id, $full_name, $address, $phone, $email, $photo_path, $objective,
    $father_name, $dob, $gender, $marital_status, $nationality,
    $languages_known, $strengths, $hobbies, $declaration_text, $declaration_city
);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Failed to save resume.', 'error' => $stmt->error]);
    exit;
}

$resume_id = $conn->insert_id;
$stmt->close();


// -------- Insert Education --------
if (!empty($education_data) && is_array($education_data)) {
    $edu_stmt = $conn->prepare("INSERT INTO education (resume_id, qualification, institution, year, status, result) VALUES (?,?,?,?,?,?)");
    foreach ($education_data as $edu) {
        $qualification = $edu['qualification'] ?? '';
        $institution = $edu['institution'] ?? '';
        $year = $edu['year'] ?? '';
        $status = $edu['status'] ?? 'completed';
        $result = $edu['result'] ?? '';
        $edu_stmt->bind_param("isssss", $resume_id, $qualification, $institution, $year, $status, $result);
        $edu_stmt->execute();
    }
    $edu_stmt->close();
}

// -------- Insert Work Experience --------
if (!empty($work_experience_data) && is_array($work_experience_data)) {
    $exp_stmt = $conn->prepare("INSERT INTO work_experience (resume_id, description) VALUES (?,?)");
    foreach ($work_experience_data as $exp) {
        $desc = is_array($exp) ? ($exp['description'] ?? '') : $exp;
        $exp_stmt->bind_param("is", $resume_id, $desc);
        $exp_stmt->execute();
    }
    $exp_stmt->close();
}

// -------- Insert Skills --------
if (!empty($skills_data) && is_array($skills_data)) {
    $skill_stmt = $conn->prepare("INSERT INTO skills (resume_id, skill_name) VALUES (?,?)");
    foreach ($skills_data as $skill) {
        $sname = is_array($skill) ? ($skill['skill_name'] ?? '') : $skill;
        $skill_stmt->bind_param("is", $resume_id, $sname);
        $skill_stmt->execute();
    }
    $skill_stmt->close();
}

// -------- Insert Competencies --------
if (!empty($competencies_data) && is_array($competencies_data)) {
    $comp_stmt = $conn->prepare("INSERT INTO competencies (resume_id, competency_name) VALUES (?,?)");
    foreach ($competencies_data as $comp) {
        $cname = is_array($comp) ? ($comp['competency_name'] ?? '') : $comp;
        $comp_stmt->bind_param("is", $resume_id, $cname);
        $comp_stmt->execute();
    }
    $comp_stmt->close();
}

header("Location: ../../resume.php?id=$resume_id");
// echo json_encode(['success' => true, 'message' => 'Resume created successfully.', 'resume_id' => $resume_id]);

$conn->close();
?>
