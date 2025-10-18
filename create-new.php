<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Resume - Resume Builder</title>
    <style>
        /* ==================== COMMON STYLES ==================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f7fa;
            min-height: 100vh;
            padding-bottom: 40px;
        }

        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
            line-height: 1.3;
            color: #1f2937;
        }

        :root {
            --primary-color: #0D9488;
            --primary-dark: #0a7a70;
            --secondary-color: #64748b;
            --danger-color: #ef4444;
            --bg-light: #f5f7fa;
            --border-color: #e5e7eb;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 0.9rem;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.3);
        }

        .btn-secondary {
            background: var(--secondary-color);
            color: white;
        }

        .btn-secondary:hover {
            background: #475569;
        }

        .btn-danger {
            background: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-color);
            margin-bottom: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* ==================== NAVBAR ==================== */
        .navbar {
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: 15px 0;
            margin-bottom: 30px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .back-btn {
            padding: 8px 16px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* ==================== PAGE HEADER ==================== */
        .page-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 2rem;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .page-header p {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        /* ==================== PHOTO UPLOAD ==================== */
        .photo-section {
            text-align: center;
            margin-bottom: 20px;
        }

        .photo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .photo-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: 700;
            overflow: hidden;
            border: 4px solid #e5e7eb;
        }

        .photo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-upload-btn {
            display: inline-block;
            padding: 8px 20px;
            background: var(--primary-color);
            color: white;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .photo-upload-btn:hover {
            background: var(--primary-dark);
        }

        .photo-upload-btn input[type="file"] {
            display: none;
        }

        /* ==================== FORM SECTIONS ==================== */
        .form-section {
            margin-bottom: 15px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border-radius: 8px;
            cursor: pointer;
            user-select: none;
            transition: all 0.3s ease;
        }

        .section-header:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.3);
        }

        .section-header h3 {
            color: white;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-actions {
            display: flex;
            gap: 8px;
        }

        .delete-section-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .delete-section-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .collapse-icon {
            transition: transform 0.3s ease;
        }

        .section-header.collapsed .collapse-icon {
            transform: rotate(-180deg);
        }

        .section-content {
            padding: 20px;
            background: white;
            border: 1px solid var(--border-color);
            border-top: none;
            border-radius: 0 0 8px 8px;
            max-height: 2000px;
            overflow: hidden;
            transition: max-height 0.4s ease, padding 0.4s ease, opacity 0.3s ease;
            opacity: 1;
        }

        .section-content.hidden {
            max-height: 0;
            padding: 0 20px;
            opacity: 0;
            border: none;
        }

        /* Section Delete Animation */
        .form-section.deleting {
            animation: slideOut 0.4s ease forwards;
        }

        @keyframes slideOut {
            0% {
                opacity: 1;
                transform: translateX(0);
                max-height: 1000px;
            }
            50% {
                opacity: 0.5;
                transform: translateX(-20px);
            }
            100% {
                opacity: 0;
                transform: translateX(-100%);
                max-height: 0;
                margin-bottom: 0;
                padding: 0;
            }
        }

        /* ==================== FORM ELEMENTS ==================== */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .page-header h1 {
                font-size: 1.5rem;
            }
            
            .page-header p {
                font-size: 0.9rem;
            }
            
            .navbar-brand {
                font-size: 1.1rem;
            }
            
            .back-btn {
                font-size: 0.8rem;
                padding: 6px 12px;
            }
            
            .back-btn span {
                display: none;
            }
            
            .section-header h3 {
                font-size: 1rem;
            }
            
            .delete-section-btn {
                font-size: 0.75rem;
                padding: 4px 8px;
            }
            
            .photo-preview {
                width: 100px;
                height: 100px;
                font-size: 2.5rem;
            }
            
            .card {
                padding: 15px;
            }
            
            .dynamic-section {
                padding: 12px;
                padding-top: 35px;
            }
            
            .remove-row-btn {
                font-size: 0.75rem;
                padding: 4px 8px;
                top: 5px;
                right: 5px;
            }
            
            .form-group input,
            .form-group textarea,
            .form-group select {
                font-size: 16px; /* Prevents zoom on iOS */
            }
            
            .skill-input-group {
                flex-direction: column;
            }
            
            .skill-input-group .btn {
                width: 100%;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .form-actions .btn {
                width: 100%;
            }
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 0 10px;
            }
            
            .navbar-content {
                padding: 0 10px;
            }
            
            .back-btn {
                padding: 6px 10px;
            }
        }

        /* ==================== DYNAMIC ROWS ==================== */
        .dynamic-section {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            position: relative;
            background: #fafafa;
        }

        .remove-row-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--danger-color);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .remove-row-btn:hover {
            background: #dc2626;
        }

        .add-more-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            margin-top: 10px;
            transition: all 0.3s ease;
        }

        .add-more-btn:hover {
            background: var(--primary-dark);
        }

        /* ==================== SKILLS & TAGS ==================== */
        .skills-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }

        .skill-tag {
            background: var(--primary-color);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .remove-skill {
            cursor: pointer;
            font-weight: bold;
        }

        .skill-input-group {
            display: flex;
            gap: 8px;
            margin-bottom: 10px;
        }

        .skill-input-group input {
            flex: 1;
        }

        /* ==================== OBJECTIVE OPTIONS ==================== */
        .objective-options {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 15px;
        }

        .objective-option {
            padding: 12px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .objective-option:hover {
            border-color: var(--primary-color);
            background: rgba(13, 148, 136, 0.05);
        }

        .objective-option.selected {
            border-color: var(--primary-color);
            background: rgba(13, 148, 136, 0.1);
            font-weight: 600;
        }

        /* ==================== FORM ACTIONS ==================== */
        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid var(--border-color);
        }

        .form-actions .btn {
            padding: 12px 30px;
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            .form-actions {
                flex-direction: column;
            }

            .form-actions .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container navbar-content">
            <a href="#" class="navbar-brand">
                <span>📄</span>
                Resume Builder
            </a>
            <button class="btn btn-secondary back-btn" onclick="handleBack()">
                ← <span>Back to Dashboard</span>
            </button>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1>Create New Resume</h1>
            <p>Fill in the details below to create your professional resume</p>
        </div>

        <!-- Photo Upload Section -->
        <div class="card photo-section">
            <div class="photo-container">
                <div class="photo-preview" id="photoPreview">
                    <span id="photoInitials">?</span>
                    <img id="photoImage" style="display: none;">
                </div>
                <label class="photo-upload-btn">
                    📷 Upload Photo
                    <input type="file" accept="image/*" onchange="handlePhotoUpload(event)">
                </label>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="card form-section">
            <div class="section-header">
                <h3>👤 Personal Information</h3>
            </div>
            <div class="section-content">
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" id="fullName" placeholder="Enter your full name" oninput="updatePhotoInitials()">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" placeholder="Enter your address">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="tel" placeholder="Enter contact number">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" placeholder="Enter email address">
                    </div>
                </div>
            </div>
        </div>

        <!-- Objective Section -->
        <div class="card form-section" id="objectiveSection">
            <div class="section-header collapsed" onclick="toggleSection('objectiveContent')">
                <h3>
                    🎯 Objective
                    <span class="collapse-icon">▼</span>
                </h3>
                <div class="section-actions" onclick="event.stopPropagation()">
                    <button class="delete-section-btn" onclick="deleteSection('objectiveSection')">🗑️ Delete</button>
                </div>
            </div>
            <div class="section-content hidden" id="objectiveContent">
                <div class="objective-options">
                    <div class="objective-option" onclick="selectObjective(0)">
                        To secure a challenging position where I can utilize my skills and contribute to organizational growth.
                    </div>
                    <div class="objective-option" onclick="selectObjective(1)">
                        Seeking an opportunity to leverage my expertise in a dynamic environment and achieve professional excellence.
                    </div>
                    <div class="objective-option" onclick="selectObjective(2)">
                        To obtain a position that allows me to grow professionally while contributing to the success of the organization.
                    </div>
                </div>
                <div class="form-group">
                    <label>Custom Objective</label>
                    <textarea id="objectiveText" placeholder="Or write your own objective..."></textarea>
                </div>
            </div>
        </div>

        <!-- Education Section -->
        <div class="card form-section" id="educationSection">
            <div class="section-header collapsed" onclick="toggleSection('educationContent')">
                <h3>
                    🎓 Educational Qualification
                    <span class="collapse-icon">▼</span>
                </h3>
                <div class="section-actions" onclick="event.stopPropagation()">
                    <button class="delete-section-btn" onclick="deleteSection('educationSection')">🗑️ Delete</button>
                </div>
            </div>
            <div class="section-content hidden" id="educationContent">
                <div id="educationRows">
                    <div class="dynamic-section">
                        <button class="remove-row-btn" onclick="removeRow(this)">✕ Remove</button>
                        <div class="form-group">
                            <label>Degree/Qualification</label>
                            <input type="text" placeholder="e.g., B.Tech in Computer Science">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Institution</label>
                                <input type="text" placeholder="Enter institution name">
                            </div>
                            <div class="form-group">
                                <label>Year</label>
                                <input type="text" placeholder="e.g., 2020-2024">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Status</label>
                                <select>
                                    <option value="">Select Status</option>
                                    <option value="completed">Completed</option>
                                    <option value="pursuing">Pursuing</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Result</label>
                                <input type="text" placeholder="e.g., 8.5 CGPA or 85%">
                            </div>
                        </div>
                    </div>
                </div>
                <button class="add-more-btn" onclick="addEducationRow()">+ Add More Education</button>
            </div>
        </div>

        <!-- Work Experience Section -->
        <div class="card form-section" id="workSection">
            <div class="section-header collapsed" onclick="toggleSection('workContent')">
                <h3>
                    💼 Work Experience
                    <span class="collapse-icon">▼</span>
                </h3>
                <div class="section-actions" onclick="event.stopPropagation()">
                    <button class="delete-section-btn" onclick="deleteSection('workSection')">🗑️ Delete</button>
                </div>
            </div>
            <div class="section-content hidden" id="workContent">
                <div id="workRows">
                    <div class="dynamic-section">
                        <button class="remove-row-btn" onclick="removeRow(this)">✕ Remove</button>
                        <div class="form-group">
                            <label>Work Experience</label>
                            <input type="text" placeholder="e.g., 6 months experience as Executive in ABC Company">
                        </div>
                    </div>
                </div>
                <button class="add-more-btn" onclick="addWorkRow()">+ Add More Experience</button>
            </div>
        </div>

        <!-- Key Skills Section -->
        <div class="card form-section" id="skillsSection">
            <div class="section-header collapsed" onclick="toggleSection('skillsContent')">
                <h3>
                    ⚡ Key Skills
                    <span class="collapse-icon">▼</span>
                </h3>
                <div class="section-actions" onclick="event.stopPropagation()">
                    <button class="delete-section-btn" onclick="deleteSection('skillsSection')">🗑️ Delete</button>
                </div>
            </div>
            <div class="section-content hidden" id="skillsContent">
                <div class="skill-input-group">
                    <input type="text" id="skillInput" placeholder="Enter a skill">
                    <button class="btn btn-primary" onclick="addSkill()">Add Skill</button>
                </div>
                <div class="skills-container" id="skillsContainer"></div>
            </div>
        </div>

        <!-- Key Competencies Section -->
        <div class="card form-section" id="competenciesSection">
            <div class="section-header collapsed" onclick="toggleSection('competenciesContent')">
                <h3>
                    🏆 Key Competencies
                    <span class="collapse-icon">▼</span>
                </h3>
                <div class="section-actions" onclick="event.stopPropagation()">
                    <button class="delete-section-btn" onclick="deleteSection('competenciesSection')">🗑️ Delete</button>
                </div>
            </div>
            <div class="section-content hidden" id="competenciesContent">
                <div class="skill-input-group">
                    <input type="text" id="competencyInput" placeholder="Enter a competency">
                    <button class="btn btn-primary" onclick="addCompetency()">Add Competency</button>
                </div>
                <div class="skills-container" id="competenciesContainer"></div>
            </div>
        </div>

        <!-- Personal Details Section -->
        <div class="card form-section" id="personalSection">
            <div class="section-header collapsed" onclick="toggleSection('personalContent')">
                <h3>
                    📋 Personal Details
                    <span class="collapse-icon">▼</span>
                </h3>
                <div class="section-actions" onclick="event.stopPropagation()">
                    <button class="delete-section-btn" onclick="deleteSection('personalSection')">🗑️ Delete</button>
                </div>
            </div>
            <div class="section-content hidden" id="personalContent">
                <div class="form-row">
                    <div class="form-group">
                        <label>Father's Name</label>
                        <input type="text" placeholder="Enter father's name">
                    </div>
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Gender</label>
                        <select>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Marital Status</label>
                        <select>
                            <option value="">Select Status</option>
                            <option value="single">Single</option>
                            <option value="married">Married</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Nationality</label>
                        <input type="text" placeholder="Enter nationality">
                    </div>
                    <div class="form-group">
                        <label>Languages Known</label>
                        <input type="text" placeholder="e.g., English, Hindi">
                    </div>
                </div>
                <div class="form-group">
                    <label>Strength</label>
                    <textarea placeholder="Describe your strengths..."></textarea>
                </div>
                <div class="form-group">
                    <label>Hobbies</label>
                    <input type="text" placeholder="e.g., Reading, Traveling, Music">
                </div>
            </div>
        </div>

        <!-- Declaration Section -->
        <div class="card form-section" id="declarationSection">
            <div class="section-header collapsed" onclick="toggleSection('declarationContent')">
                <h3>
                    ✍️ Declaration
                    <span class="collapse-icon">▼</span>
                </h3>
                <div class="section-actions" onclick="event.stopPropagation()">
                    <button class="delete-section-btn" onclick="deleteSection('declarationSection')">🗑️ Delete</button>
                </div>
            </div>
            <div class="section-content hidden" id="declarationContent">
                <div class="form-group">
                    <label>Declaration Text</label>
                    <textarea id="declarationText">I hereby declare that the information provided above is true and correct to the best of my knowledge and belief.</textarea>
                </div>
                <div class="form-group">
                    <label>City</label>
                    <input type="text" placeholder="Enter city name">
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button class="btn btn-secondary" onclick="handleCancel()">Cancel</button>
            <button class="btn btn-primary" onclick="handlePreview()">👁️ Preview Resume</button>
            <button class="btn btn-primary" onclick="handleSave()">💾 Save Resume</button>
        </div>
    </div>

    <script>
        // Photo Upload
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

        // Section Toggle
        function toggleSection(contentId) {
            const content = document.getElementById(contentId);
            const header = content.previousElementSibling;
            
            content.classList.toggle('hidden');
            header.classList.toggle('collapsed');
        }

        // Delete Section
        function deleteSection(sectionId) {
            if (confirm('Are you sure you want to delete this section?')) {
                const section = document.getElementById(sectionId);
                section.classList.add('deleting');
                
                // Remove element after animation completes
                setTimeout(() => {
                    section.remove();
                }, 400);
            }
        }

        // Objective Selection
        let selectedObjectiveIndex = -1;
        function selectObjective(index) {
            const options = document.querySelectorAll('.objective-option');
            options.forEach((opt, i) => {
                opt.classList.toggle('selected', i === index);
            });
            selectedObjectiveIndex = index;
            document.getElementById('objectiveText').value = options[index].textContent.trim();
        }

        // Add Education Row
        function addEducationRow() {
            const container = document.getElementById('educationRows');
            const newRow = document.createElement('div');
            newRow.className = 'dynamic-section';
            newRow.innerHTML = `
                <button class="remove-row-btn" onclick="removeRow(this)">✕ Remove</button>
                <div class="form-group">
                    <label>Degree/Qualification</label>
                    <input type="text" placeholder="e.g., B.Tech in Computer Science">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Institution</label>
                        <input type="text" placeholder="Enter institution name">
                    </div>
                    <div class="form-group">
                        <label>Year</label>
                        <input type="text" placeholder="e.g., 2020-2024">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Status</label>
                        <select>
                            <option value="">Select Status</option>
                            <option value="completed">Completed</option>
                            <option value="pursuing">Pursuing</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Result</label>
                        <input type="text" placeholder="e.g., 8.5 CGPA or 85%">
                    </div>
                </div>
            `;
            container.appendChild(newRow);
        }

        // Add Work Row
        function addWorkRow() {
            const container = document.getElementById('workRows');
            const newRow = document.createElement('div');
            newRow.className = 'dynamic-section';
            newRow.innerHTML = `
                <button class="remove-row-btn" onclick="removeRow(this)">✕ Remove</button>
                <div class="form-group">
                    <label>Work Experience</label>
                    <input type="text" placeholder="e.g., 6 months experience as Executive in ABC Company">
                </div>
            `;
            container.appendChild(newRow);
        }

        // Remove Row
        function removeRow(button) {
            const row = button.parentElement;
            const container = row.parentElement;
            if (container.children.length > 1) {
                row.remove();
            } else {
                alert('At least one entry is required!');
            }
        }

        // Add Skill
        function addSkill() {
            const input = document.getElementById('skillInput');
            const skill = input.value.trim();
            if (skill) {
                const container = document.getElementById('skillsContainer');
                const tag = document.createElement('div');
                tag.className = 'skill-tag';
                tag.innerHTML = `${skill} <span class="remove-skill" onclick="removeTag(this)">✕</span>`;
                container.appendChild(tag);
                input.value = '';
            }
        }

        // Add Competency
        function addCompetency() {
            const input = document.getElementById('competencyInput');
            const competency = input.value.trim();
            if (competency) {
                const container = document.getElementById('competenciesContainer');
                const tag = document.createElement('div');
                tag.className = 'skill-tag';
                tag.innerHTML = `${competency} <span class="remove-skill" onclick="removeTag(this)">✕</span>`;
                container.appendChild(tag);
                input.value = '';
            }
        }

        // Remove Tag
        function removeTag(span) {
            span.parentElement.remove();
        }

        // Allow Enter key to add skills/competencies
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

        // Form Actions
        function handleSave() {
            const name = document.getElementById('fullName').value.trim();
            if (!name) {
                alert('Please enter your full name before saving!');
                return;
            }
            
            console.log('Saving resume...');
            console.log('Name:', name);
            alert(`✅ Resume saved successfully for ${name}!\n\nIn a real application, this would save the data to a database.`);
        }

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
                // In real app: window.location.href = 'dashboard.html';
            }
        }

        function handleBack() {
            if (confirm('Are you sure you want to go back? Any unsaved changes will be lost.')) {
                alert('Redirecting to Dashboard...');
                // In real app: window.location.href = 'dashboard.html';
            }
        }
    </script>
</body>
</html>