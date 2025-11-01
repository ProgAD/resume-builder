<?php
session_start();
if(!isset($_SESSION['id']) or !isset($_SESSION['phone'])){
    header("Location: actions/auth/logout.php");
    exit();
}

require 'config/db.php';
$user_id = $_SESSION['id'];
$sql = "SELECT * FROM resumes WHERE user_id = $user_id AND type = 1";
$result = mysqli_query($conn, $sql);
$my_resume = mysqli_fetch_assoc($result);

$sql2 = "SELECT * FROM resumes WHERE user_id = $user_id AND type = 0";
$result2 = mysqli_query($conn, $sql2);
$other_resumes = mysqli_fetch_all($result2, MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Resume Builder</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container navbar-content">
            <a href="#" class="navbar-brand">
                <span>📄</span>
                Resume Builder
            </a>
            <div class="navbar-user">
                <?php if (!empty($my_resume['profile_photo'])): ?>
                <div class="user-avatar">
                <?php echo strtoupper(substr($my_resume['full_name'], 0, 2)); ?>
                </div>
                <?php endif; ?>
                <button class="btn btn-secondary btn-sm logout-btn" onclick="handleLogout()">Logout</button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <!-- My Resume Section -->
        <?php if (!empty($my_resume)): ?>
        <div class="my-resume-section">
            <h2 class="section-title">My Resume</h2>
            <div class="card my-resume-card">
                <div class="my-resume-content">
                    <div class="my-resume-avatar">
                        <?php if (!empty($my_resume['profile_photo'])): ?>
                        <img src="<?php echo $my_resume['profile_photo']; ?>">
                        <?php else: ?>
                        <span><?php echo strtoupper(substr($my_resume['full_name'], 0, 2)); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="my-resume-info">
                        <div class="my-resume-name"><?php echo $my_resume['full_name']; ?></div>
                        <!-- <div class="my-resume-status"><?php echo $my_resume['job_title']; ?></div> -->
                        <div class="my-resume-actions">
                            <button class="btn btn-sm" onclick="handleDownload('<?php echo $my_resume['full_name']; ?>')">📥 Download</button>
                            <button class="btn btn-sm" onclick="handleShare('<?php echo $my_resume['full_name']; ?>')">🔗 Share</button>
                            <button class="btn btn-sm" onclick="showEditModal('<?php echo $my_resume['full_name']; ?>')">✏️ Edit</button>
                            <button class="btn btn-sm" onclick="handleView('<?php echo $my_resume['full_name']; ?>')">👁️ View</button>
                            <button class="btn btn-sm" onclick="showDeleteModal('<?php echo $my_resume['full_name']; ?>')">🗑️ Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Others Section -->
        <div class="others-section">
            <h2 class="section-title">Others</h2>
            <div class="grid grid-cols-4">
                <!-- Resume Card 1 -->
                <?php if (!empty($other_resumes)): ?>
                <?php foreach ($other_resumes as $resume): ?>
                <div class="card resume-card" id="resume-<?php echo $resume['id']; ?>">
                    <div class="resume-avatar">
                        <?php if (!empty($resume['profile_photo'])): ?>
                        <img src="<?php echo $resume['profile_photo']; ?>">
                        <?php else: ?>
                        <span><?php echo strtoupper(substr($resume['full_name'], 0, 2)); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="resume-name"><?php echo $resume['full_name']; ?></div>
                    <!-- <div class="resume-status"><?php echo $resume['job_title']; ?></div> -->
                    <div class="resume-actions">
                        <button class="btn btn-primary btn-sm" onclick="showSetMyResumeModal('<?php echo $resume['full_name']; ?>','<?php echo $resume['id']; ?>')">✓ Set as My Resume</button>
                    </div>
                    <div class="icon-actions">
                        <button class="icon-btn icon-btn-share" onclick="handleShare('<?php echo $resume['id']; ?>')" title="Share">🔗</button>
                        <button class="icon-btn icon-btn-edit" onclick="showEditModal('<?php echo $resume['full_name']; ?>','<?php echo $resume['id']; ?>')" title="Edit">✏️</button>
                        <button class="icon-btn icon-btn-view" onclick="window.open('resume.php?id=<?php echo $resume['id']; ?>', '_blank')" title="View">👁️</button>
                        <button class="icon-btn icon-btn-delete" onclick="showDeleteModal('<?php echo $resume['full_name']; ?>','<?php echo $resume['id']; ?>')" title="Delete">🗑️</button>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>

                <!-- Resume Card 2 -->
                <!-- <div class="card resume-card">
                    <div class="resume-avatar" style="background: #f59e0b;">MC</div>
                    <div class="resume-name">Mike Chen</div>
                    <div class="resume-status">Student</div>
                    <div class="resume-actions">
                        <button class="btn btn-primary btn-sm" onclick="showSetMyResumeModal('Mike Chen')">✓ Set as My Resume</button>
                    </div>
                    <div class="icon-actions">
                        <button class="icon-btn icon-btn-share" onclick="handleShare('Mike Chen')" title="Share">🔗</button>
                        <button class="icon-btn icon-btn-edit" onclick="showEditModal('Mike Chen')" title="Edit">✏️</button>
                        <button class="icon-btn icon-btn-view" onclick="handleView('Mike Chen')" title="View">👁️</button>
                        <button class="icon-btn icon-btn-delete" onclick="showDeleteModal('Mike ')" title="Delete">🗑️</button>
                    </div>
                </div> -->

                <!-- Create New Card -->
                <div class="card create-card" onclick="handleCreateNew()">
                    <div class="create-icon">➕</div>
                    <div class="create-text">Create New Resume</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Set My Resume Modal -->
    <div id="setMyResumeModal" class="modal-overlay">
        <div class="modal-content">
            <h3 class="modal-title">Set as My Resume</h3>
            <p class="modal-message" id="setMyResumeMessage"></p>
            <div class="modal-actions">
                <button class="modal-btn modal-btn-cancel" onclick="closeSetMyResumeModal()">Cancel</button>
                <form id="setMyResumeForm" action="actions/resume/set_my_resume.php" method="POST">
                    <input type="hidden" id="setMyResumeId" name="resume_id" value="">
                    <button class="modal-btn modal-btn-confirm" type="submit">Confirm</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal-overlay">
        <div class="modal-content">
            <h3 class="modal-title">Edit Resume</h3>
            <p class="modal-message" id="editMessage"></p>
            <div class="modal-actions">
                <button class="modal-btn modal-btn-cancel" onclick="closeEditModal()">Cancel</button>
                <button class="modal-btn modal-btn-confirm" id="editConfirmBtn" onclick="confirmEdit()">Continue to Edit</button>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="modal-overlay">
        <div class="modal-content">
            <h3 class="modal-title">Delete Resume</h3>
            <p class="modal-message" id="deleteMessage"></p>
            <div class="modal-actions">
                <button class="modal-btn modal-btn-cancel" onclick="closeDeleteModal()">Cancel</button>
                <button class="modal-btn modal-btn-danger" id="deleteConfirmBtn">Delete</button>
            </div>
        </div>
    </div>

    <script>
        let currentResumeName = '';

        // Set My Resume Modal Functions
        function showSetMyResumeModal(name, id) {
            currentResumeName = name;
            document.getElementById('setMyResumeMessage').textContent = 
                `Are you sure you want to set "${name}'s" resume as your active resume? Your current resume will be moved to the Others section.`;
            document.getElementById('setMyResumeModal').classList.add('show');
            document.getElementById('setMyResumeId').value = id;
        }

        function closeSetMyResumeModal() {
            document.getElementById('setMyResumeModal').classList.remove('show');
            currentResumeName = '';
        }

        // function confirmSetMyResume() {
        //     alert(`"${currentResumeName}'s" resume is now set as your active resume!`);
        //     closeSetMyResumeModal();
        //     // In real app: Update the "My Resume" section with selected resume
        // }

        // Edit Modal Functions
        function showEditModal(name, id) {
            currentResumeName = name;
            document.getElementById('editMessage').textContent = 
                `You are about to edit "${name}'s" resume. Any unsaved changes will be lost. Do you want to continue?`;
            document.getElementById('editModal').classList.add('show');
            const btnEditConfirm = document.getElementById('editConfirmBtn');
            btnEditConfirm.onclick = function() {
                window.location.href = `build.php?req=edit&id=${id}`;
            }
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('show');
            currentResumeName = '';
        }

        function confirmEdit() {
            alert(`Redirecting to edit page for "${currentResumeName}"...`);
            closeEditModal();
            // In real app: Redirect to edit page
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('show');
            currentResumeName = '';
        }

        

        // Other Action Handlers
        function handleDownload(name) {
            alert(`Downloading resume of ${name}\n\nIn a real app, this would generate and download a PDF file.`);
        }

        


        function handleView(name) {
            alert(`Viewing resume of ${name}\n\nOpening preview...`);
        }

        function handleCreateNew() {
            window.location.href = 'build.php?req=new';
        }

        function handleLogout() {
            const confirmed = confirm('Are you sure you want to logout?');
            if (confirmed) {
                window.location.href = 'actions/auth/logout.php'; // Redirect to logout script
            }
        }
    </script>
    <script src="js/dashboard.js"></script>
</body>
</html>