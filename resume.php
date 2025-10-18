<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Resume</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            padding: 30px;
            min-height: 100vh;
        }

        .resume-wrapper {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            overflow: hidden;
        }

        #resumeContent {
            padding: 50px 60px;
        }

        /* Header Section */
        .header-section {
            display: flex;
            align-items: flex-start;
            gap: 30px;
            margin-bottom: 35px;
            padding-bottom: 25px;
            border-bottom: 4px solid var(--theme-color, #0D9488);
        }

        .profile-photo {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            background: var(--theme-color, #0D9488);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3.5rem;
            font-weight: 700;
            flex-shrink: 0;
            border: 5px solid var(--theme-color, #0D9488);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .header-info {
            flex: 1;
        }

        .candidate-name {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .contact-info {
            font-size: 1rem;
            color: #64748b;
            line-height: 2;
        }

        .contact-info div {
            margin-bottom: 4px;
        }

        /* Section Styles */
        .content-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .section-heading {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--theme-color, #0D9488);
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 3px solid var(--theme-color, #0D9488);
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .section-text {
            color: #475569;
            font-size: 1rem;
            line-height: 1.8;
        }

        /* Objective Section */
        .objective-content {
            text-align: justify;
            line-height: 1.9;
        }

        /* Education Items */
        .edu-item {
            margin-bottom: 18px;
            padding-left: 20px;
            border-left: 4px solid #e2e8f0;
            page-break-inside: avoid;
        }

        .edu-title {
            font-weight: 700;
            font-size: 1.1rem;
            color: #1e293b;
            margin-bottom: 5px;
        }

        .edu-info {
            color: #64748b;
            font-size: 0.95rem;
            margin-top: 4px;
        }

        .edu-grade {
            color: var(--theme-color, #0D9488);
            font-weight: 600;
            margin-top: 5px;
        }

        /* Experience Items */
        .exp-item {
            margin-bottom: 15px;
            padding-left: 20px;
            border-left: 4px solid #e2e8f0;
            page-break-inside: avoid;
        }

        .exp-description {
            color: #475569;
            line-height: 1.8;
        }

        /* Skills & Competencies List */
        .bullet-list {
            list-style: none;
            padding-left: 0;
        }

        .bullet-list li {
            padding: 8px 0;
            padding-left: 25px;
            position: relative;
            page-break-inside: avoid;
        }

        .bullet-list li:before {
            content: "▸";
            position: absolute;
            left: 0;
            color: var(--theme-color, #0D9488);
            font-weight: 700;
            font-size: 1.2rem;
        }

        /* Personal Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .info-row {
            display: flex;
            page-break-inside: avoid;
        }

        .info-label {
            font-weight: 700;
            color: #1e293b;
            min-width: 150px;
        }

        .info-data {
            color: #64748b;
        }

        .info-full {
            grid-column: 1 / -1;
        }

        /* Declaration */
        .declaration-content {
            text-align: justify;
            line-height: 1.9;
            margin-bottom: 20px;
        }

        .signature-area {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
        }

        .signature-location,
        .signature-name {
            font-weight: 700;
            color: #1e293b;
        }

        /* Control Buttons */
        .control-panel {
            position: fixed;
            bottom: 30px;
            right: 30px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            z-index: 1000;
        }

        .control-btn {
            padding: 15px 28px;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
            min-width: 200px;
            justify-content: center;
        }

        .btn-pdf {
            background: var(--theme-color, #0D9488);
            color: white;
        }

        .btn-pdf:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(13, 148, 136, 0.5);
        }

        .btn-doc {
            background: var(--theme-color, #0D9488);
            color: white;
        }

        .btn-doc:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(13, 148, 136, 0.5);
        }

        .btn-share {
            background: var(--theme-color, #0D9488);
            color: white;
        }

        .btn-share:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(13, 148, 136, 0.5);
        }

        .btn-theme {
            background: var(--theme-color, #0D9488);
            color: white;
        }

        .btn-theme:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(13, 148, 136, 0.5);
        }

        /* Loading Modal */
        .loading-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .loading-modal.active {
            display: flex;
        }

        .loading-box {
            background: white;
            padding: 40px 50px;
            border-radius: 15px;
            text-align: center;
        }

        .loader {
            width: 60px;
            height: 60px;
            border: 5px solid #e2e8f0;
            border-top: 5px solid #0D9488;
            border-radius: 50%;
            animation: rotate 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Theme Picker Modal */
        .theme-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 2500;
            align-items: center;
            justify-content: center;
        }

        .theme-modal.active {
            display: flex;
        }

        .theme-box {
            background: white;
            padding: 40px;
            border-radius: 15px;
            max-width: 450px;
            width: 90%;
        }

        .theme-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 25px;
        }

        .color-selector {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .color-selector label {
            flex: 1;
            font-weight: 600;
            color: #475569;
        }

        .color-selector input[type="color"] {
            width: 70px;
            height: 50px;
            border: 3px solid #e2e8f0;
            border-radius: 10px;
            cursor: pointer;
        }

        .preset-colors {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-top: 20px;
        }

        .preset-title {
            grid-column: 1 / -1;
            color: #64748b;
            font-size: 0.95rem;
            margin-bottom: 5px;
        }

        .preset-item {
            width: 100%;
            height: 50px;
            border-radius: 10px;
            border: 3px solid #e2e8f0;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .preset-item:hover {
            transform: scale(1.1);
            border-color: #94a3b8;
        }

        .theme-actions {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        .theme-actions button {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-apply-theme {
            background: #0D9488;
            color: white;
        }

        .btn-apply-theme:hover {
            background: #0a7a70;
        }

        .btn-cancel-theme {
            background: #e2e8f0;
            color: #475569;
        }

        .btn-cancel-theme:hover {
            background: #cbd5e1;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            #resumeContent {
                padding: 30px 25px;
            }

            .header-section {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .profile-photo {
                width: 110px;
                height: 110px;
                font-size: 2.8rem;
            }

            .candidate-name {
                font-size: 1.8rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .control-panel {
                position: static;
                margin: 30px auto 0;
                max-width: 400px;
                padding: 0 15px 30px;
            }

            .control-btn {
                padding: 15px 20px;
                font-size: 0.95rem;
                min-width: 100%;
            }
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .resume-wrapper {
                box-shadow: none;
                border-radius: 0;
            }

            .control-panel {
                display: none;
            }
        }
    </style>
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
                <div class="profile-photo">
                    <span>JD</span>
                </div>
                <div class="header-info">
                    <div class="candidate-name">JOHN DOE</div>
                    <div class="contact-info">
                        <div>📍 123 Main Street, Kanpur, Uttar Pradesh, India</div>
                        <div>📞 +91 98765 43210</div>
                        <div>✉️ john.doe@email.com</div>
                    </div>
                </div>
            </div>

            <!-- Career Objective -->
            <div class="content-section">
                <div class="section-heading">Career Objective</div>
                <div class="section-text">
                    <p class="objective-content">
                        To secure a challenging position where I can utilize my skills and contribute to organizational growth while achieving professional excellence in a dynamic work environment.
                    </p>
                </div>
            </div>

            <!-- Educational Background -->
            <div class="content-section">
                <div class="section-heading">Educational Background</div>
                <div class="section-text">
                    <div class="edu-item">
                        <div class="edu-title">Bachelor of Technology in Computer Science</div>
                        <div class="edu-info">ABC University | 2020 - 2024</div>
                        <div class="edu-grade">Completed | CGPA: 8.5/10</div>
                    </div>
                    <div class="edu-item">
                        <div class="edu-title">Higher Secondary Certificate (Class 12th)</div>
                        <div class="edu-info">XYZ School | 2018 - 2020</div>
                        <div class="edu-grade">Completed | Percentage: 85%</div>
                    </div>
                    <div class="edu-item">
                        <div class="edu-title">Secondary School Certificate (Class 10th)</div>
                        <div class="edu-info">XYZ School | 2018</div>
                        <div class="edu-grade">Completed | Percentage: 90%</div>
                    </div>
                </div>
            </div>

            <!-- Professional Experience -->
            <div class="content-section">
                <div class="section-heading">Professional Experience</div>
                <div class="section-text">
                    <div class="exp-item">
                        <div class="exp-description">6 months experience as Software Developer Intern at Tech Solutions Pvt. Ltd.</div>
                    </div>
                    <div class="exp-item">
                        <div class="exp-description">1 year experience as Junior Developer at Digital Innovations Company</div>
                    </div>
                </div>
            </div>

            <!-- Technical Skills -->
            <div class="content-section">
                <div class="section-heading">Technical Skills</div>
                <div class="section-text">
                    <ul class="bullet-list">
                        <li>JavaScript</li>
                        <li>React.js</li>
                        <li>Node.js</li>
                        <li>Python</li>
                        <li>SQL</li>
                        <li>HTML/CSS</li>
                        <li>Git</li>
                        <li>Problem Solving</li>
                    </ul>
                </div>
            </div>

            <!-- Core Competencies -->
            <div class="content-section">
                <div class="section-heading">Core Competencies</div>
                <div class="section-text">
                    <ul class="bullet-list">
                        <li>Strong analytical and problem-solving abilities</li>
                        <li>Excellent team collaboration and communication skills</li>
                        <li>Fast learner with adaptability to new technologies</li>
                        <li>Time management and organizational skills</li>
                        <li>Leadership and project management capabilities</li>
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
                            <span class="info-data">Mr. Robert Doe</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Date of Birth:</span>
                            <span class="info-data">15th January 2000</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Gender:</span>
                            <span class="info-data">Male</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Marital Status:</span>
                            <span class="info-data">Single</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Nationality:</span>
                            <span class="info-data">Indian</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Languages:</span>
                            <span class="info-data">English, Hindi</span>
                        </div>
                        <div class="info-row info-full">
                            <span class="info-label">Strengths:</span>
                            <span class="info-data">Self-motivated, quick learner, team player, adaptable to challenges</span>
                        </div>
                        <div class="info-row info-full">
                            <span class="info-label">Hobbies:</span>
                            <span class="info-data">Reading, Coding, Traveling, Photography, Playing Cricket</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Declaration -->
            <div class="content-section">
                <div class="section-heading">Declaration</div>
                <div class="section-text">
                    <p class="declaration-content">
                        I hereby declare that the information provided above is true and correct to the best of my knowledge and belief.
                    </p>
                    <div class="signature-area">
                        <div>
                            <div class="signature-location">Place: Kanpur</div>
                            <div class="signature-location" style="margin-top: 5px;">Date: _______________</div>
                        </div>
                        <div class="signature-name">(John Doe)</div>
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