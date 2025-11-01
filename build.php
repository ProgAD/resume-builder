<?php
session_start();
if(!isset($_SESSION['id']) or !isset($_SESSION['phone'])){
    header("Location: actions/auth/logout.php");
    exit();
}
if(!isset($_GET['req'])){
    header("Location: dashobard.html");
    exit();
}

$req = $_GET['req'];

if($req == 'edit'){
    if(!isset($_GET['id'])){
        header("Location: dashobard.html");
        exit();
    }
    $resume_id = $_GET['id'];
    require 'config/db.php';

    try {
        // -------------------- FETCH MAIN RESUME --------------------
        $stmt = $conn->prepare("SELECT * FROM resumes WHERE id = ?");
        $stmt->bind_param("i", $resume_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $resume = $result->fetch_assoc();

        // -------------------- FETCH EDUCATION --------------------
        $stmt = $conn->prepare("SELECT * FROM education WHERE resume_id = ?");
        $stmt->bind_param("i", $resume_id);
        $stmt->execute();
        $education = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // -------------------- FETCH SKILLS --------------------
        $stmt = $conn->prepare("SELECT * FROM skills WHERE resume_id = ?");
        $stmt->bind_param("i", $resume_id);
        $stmt->execute();
        $skills = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // -------------------- FETCH COMPETENCIES --------------------
        $stmt = $conn->prepare("SELECT * FROM competencies WHERE resume_id = ?");
        $stmt->bind_param("i", $resume_id);
        $stmt->execute();
        $competencies = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // -------------------- FETCH EXPERIENCE --------------------
        $stmt = $conn->prepare("SELECT * FROM work_experience WHERE resume_id = ?");
        $stmt->bind_param("i", $resume_id);
        $stmt->execute();
        $experience = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $full_name = $resume['full_name']?? '';
        $photo_path = $resume['profile_photo']?? '';
        $phone = $resume['phone']?? '';
        $email = $resume['email']?? '';
        $address = $resume['address']?? '';
        $objective = $resume['objective']?? '';
        $father_name = $resume['father_name']?? '';
        $dob = $resume['date_of_birth']?? '';
        $gender = $resume['gender']?? '';
        $marital_status = $resume['marital_status']?? '';
        $nationality = $resume['nationality']?? '';
        $languages_known = $resume['languages_known']?? '';
        $strengths = $resume['strengths']?? '';
        $hobbies = $resume['hobbies']?? '';
        $declaration_text = $resume['declaration_text']?? '';
        $declaration_city = $resume['declaration_city']?? '';
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Resume - Resume Builder</title>
    <link rel="stylesheet" href="css/build.css">
</head>
<body>
    <nav class="navbar">
        <div class="container navbar-content">
            <a href="#" class="navbar-brand">
                <span>📄</span>
                Resume Builder
            </a>
            <button type="button" class="btn btn-secondary back-btn" onclick="window.location.href = 'dashboard.php'">
                ← <span>Back to Dashboard</span>
            </button>
        </div>
    </nav>

    <div class="container">
        <form id="resumeForm" method="POST" action="<?php echo $req == 'new' ? 'actions/resume/save_resume.php' : 'actions/resume/update_resume.php'; ?>" enctype="multipart/form-data">
        <?php if($req == 'edit'){ echo '<input type="hidden" name="resumeId" value="'.$resume_id.'">'; } ?>
        <div class="page-header">
            <h1><?php echo $req == 'new' ? 'Create New Resume' : 'Edit Resume'; ?></h1>
            <p>Fill in the details below to create your professional resume</p>
        </div>

        <div class="card photo-section">
            <div class="photo-container">
                <div class="photo-preview" id="photoPreview">
                    <?php if($photo_path == ""){ echo '<span id="photoInitials">?</span>'; } ?>
                    <img id="photoImage" src="<?php echo htmlspecialchars($photo_path); ?>" <?php if($req == 'new'){ echo 'style="display: none;"'; } ?> >
                </div>
                <label class="photo-upload-btn">
                    📷 Upload Photo
                    <input type="file" name="profilePhoto" accept="image/*" onchange="handlePhotoUpload(event)">
                </label>
            </div>
        </div>

        <div class="card form-section">
            <div class="section-header">
                <h3>👤 Personal Information</h3>
            </div>
            <div class="section-content">
                <div class="form-group">
                    <label for="fullName">Full Name *</label>
                    <input type="text" id="fullName" name="fullName" value="<?php echo htmlspecialchars($full_name); ?>" placeholder="Enter your full name" oninput="updatePhotoInitials()" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>" placeholder="Enter your address" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Contact Number</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" placeholder="Enter contact number" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Enter email address" required>
                    </div>
                </div>
            </div>
        </div>

        <div id="sectionsContainer">
            <div class="card form-section" id="objectiveSection" data-section-name="Objective" data-section-icon="🎯">
                <div class="section-header collapsed" onclick="toggleSection('objectiveContent')">
                    <h3>🎯 Objective <span class="collapse-icon">▼</span></h3>
                    <!-- <div class="section-actions" onclick="event.stopPropagation()">
                        <button type="button" class="delete-section-btn" onclick="deleteSection('objectiveSection')">🗑️ Delete</button>
                    </div> -->
                </div>
                <div class="section-content hidden" id="objectiveContent">
                    <!-- <div class="objective-options">
                        <div class="objective-option" onclick="selectObjective(0)">
                            To secure a challenging position where I can utilize my skills and contribute to organizational growth.
                        </div>
                        <div class="objective-option" onclick="selectObjective(1)">
                            Seeking an opportunity to leverage my expertise in a dynamic environment and achieve professional excellence.
                        </div>
                        <div class="objective-option" onclick="selectObjective(2)">
                            To obtain a position that allows me to grow professionally while contributing to the success of the organization.
                        </div>
                    </div> -->
                    <div class="form-group">
                        <!-- <label>Custom Objective</label> -->
                        <textarea 
                        id="objectiveText" 
                        name="objective" 
                        placeholder="Or write your own objective..." 
                        required
                        ><?php if (!empty($objective)): ?><?php echo htmlspecialchars($objective); ?><?php else: ?>I am a motivated and quick learner seeking an entry-level position in a dynamic organization where I can apply my academic knowledge and develop new skills. My goal is to contribute effectively to the company's success while gaining practical experience and growing professionally. I am eager to work collaboratively with a team that values innovation, creativity, and dedication.<?php endif; ?>
                    </textarea>

                    </div>
                </div>
            </div>

            <div class="card form-section" id="educationSection" data-section-name="Educational Qualification" data-section-icon="🎓">
                <div class="section-header collapsed" onclick="toggleSection('educationContent')">
                    <h3>🎓 Educational Qualification <span class="collapse-icon">▼</span></h3>
                    <div class="section-actions" onclick="event.stopPropagation()">
                        <button type="button" class="delete-section-btn" onclick="deleteSection('educationSection')">🗑️ Delete</button>
                    </div>
                </div>
                <div class="section-content hidden" id="educationContent">
                    <div id="educationRows">
                        <?php if ($req == 'new'): ?>
                        <div class="dynamic-section">
                            <button type="button" class="remove-row-btn" onclick="removeRow(this)">✕ Remove</button>
                            <div class="form-group">
                                <label>Degree/Qualification</label>
                                <input type="text" name="educationQualification[]" placeholder="e.g., B.Tech in Computer Science" required>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Institution</label>
                                    <input type="text" name="educationInstitution[]" placeholder="Enter institution name" required>
                                </div>
                                <div class="form-group">
                                    <label>Year</label>
                                    <input type="text" name="educationYear[]" placeholder="e.g., 2020-2024" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="educationStatus[]" required onchange="educationStatusChange(this);">
                                        <option value="" selected>Select Status</option>
                                        <option value="completed">Completed</option>
                                        <option value="pursuing">Pursuing</option>
                                    </select>
                                </div>
                                <div class="form-group" id="educationResultB" style="display: none;">
                                    <label>Result</label>
                                    <input type="text" name="educationResult[]" placeholder="e.g., 8.5 CGPA or 85%">
                                </div>
                            </div>
                        </div>
                        <?php elseif ($req == 'edit'): ?>
                            <?php foreach ($education as $index => $edu): ?>
                                <div class="dynamic-section">
                                    <button type="button" class="remove-row-btn" onclick="removeRow(this)">✕ Remove</button>
                                    <div class="form-group">
                                        <label>Degree/Qualification</label>
                                        <input type="text" name="educationQualification[]" value="<?php echo htmlspecialchars($edu['qualification']); ?>" required>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label>Institution</label>
                                            <input type="text" name="educationInstitution[]" value="<?php echo htmlspecialchars($edu['institution']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Year</label>
                                            <input type="text" name="educationYear[]" value="<?php echo htmlspecialchars($edu['year']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select name="educationStatus[]" required onchange="educationStatusChange(this);">
                                                <option value="" selected>Select Status</option>
                                                <option value="completed" <?php echo $edu['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                                <option value="pursuing" <?php echo $edu['status'] == 'pursuing' ? 'selected' : ''; ?>>Pursuing</option>
                                            </select>
                                        </div>
                                        <div class="form-group" id="educationResultB" style="display: <?php echo $edu['status'] == 'completed' ? 'block' : 'none'; ?>;">
                                            <label>Result</label>
                                            <input type="text" name="educationResult[]" value="<?php echo htmlspecialchars($edu['result']); ?>" placeholder="e.g., 8.5 CGPA or 85%">
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="add-more-btn" onclick="addEducationRow()">+ Add More Education</button>
                </div>
            </div>

            <div class="card form-section" id="workSection" data-section-name="Work Experience" data-section-icon="💼">
                <div class="section-header collapsed" onclick="toggleSection('workContent')">
                    <h3>💼 Work Experience <span class="collapse-icon">▼</span></h3>
                    <div class="section-actions" onclick="event.stopPropagation()">
                        <button type="button" class="delete-section-btn" onclick="deleteSection('workSection')">🗑️ Delete</button>
                    </div>
                </div>
                <?php if ($req == 'new'): ?>
                <div class="section-content hidden" id="workContent">
                    <div id="workRows">
                        <div class="dynamic-section">
                            <button type="button" class="remove-row-btn" onclick="removeRow(this)">✕ Remove</button>
                            <div class="form-group">
                                <!-- <label>Work Experience</label> -->
                                <input type="text" name="workExperience[]" placeholder="e.g., 6 months experience as Executive in ABC Company">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="add-more-btn" onclick="addWorkRow()">+ Add More Experience</button>
                </div>
                <?php elseif ($req == 'edit'): ?>
                <div class="section-content hidden" id="workContent">
                    <div id="workRows">
                        <?php foreach ($experience as  $work): ?>
                        <div class="dynamic-section">
                            <button type="button" class="remove-row-btn" onclick="removeRow(this)">✕ Remove</button>
                            <div class="form-group">
                                <!-- <label>Work Experience</label> -->
                                <input type="text" name="workExperience[]" value="<?php echo htmlspecialchars($work['description']); ?>" placeholder="e.g., 6 months experience as Executive in ABC Company">
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="add-more-btn" onclick="addWorkRow()">+ Add More Experience</button>
                </div>
                <?php endif; ?>
            </div>

            <div class="card form-section" id="skillsSection" data-section-name="Key Skills" data-section-icon="⚡">
                <div class="section-header collapsed" onclick="toggleSection('skillsContent')">
                    <h3>⚡ Key Skills <span class="collapse-icon">▼</span></h3>
                    <div class="section-actions" onclick="event.stopPropagation()">
                        <button type="button" class="delete-section-btn" onclick="deleteSection('skillsSection')">🗑️ Delete</button>
                    </div>
                </div>
                <div class="section-content hidden" id="skillsContent">
                    <div class="skill-input-group">
                        <input type="text" id="skillInput" placeholder="Enter a skill">
                        <button type="button" class="btn btn-primary" onclick="addSkill()">Add Skill</button>
                    </div>
                    <div class="skills-container" id="skillsContainer">
                        <?php if ($req == 'edit'): ?>
                            <?php foreach ($skills as $skill): ?>
                                <div class="skill-tag">
                                    <?php echo htmlspecialchars($skill['skill_name']); ?>
                                    <span class="remove-skill" onclick="removeTag(this)">
                                        <input type="hidden" name="keySkill[]" value="<?php echo htmlspecialchars($skill['skill_name']); ?>">
                                        x
                                    <span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="card form-section" id="competenciesSection" data-section-name="Key Competencies" data-section-icon="🏆">
                <div class="section-header collapsed" onclick="toggleSection('competenciesContent')">
                    <h3>🏆 Key Competencies <span class="collapse-icon">▼</span></h3>
                    <div class="section-actions" onclick="event.stopPropagation()">
                        <button type="button" class="delete-section-btn" onclick="deleteSection('competenciesSection')">🗑️ Delete</button>
                    </div>
                </div>
                <div class="section-content hidden" id="competenciesContent">
                    <div class="skill-input-group">
                        <input type="text" id="competencyInput" placeholder="Enter a competency">
                        <button type="button" class="btn btn-primary" onclick="addCompetency()">Add Competency</button>
                    </div>
                    <div class="skills-container" id="competenciesContainer">
                        <?php if ($req == 'edit'): ?>
                            <?php foreach ($competencies as $competency): ?>
                                <div class="skill-tag">
                                    <?php echo htmlspecialchars($competency['competency_name']); ?>
                                    <span class="remove-skill" onclick="removeTag(this)">
                                        <input type="hidden" name="keyCompetency[]" value="<?php echo htmlspecialchars($competency['competency_name']); ?>">
                                        x
                                    <span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card form-section" id="personalSection" data-section-name="Personal Details" data-section-icon="📋">
                <div class="section-header collapsed" onclick="toggleSection('personalContent')">
                    <h3>📋 Personal Details <span class="collapse-icon">▼</span></h3>
                    <!-- <div class="section-actions" onclick="event.stopPropagation()">
                        <button type="button" class="delete-section-btn" onclick="deleteSection('personalSection')">🗑️ Delete</button>
                    </div> -->
                </div>
                <div class="section-content hidden" id="personalContent">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Father's Name</label>
                            <input type="text" name="fatherName" value="<?php echo htmlspecialchars($resume['father_name']); ?>" placeholder="Enter father's name">
                        </div>
                        <div class="form-group">
                            <label>Date of Birth</label>
                            <input type="date" value="<?php echo htmlspecialchars($resume['date_of_birth']); ?>" name="dateOfBirth">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender">
                                <option value="">Select Gender</option>
                                <option <?php echo $resume['gender'] == 'male' ? 'selected' : ''; ?> value="male">Male</option>
                                <option <?php echo $resume['gender'] == 'female' ? 'selected' : ''; ?> value="female">Female</option>
                                <option <?php echo $resume['gender'] == 'other' ? 'selected' : ''; ?> value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Marital Status</label>
                            <select name="maritalStatus">
                                <option value="">Select Status</option>
                                <option <?php echo $resume['marital_status'] == 'single' ? 'selected' : ''; ?> value="single">Single</option>
                                <option <?php echo $resume['marital_status'] == 'married' ? 'selected' : ''; ?> value="married">Married</option>
                                <option <?php echo $resume['marital_status'] == 'other' ? 'selected' : ''; ?> value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nationality</label>
                            <input type="text" name="nationality" value="<?php echo htmlspecialchars($resume['nationality']); ?>" placeholder="Enter nationality">
                        </div>
                        <div class="form-group">
                            <label>Languages Known</label>
                            <input type="text" name="languagesKnown" value="<?php echo htmlspecialchars($resume['languages_known']); ?>" placeholder="e.g., English, Hindi">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Strength</label>
                        <textarea name="strengths" value="<?php echo htmlspecialchars($resume['strengths']); ?>" placeholder="Describe your strengths..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Hobbies</label>
                        <input type="text" name="hobbies" value="<?php echo htmlspecialchars($resume['hobbies']); ?>" placeholder="e.g., Reading, Traveling, Music">
                    </div>
                </div>
            </div>

            <div class="card form-section" id="declarationSection" data-section-name="Declaration" data-section-icon="✍️">
                <div class="section-header collapsed" onclick="toggleSection('declarationContent')">
                    <h3>✍️ Declaration <span class="collapse-icon">▼</span></h3>
                    <!-- <div class="section-actions" onclick="event.stopPropagation()">
                        <button type="button" class="delete-section-btn" onclick="deleteSection('declarationSection')">🗑️ Delete</button>
                    </div> -->
                </div>
                <div class="section-content hidden" id="declarationContent">
                    <div class="form-group">
                        <label>Declaration Text</label>
                        <textarea name="declarationText" id="declarationText"><?php echo htmlspecialchars($resume['declaration_text'] ?? 'I hereby declare that the information provided above is true and correct to the best of my knowledge and belief.'); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city" value="<?php echo htmlspecialchars($resume['declaration_city']); ?>" placeholder="Enter city name" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="handleCancel()">Cancel</button>
            <button type="button" class="btn btn-primary" onclick="handlePreview()">👁️ Preview Resume</button>
            <button type="submit" class="btn btn-primary">💾 Save Resume</button>
        </div>
        </form>
    </div>

    <script>
        // Prevent Enter key from submitting the form (except for skill/competency inputs)
        // Handle Enter key: move to next field instead of submitting form
        document.getElementById('resumeForm').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                // Allow Enter for textareas (for line breaks)
                if (e.target.tagName === 'TEXTAREA') {
                    return;
                }
                
                // For skill and competency inputs, trigger their add functions
                if (e.target.id === 'skillInput') {
                    e.preventDefault();
                    addSkill();
                    return false;
                }
                
                if (e.target.id === 'competencyInput') {
                    e.preventDefault();
                    addCompetency();
                    return false;
                }
                
                // For all other inputs, move to next input field
                if (e.target.tagName === 'INPUT' || e.target.tagName === 'SELECT') {
                    e.preventDefault();
                    
                    // Get all focusable elements in the form
                    const focusableElements = Array.from(
                        document.querySelectorAll('#resumeForm input:not([type="file"]), #resumeForm select, #resumeForm textarea')
                    ).filter(el => {
                        // Only include visible elements
                        return el.offsetParent !== null && !el.disabled;
                    });
                    
                    // Find current element index
                    const currentIndex = focusableElements.indexOf(e.target);
                    
                    // Move to next element
                    if (currentIndex > -1 && currentIndex < focusableElements.length - 1) {
                        focusableElements[currentIndex + 1].focus();
                    }
                    
                    return false;
                }
            }
        });

        const deletedSections = {};

        function handlePhotoUpload(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('photoImage').src = e.target.result;
                    document.getElementById('photoImage').style.display = 'block';
                    document.getElementById('photoInitials').style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        }

        function updatePhotoInitials() {
            const name = document.getElementById('fullName').value.trim();
            const initialsElement = document.getElementById('photoInitials');
            const imageElement = document.getElementById('photoImage');
            
            if (imageElement.style.display === 'none') {
                if (name) {
                    const parts = name.split(' ');
                    const initials = parts.length > 1 
                        ? parts[0][0] + parts[parts.length - 1][0]
                        : parts[0][0];
                    initialsElement.textContent = initials.toUpperCase();
                } else {
                    initialsElement.textContent = '?';
                }
            }
        }

        function toggleSection(contentId) {
            const content = document.getElementById(contentId);
            const header = content.previousElementSibling;
            content.classList.toggle('hidden');
            header.classList.toggle('collapsed');
        }

        function deleteSection(sectionId) {
            if (confirm('Are you sure you want to delete this section?')) {
                const section = document.getElementById(sectionId);
                const sectionName = section.dataset.sectionName;
                const sectionIcon = section.dataset.sectionIcon;
                
                deletedSections[sectionId] = section.outerHTML;
                section.classList.add('deleting');
                
                setTimeout(() => {
                    section.classList.remove('deleting');
                    section.classList.add('section-hidden');
                    
                    const placeholder = document.createElement('div');
                    placeholder.className = 'add-section-placeholder';
                    placeholder.id = `placeholder-${sectionId}`;
                    placeholder.innerHTML = `
                        <p style="color: var(--text-secondary); margin-bottom: 15px; font-size: 0.95rem;">
                            Section "${sectionName}" has been removed
                        </p>
                        <button type="button" class="restore-section-btn" onclick="restoreSection('${sectionId}')">
                            ${sectionIcon} Add ${sectionName} Section
                        </button>
                    `;
                    
                    section.parentNode.insertBefore(placeholder, section.nextSibling);
                }, 400);
            }
        }

        function restoreSection(sectionId) {
            const section = document.getElementById(sectionId);
            const placeholder = document.getElementById(`placeholder-${sectionId}`);
            
            if (placeholder) {
                placeholder.style.animation = 'slideOut 0.4s ease forwards';
                
                setTimeout(() => {
                    placeholder.remove();
                    section.classList.remove('section-hidden');
                    section.style.animation = 'slideIn 0.4s ease forwards';
                    
                    setTimeout(() => {
                        section.style.animation = '';
                    }, 400);
                }, 400);
            }
        }

        // let selectedObjectiveIndex = -1;
        // function selectObjective(index) {
        //     const options = document.querySelectorAll('.objective-option');
        //     options.forEach((opt, i) => {
        //         opt.classList.toggle('selected', i === index);
        //     });
        //     selectedObjectiveIndex = index;
        //     document.getElementById('objectiveText').value = options[index].textContent.trim();
        // }

        function addEducationRow() {
            const container = document.getElementById('educationRows');
            const newRow = document.createElement('div');
            newRow.className = 'dynamic-section';
            newRow.innerHTML = `
                <button type="button" class="remove-row-btn" onclick="removeRow(this)">✕ Remove</button>
                <div class="form-group">
                    <label>Degree/Qualification</label>
                    <input type="text" name="educationQualification[]" placeholder="e.g., B.Tech in Computer Science" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Institution</label>
                        <input type="text" name="educationInstitution[]" placeholder="Enter institution name" required>
                    </div>
                    <div class="form-group">
                        <label>Year</label>
                        <input type="text" name="educationYear[]" placeholder="e.g., 2020-2024" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="educationStatus[]" required onchange="educationStatusChange(this);">
                            <option value="" selected>Select Status</option>
                            <option value="completed">Completed</option>
                            <option value="pursuing">Pursuing</option>
                        </select>
                    </div>
                    <div class="form-group" id="educationResultB" style="display: none;">
                        <label>Result</label>
                        <input type="text" name="educationResult[]" placeholder="e.g., 8.5 CGPA or 85%">
                    </div>
                </div>
            `;
            container.appendChild(newRow);
        }

        function addWorkRow() {
            const container = document.getElementById('workRows');
            const newRow = document.createElement('div');
            newRow.className = 'dynamic-section';
            newRow.innerHTML = `
                <button type="button" class="remove-row-btn" onclick="removeRow(this)">✕ Remove</button>
                <div class="form-group">
                    <!-- <label>Work Experience</label> -->
                    <input type="text" name="workExperience[]" placeholder="e.g., 6 months experience as Executive in ABC Company">
                </div>
            `;
            container.appendChild(newRow);
        }

        function removeRow(button) {
            const row = button.parentElement;
            const container = row.parentElement;
            if (container.children.length > 1) {
                row.remove();
            } else {
                alert('At least one entry is required!');
            }
        }

        function addSkill() {
            const input = document.getElementById('skillInput');
            const skill = input.value.trim();
            if (skill) {
                const container = document.getElementById('skillsContainer');
                const tag = document.createElement('div');
                tag.className = 'skill-tag';
                tag.innerHTML = `${skill} <span class="remove-skill" onclick="removeTag(this)"><input type="hidden" name="keySkill[]" value="${skill}">✕</span>`;
                container.appendChild(tag);
                input.value = '';


                // const newSkill = document.createElement('input');
                // newSkill.type = 'hidden';
                // newSkill.name = 'keySkill[]';
                // newSkill.value = skill;
                // newSkill.style.display = 'none';
                // document.getElementById('skillsForm').appendChild(newSkill);
            }
        }

        function addCompetency() {
            const input = document.getElementById('competencyInput');
            const competency = input.value.trim();
            if (competency) {
                const container = document.getElementById('competenciesContainer');
                const tag = document.createElement('div');
                tag.className = 'skill-tag';
                tag.innerHTML = `${competency} <span class="remove-skill" onclick="removeTag(this)"><input type="hidden" name="keyCompetency[]" value="${competency}">✕</span>`;
                container.appendChild(tag);
                input.value = '';

                // const newCompetency = document.createElement('input');
                // newCompetency.type = 'hidden';
                // newCompetency.name = 'keyCompetency[]';
                // newCompetency.value = competency;
                // newCompetency.style.display = 'none';
                // document.getElementById('competenciesForm').appendChild(newCompetency);
            }
        }

        function removeTag(span) {
            span.parentElement.remove();
        }

        document.getElementById('skillInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addSkill();
            }
        });

        document.getElementById('competencyInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addCompetency();
            }
        });


        function handlePreview() {
            const name = document.getElementById('fullName').value.trim();
            if (!name) {
                alert('Please enter your full name before previewing!');
                return;
            }
            alert(`👁️ Opening preview for ${name}'s resume...\n\nIn a real application, this would open a preview page showing how the resume looks.`);
        }

        function handleCancel() {
            if (confirm('Are you sure you want to cancel? All unsaved changes will be lost.')) {
                alert('Redirecting to Dashboard...');
            }
        }

        // function handleBack() {
        //     if (confirm('Are you sure you want to go back? Any unsaved changes will be lost.')) {
        //         alert('Redirecting to Dashboard...');
        //     }
        // }
        function educationStatusChange(element) {
            const closestSection = element.closest('.dynamic-section');
            const status = element.value;
            const resultB = closestSection.querySelector('#educationResultB');
            if (status === 'completed') {
                resultB.style.display = 'block';
            } else {
                resultB.style.display = 'none';
            }
        }
    </script>
</body>
</html>