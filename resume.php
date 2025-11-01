<?php
if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_GET['id'])) {
    header('Location: index.html');
    exit();
}
$resume_id = $_GET['id'];

if (!$resume_id) {
    die("Resume ID not provided.");
}

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

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Resume</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <link rel="stylesheet" href="css/resume.css">
</head>
<body>
    <!-- Loading Modal -->
    <div class="loading-modal" id="loadingModal">
        <div class="loading-box">
            <div class="loader"></div>
            <p id="loadingText" style="color: #1e293b; font-weight: 700; font-size: 1.1rem;">Processing...</p>
        </div>
    </div>

    <!-- Theme Picker Modal -->
    <div class="theme-modal" id="themeModal">
        <div class="theme-box">
            <div class="theme-title">🎨 Customize Theme Color</div>
            <div class="color-selector">
                <label>Choose Your Color:</label>
                <input type="color" id="colorPicker" value="#0D9488">
            </div>
            <div class="preset-colors">
                <div class="preset-title">Quick Presets:</div>
                <div class="preset-item" style="background: #0D9488;" onclick="selectPreset('#0D9488')"></div>
                <div class="preset-item" style="background: #2563eb;" onclick="selectPreset('#2563eb')"></div>
                <div class="preset-item" style="background: #7c3aed;" onclick="selectPreset('#7c3aed')"></div>
                <div class="preset-item" style="background: #dc2626;" onclick="selectPreset('#dc2626')"></div>
                <div class="preset-item" style="background: #ea580c;" onclick="selectPreset('#ea580c')"></div>
                <div class="preset-item" style="background: #059669;" onclick="selectPreset('#059669')"></div>
                <div class="preset-item" style="background: #0891b2;" onclick="selectPreset('#0891b2')"></div>
                <div class="preset-item" style="background: #db2777;" onclick="selectPreset('#db2777')"></div>
                <div class="preset-item" style="background: #0f172a;" onclick="selectPreset('#0f172a')"></div>
            </div>
            <div class="theme-actions">
                <button class="btn-cancel-theme" onclick="closeThemePicker()">Cancel</button>
                <button class="btn-apply-theme" onclick="applyTheme()">Apply Theme</button>
            </div>
        </div>
    </div>

    <!-- Resume Container -->
    <div class="resume-wrapper">
        <div id="resumeContent">
            <!-- Header -->
            <div class="header-section">
                <div class="profile-photo" <?php echo !empty($resume['profile_photo']) ? 'style="display: none";' : ''; ?>>
                    <?php if (!empty($resume['profile_photo'])): ?>
                    <img src="<?php echo $resume['profile_photo']; ?>">
                    <?php else: ?>
                    <span><?php echo strtoupper(substr($resume['full_name'], 0, 2)); ?></span>
                    <?php endif; ?>
                </div>
                <div class="header-info">
                    <div class="candidate-name"><?php echo ucwords($resume['full_name']); ?></div>
                    <div class="contact-info">
                        <div>📍 <?php echo $resume['address']; ?></div>
                        <div>📞 <?php echo $resume['phone']; ?></div>
                        <div>✉️ <?php echo $resume['email']; ?></div>
                    </div>
                </div>
            </div>

            <!-- Career Objective -->
            <div class="content-section">
                <div class="section-heading">Career Objective</div>
                <div class="section-text">
                    <p class="objective-content">
                        <?php echo $resume['objective']; ?>
                    </p>
                </div>
            </div>

            <!-- Educational Background -->
            <div class="content-section">
                <div class="section-heading">Educational Background</div>
                <div class="section-text">
                    <?php foreach ($education as $edu): ?>
                    <div class="edu-item">
                        <div class="edu-title"><?php echo $edu['qualification']; ?></div>
                        <div class="edu-info"><?php echo $edu['institution']; ?> | <?php echo $edu['year']; ?></div>
                        <div class="edu-grade"><?php echo ucfirst($edu['status']); ?><?php echo !empty($edu['result']) ? ' | ' . $edu['result'] : ''; ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Professional Experience -->
            <div class="content-section">
                <div class="section-heading">Professional Experience</div>
                <?php foreach ($experience as $exp): ?>
                <div class="section-text">
                    <div class="exp-item">
                        <div class="exp-description"><?php echo $exp['description']; ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Technical Skills -->
            <div class="content-section">
                <div class="section-heading">Technical Skills</div>
                <div class="section-text">
                    <ul class="bullet-list">
                        <?php foreach ($skills as $skill): ?>
                        <li><?php echo ucfirst($skill['skill_name']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Core Competencies -->
            <div class="content-section">
                <div class="section-heading">Core Competencies</div>
                <div class="section-text">
                    <ul class="bullet-list">
                        <?php foreach ($competencies as $comp): ?>
                        <li><?php echo ucfirst($comp['competency_name']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="content-section">
                <div class="section-heading">Personal Information</div>
                <div class="section-text">
                    <div class="info-grid">
                        <div class="info-row">
                            <span class="info-label">Father's Name:</span>
                            <span class="info-data"><?php echo ucfirst($resume['father_name']); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Date of Birth:</span>
                            <span class="info-data"><?php echo date('d M Y', strtotime($resume['date_of_birth'])); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Gender:</span>
                            <span class="info-data"><?php echo ucfirst($resume['gender']); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Marital Status:</span>
                            <span class="info-data"><?php echo ucfirst($resume['marital_status']); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Nationality:</span>
                            <span class="info-data"><?php echo ucfirst($resume['nationality']); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Languages:</span>
                            <span class="info-data"><?php echo ucfirst($resume['languages_known']); ?></span>
                        </div>
                        <div class="info-row info-full">
                            <span class="info-label">Strengths:</span>
                            <span class="info-data"><?php echo ucfirst($resume['strengths']); ?></span>
                        </div>
                        <div class="info-row info-full">
                            <span class="info-label">Hobbies:</span>
                            <span class="info-data"><?php echo ucfirst($resume['hobbies']); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Declaration -->
            <div class="content-section">
                <div class="section-heading">Declaration</div>
                <div class="section-text">
                    <p class="declaration-content">
                        <?php echo ucfirst($resume['declaration_text']); ?>
                    </p>
                    <div class="signature-area">
                        <div>
                            <div class="signature-location">Place: <?php echo ucfirst($resume['declaration_city']); ?></div>
                            <div class="signature-location" style="margin-top: 5px;">Date: _______________</div>
                        </div>
                        <div class="signature-name">(<?php echo ucfirst($resume['full_name']); ?>)</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Control Panel -->
    <div class="control-panel">
        <button class="control-btn btn-theme" onclick="openThemePicker()">
            🎨 Change Color
        </button>
        <button class="control-btn btn-share" onclick="shareResume()">
            🔗 Share Resume
        </button>
        <button class="control-btn btn-doc" onclick="downloadDOC()">
            📄 Download DOC
        </button>
        <button class="control-btn btn-pdf" onclick="downloadPDF()">
            📥 Download PDF
        </button>
    </div>

    <script>
        let activeThemeColor = '#0D9488';

        // Theme Picker Functions
        function openThemePicker() {
            document.getElementById('themeModal').classList.add('active');
            document.getElementById('colorPicker').value = activeThemeColor;
        }

        function closeThemePicker() {
            document.getElementById('themeModal').classList.remove('active');
        }

        function selectPreset(color) {
            document.getElementById('colorPicker').value = color;
        }

        function applyTheme() {
            const selectedColor = document.getElementById('colorPicker').value;
            activeThemeColor = selectedColor;
            
            document.documentElement.style.setProperty('--theme-color', selectedColor);
            
            const styleTag = document.createElement('style');
            styleTag.innerHTML = `
                .header-section { border-bottom-color: ${selectedColor} !important; }
                .profile-photo { background: ${selectedColor} !important; border-color: ${selectedColor} !important; }
                .section-heading { color: ${selectedColor} !important; border-bottom-color: ${selectedColor} !important; }
                .edu-grade { color: ${selectedColor} !important; }
                .bullet-list li:before { color: ${selectedColor} !important; }
                .btn-pdf { background: ${selectedColor} !important; }
                .btn-doc { background: ${selectedColor} !important; }
                .btn-share { background: ${selectedColor} !important; }
                .btn-theme { background: ${selectedColor} !important; }
                .loader { border-top-color: ${selectedColor} !important; }
                .btn-apply-theme { background: ${selectedColor} !important; }
            `;
            
            const oldStyle = document.getElementById('dynamic-theme');
            if (oldStyle) oldStyle.remove();
            
            styleTag.id = 'dynamic-theme';
            document.head.appendChild(styleTag);
            
            closeThemePicker();
        }

        // Download PDF Function
        async function downloadPDF() {
            const { jsPDF } = window.jspdf;
            
            document.getElementById('loadingModal').classList.add('active');
            document.getElementById('loadingText').textContent = 'Generating PDF...';
            
            try {
                const content = document.getElementById('resumeContent');
                
                // Store original styles
                const isMobile = window.innerWidth <= 768;
                let originalStyles = {};
                
                if (isMobile) {
                    // Temporarily apply desktop styles for PDF generation
                    const elementsToFix = {
                        content: content,
                        header: content.querySelector('.header-section'),
                        photo: content.querySelector('.profile-photo'),
                        name: content.querySelector('.candidate-name'),
                        infoGrid: content.querySelector('.info-grid')
                    };
                    
                    // Save original styles
                    Object.keys(elementsToFix).forEach(key => {
                        if (elementsToFix[key]) {
                            originalStyles[key] = elementsToFix[key].style.cssText;
                        }
                    });
                    
                    // Apply desktop styles temporarily
                    if (elementsToFix.content) {
                        elementsToFix.content.style.padding = '50px 60px';
                    }
                    if (elementsToFix.header) {
                        elementsToFix.header.style.flexDirection = 'row';
                        elementsToFix.header.style.alignItems = 'flex-start';
                        elementsToFix.header.style.textAlign = 'left';
                    }
                    if (elementsToFix.photo) {
                        elementsToFix.photo.style.width = '130px';
                        elementsToFix.photo.style.height = '130px';
                        elementsToFix.photo.style.fontSize = '3.5rem';
                    }
                    if (elementsToFix.name) {
                        elementsToFix.name.style.fontSize = '2.5rem';
                    }
                    if (elementsToFix.infoGrid) {
                        elementsToFix.infoGrid.style.gridTemplateColumns = 'repeat(2, 1fr)';
                    }
                }
                
                const canvas = await html2canvas(content, {
                    scale: 3,
                    useCORS: true,
                    logging: false,
                    backgroundColor: '#ffffff',
                    windowWidth: 900,
                    windowHeight: content.scrollHeight,
                });
                
                // Restore original styles if mobile
                if (isMobile) {
                    Object.keys(originalStyles).forEach(key => {
                        const elements = {
                            content: content,
                            header: content.querySelector('.header-section'),
                            photo: content.querySelector('.profile-photo'),
                            name: content.querySelector('.candidate-name'),
                            infoGrid: content.querySelector('.info-grid')
                        };
                        if (elements[key]) {
                            elements[key].style.cssText = originalStyles[key];
                        }
                    });
                }
                
                const pdf = new jsPDF('p', 'mm', 'a4');
                const imgData = canvas.toDataURL('image/png');
                
                const pdfWidth = 210;
                const pdfHeight = 297;
                const imgWidth = pdfWidth;
                const imgHeight = (canvas.height * pdfWidth) / canvas.width;
                
                const totalPages = Math.ceil(imgHeight / pdfHeight);
                
                for (let page = 0; page < totalPages; page++) {
                    if (page > 0) pdf.addPage();
                    const yOffset = -page * pdfHeight;
                    pdf.addImage(imgData, 'PNG', 0, yOffset, imgWidth, imgHeight);
                }
                
                pdf.save('John_Doe_Resume.pdf');
                
                document.getElementById('loadingModal').classList.remove('active');
                
                setTimeout(() => {
                    alert('✅ PDF downloaded successfully!');
                }, 300);
                
            } catch (error) {
                console.error('PDF Error:', error);
                document.getElementById('loadingModal').classList.remove('active');
                alert('❌ Error generating PDF. Please try again.');
            }
        }

        // Download DOC Function
        function downloadDOC() {
            document.getElementById('loadingModal').classList.add('active');
            document.getElementById('loadingText').textContent = 'Generating DOC...';
            
            try {
                const content = document.getElementById('resumeContent').cloneNode(true);
                
                const docHTML = `
                    <!DOCTYPE html>
                    <html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'>
                    <head>
                        <meta charset='utf-8'>
                        <title>Resume</title>
                        <style>
                            body { font-family: Arial, sans-serif; line-height: 1.6; color: #475569; }
                            .header-section { display: flex; align-items: flex-start; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 4px solid ${activeThemeColor}; }
                            .profile-photo { width: 120px; height: 120px; border-radius: 50%; background: ${activeThemeColor}; color: white; display: flex; align-items: center; justify-content: center; font-size: 3rem; font-weight: bold; margin-right: 25px; }
                            .candidate-name { font-size: 2.2rem; font-weight: bold; color: #1e293b; margin-bottom: 10px; }
                            .contact-info { font-size: 1rem; color: #64748b; }
                            .contact-info div { margin-bottom: 4px; }
                            .content-section { margin-bottom: 25px; }
                            .section-heading { font-size: 1.3rem; font-weight: bold; color: ${activeThemeColor}; margin-bottom: 12px; padding-bottom: 6px; border-bottom: 3px solid ${activeThemeColor}; text-transform: uppercase; }
                            .edu-item, .exp-item { margin-bottom: 15px; padding-left: 18px; border-left: 4px solid #e2e8f0; }
                            .edu-title { font-weight: bold; font-size: 1.05rem; color: #1e293b; }
                            .edu-info { color: #64748b; font-size: 0.95rem; }
                            .edu-grade { color: ${activeThemeColor}; font-weight: 600; }
                            .bullet-list { list-style: none; padding-left: 0; }
                            .bullet-list li { padding: 6px 0; padding-left: 22px; position: relative; }
                            .bullet-list li:before { content: "▸"; position: absolute; left: 0; color: ${activeThemeColor}; font-weight: bold; }
                            .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
                            .info-row { display: flex; }
                            .info-label { font-weight: bold; color: #1e293b; min-width: 140px; }
                            .info-data { color: #64748b; }
                            .signature-area { margin-top: 20px; display: flex; justify-content: space-between; font-weight: bold; }
                        </style>
                    </head>
                    <body>
                        ${content.innerHTML}
                    </body>
                    </html>
                `;
                
                const blob = new Blob(['\ufeff', docHTML], {
                    type: 'application/msword'
                });
                
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = 'John_Doe_Resume.doc';
                link.click();
                
                document.getElementById('loadingModal').classList.remove('active');
                
                setTimeout(() => {
                    alert('✅ DOC file downloaded successfully!');
                }, 300);
                
            } catch (error) {
                console.error('DOC Error:', error);
                document.getElementById('loadingModal').classList.remove('active');
                alert('❌ Error generating DOC. Please try again.');
            }
        }

        // Share Resume Function
        function shareResume() {
            const url = window.location.href;
            const text = 'Check out my professional resume!';
            
            if (navigator.share) {
                navigator.share({
                    title: 'John Doe - Professional Resume',
                    text: text,
                    url: url
                })
                .then(() => console.log('Shared successfully'))
                .catch((error) => console.log('Share cancelled', error));
            } else {
                const tempInput = document.createElement('input');
                tempInput.value = url;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);
                
                alert('🔗 Resume link copied to clipboard!\n\n' + url + '\n\nYou can now share it anywhere!');
            }
        }

        // Close modals on outside click
        document.getElementById('themeModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeThemePicker();
            }
        });

        // Keyboard Shortcuts
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                downloadPDF();
            }
            
            if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
                e.preventDefault();
                downloadDOC();
            }
            
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                shareResume();
            }

            if ((e.ctrlKey || e.metaKey) && e.key === 't') {
                e.preventDefault();
                openThemePicker();
            }
        });

        // Log library status
        console.log('✓ jsPDF loaded:', typeof window.jspdf !== 'undefined');
        console.log('✓ html2canvas loaded:', typeof html2canvas !== 'undefined');
    </script>