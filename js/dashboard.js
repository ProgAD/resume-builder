// Delete Modal Functions
function showDeleteModal(name, id) {
    currentResumeName = name;
    document.getElementById('deleteMessage').textContent = 
        `Are you sure you want to delete "${name}'s" resume? This action cannot be undone.`;
    document.getElementById('deleteModal').classList.add('show');
    const dltBtn = document.getElementById('deleteConfirmBtn');
    dltBtn.onclick = function() {
        confirmDelete(id);
    }
}

function confirmDelete(id) {
    fetch('actions/resume/delete_resume.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ deleteId: id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the resume element from the DOM
            const resumeElement = document.getElementById(`resume-${id}`);
            if (resumeElement) {
                // Add burst effect before removal
                resumeElement.style.transition = "all 0.3s ease";
                resumeElement.style.transform = "scale(0.8)";
                resumeElement.style.opacity = "0";
                setTimeout(() => {
                    resumeElement.remove();
                }, 300);
            }
            showToast(`Resume has been deleted.`);
            closeDeleteModal();
            // In real app: Delete the resume from database
        } else {
            alert(`Error: ${data.message}`);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(`Error: ${error.message}`);
    });
}

// Close modals when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal-overlay')) {
        event.target.classList.remove('show');
        currentResumeName = '';
    }
}


function handleShare(id) {
    const shareLink = `http://localhost/simple-resume/resume.php?id=${id}`;

    navigator.clipboard.writeText(shareLink)
        .then(() => showToast("Link copied to clipboard!"))
        .catch(() => {
            // fallback
            const textarea = document.createElement("textarea");
            textarea.value = shareLink;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand("copy");
            document.body.removeChild(textarea);
            showToast("Link copied to clipboard!");
        });
}

function showToast(message) {
    // create toast element
    const toast = document.createElement("div");
    toast.innerText = message;
    toast.style.position = "fixed";
    toast.style.bottom = "30px";
    toast.style.left = "50%";
    toast.style.transform = "translateX(-50%)";
    toast.style.background = "#333";
    toast.style.color = "#fff";
    toast.style.padding = "10px 20px";
    toast.style.borderRadius = "8px";
    toast.style.boxShadow = "0 2px 10px rgba(0,0,0,0.3)";
    toast.style.zIndex = "9999";
    toast.style.opacity = "0";
    toast.style.transition = "opacity 0.4s ease";

    document.body.appendChild(toast);

    // fade in
    setTimeout(() => {
        toast.style.opacity = "1";
    }, 100);

    // remove after 2.5s
    setTimeout(() => {
        toast.style.opacity = "0";
        setTimeout(() => toast.remove(), 400);
    }, 2500);
}