<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Community Post - TripMate Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            min-height: 100vh;
        }

        /* Navigation */
        .header-nav {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 24px;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-section img {
            width: 40px;
            height: 40px;
        }

        .logo-text h1 {
            font-size: 20px;
            color: #1e293b;
            font-weight: 700;
        }

        .nav-links {
            display: flex;
            gap: 8px;
            list-style: none;
        }

        .nav-links a {
            padding: 8px 16px;
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .nav-links a:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        .nav-links a.active {
            background: #f1f5f9;
            color: #1e293b;
            font-weight: 600;
        }

        .user-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-info {
            text-align: right;
        }

        .user-info p {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
        }

        .user-info span {
            font-size: 12px;
            color: #64748b;
        }

        .logout-btn {
            background: #ef4444;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: background 0.2s;
        }

        .logout-btn:hover {
            background: #dc2626;
        }

        /* Main Content */
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 32px 24px;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
            font-size: 14px;
            color: #64748b;
        }

        .breadcrumb a {
            color: #3b82f6;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .page-header {
            margin-bottom: 32px;
        }

        .page-title {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 16px;
        }

        /* Card */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 32px;
        }

        /* Form */
        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #1e293b;
            font-size: 14px;
        }

        .form-group label .required {
            color: #ef4444;
            margin-left: 4px;
        }

        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s;
        }

        .form-group input[type="text"]:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .form-group small {
            display: block;
            margin-top: 6px;
            color: #64748b;
            font-size: 13px;
        }

        .char-counter {
            text-align: right;
            font-size: 12px;
            color: #94a3b8;
            margin-top: 4px;
        }

        .char-counter.warning {
            color: #f59e0b;
        }

        .char-counter.danger {
            color: #ef4444;
        }

        /* Image Upload */
        .image-upload-section {
            margin-bottom: 24px;
        }

        .file-upload-wrapper {
            margin-bottom: 16px;
        }

        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            background: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }

        .file-upload-label:hover {
            background: #f1f5f9;
            border-color: #3b82f6;
        }

        .file-upload-label i {
            font-size: 48px;
            color: #3b82f6;
            margin-bottom: 12px;
        }

        .file-upload-label span {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .file-upload-label small {
            font-size: 13px;
            color: #64748b;
        }

        .image-preview-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 12px;
            margin-top: 16px;
        }

        .preview-image-wrapper {
            position: relative;
            aspect-ratio: 1;
            border-radius: 8px;
            overflow: hidden;
            background: #f1f5f9;
        }

        .preview-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .remove-preview-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 28px;
            height: 28px;
            background: #ef4444;
            color: white;
            border: 2px solid white;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            transition: all 0.2s;
        }

        .remove-preview-btn:hover {
            background: #dc2626;
            transform: scale(1.1);
        }

        .image-inputs {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .image-input-group {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .image-input-group input {
            flex: 1;
        }

        .btn-remove {
            padding: 8px 12px;
            background: #fee2e2;
            color: #dc2626;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.2s;
        }

        .btn-remove:hover {
            background: #fecaca;
        }

        .btn-add-image {
            padding: 10px 16px;
            background: #f1f5f9;
            color: #475569;
            border: 1px dashed #cbd5e1;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-add-image:hover {
            background: #e2e8f0;
            border-color: #94a3b8;
        }

        /* Preview */
        .preview-section {
            margin-top: 32px;
            padding-top: 32px;
            border-top: 2px solid #e2e8f0;
        }

        .preview-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 16px;
        }

        .post-preview {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
        }

        .preview-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .preview-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 16px;
        }

        .preview-author {
            flex: 1;
        }

        .preview-author-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 14px;
        }

        .preview-author-badge {
            display: inline-block;
            padding: 2px 8px;
            background: #dbeafe;
            color: #1e40af;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 6px;
        }

        .preview-date {
            font-size: 12px;
            color: #94a3b8;
        }

        .preview-post-title {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 12px;
        }

        .preview-post-description {
            color: #475569;
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .preview-images {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 12px;
            margin-bottom: 16px;
        }

        .preview-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e2e8f0;
        }

        .preview-image.broken {
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-size: 12px;
        }

        .preview-stats {
            display: flex;
            gap: 24px;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
            font-size: 14px;
            color: #64748b;
        }

        .preview-stat {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Alert */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .alert-info {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #93c5fd;
        }

        /* Buttons */
        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 32px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
        }

        .btn-primary:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
        }

        .btn-secondary {
            background: white;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background: #f8fafc;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .nav-links {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .card {
                padding: 24px 20px;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .preview-images {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header Navigation -->
    <header class="header-nav">
        <div class="nav-container">
            <div class="logo-section">
                <img src="{{ asset('image/tripmate.png') }}" alt="TripMate logo">
                <div class="logo-text">
                    <h1>TripMate Admin</h1>
                </div>
            </div>

            <ul class="nav-links">
                <li><a href="/admin/dashboards">Dashboard</a></li>
                <li><a href="/admin/MemberManagement">Members</a></li>
                <li><a href="/admin/CommunityManagement" class="active">Community</a></li>
                <li><a href="/admin/BusinessManagement">Business</a></li>
            </ul>

            <div class="user-section">
                <div class="user-info">
                    <p>{{ $adminName ?? 'Admin User' }}</p>
                    <span>Administrator</span>
                </div>
                <button class="logout-btn" onclick="logout()">Logout</button>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="breadcrumb">
            <a href="/admin/CommunityManagement">Community Management</a>
            <span>/</span>
            <span>Create Post</span>
        </div>

        <div class="page-header">
            <h1 class="page-title">Create Community Post</h1>
            <p class="page-subtitle">Share updates, announcements, or tips as TripMate Admin</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <span>This post will be published as <strong>TripMate Admin</strong> and will be visible to all users in the community.</span>
        </div>

        <div class="card">
            <form action="{{ route('admin.community.store') }}" method="POST" id="createPostForm" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="title">
                        Post Title
                        <span class="required">*</span>
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        maxlength="100"
                        required
                        placeholder="Enter an engaging title..."
                        value="{{ old('title') }}"
                        oninput="updatePreview()"
                    >
                    <div class="char-counter" id="titleCounter">0 / 100 characters</div>
                </div>

                <div class="form-group">
                    <label for="description">
                        Description
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        maxlength="255"
                        placeholder="Share details, tips, or information..."
                        oninput="updatePreview()"
                    >{{ old('description') }}</textarea>
                    <div class="char-counter" id="descCounter">0 / 255 characters</div>
                    <small>Optional: Add more context or details to your post</small>
                </div>

                <div class="image-upload-section">
                    <label>
                        Images (Optional)
                    </label>
                    <small style="display: block; margin-bottom: 12px; color: #64748b;">
                        Upload up to 4 images from your computer (JPG, PNG, GIF - Max 5MB each)
                    </small>
                    <div class="file-upload-wrapper">
                        <input
                            type="file"
                            name="images[]"
                            id="imageUpload"
                            accept="image/jpeg,image/png,image/gif,image/jpg"
                            multiple
                            onchange="previewUploadedImages(this)"
                            style="display: none;"
                        >
                        <label for="imageUpload" class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Click to browse images from your PC</span>
                            <small>Or drag and drop images here</small>
                        </label>
                    </div>
                    <div id="imagePreviewContainer" class="image-preview-container"></div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-paper-plane"></i>
                        Publish Post
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="goBack()">
                        <i class="fas fa-times"></i>
                        Cancel
                    </button>
                </div>
            </form>

            <!-- Preview Section -->
            <div class="preview-section">
                <h3 class="preview-title">Post Preview</h3>
                <div class="post-preview">
                    <div class="preview-header">
                        <div class="preview-avatar">T</div>
                        <div class="preview-author">
                            <div>
                                <span class="preview-author-name">TripMate Admin</span>
                                <span class="preview-author-badge">OFFICIAL</span>
                            </div>
                            <div class="preview-date">Just now</div>
                        </div>
                    </div>

                    <div id="previewContent">
                        <div class="preview-post-title" id="previewTitle">
                            Your title will appear here...
                        </div>
                        <div class="preview-post-description" id="previewDescription" style="display: none;">
                            Your description will appear here...
                        </div>
                        <div class="preview-images" id="previewImages"></div>
                    </div>

                    <div class="preview-stats">
                        <div class="preview-stat">
                            <i class="fas fa-heart"></i>
                            <span>0 likes</span>
                        </div>
                        <div class="preview-stat">
                            <i class="fas fa-comment"></i>
                            <span>0 comments</span>
                        </div>
                        <div class="preview-stat">
                            <i class="fas fa-eye"></i>
                            <span>Visible to all</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let imageCount = 1;
        const maxImages = 4;
        let selectedFiles = [];

        // Character counter
        const titleInput = document.getElementById('title');
        const descInput = document.getElementById('description');
        const titleCounter = document.getElementById('titleCounter');
        const descCounter = document.getElementById('descCounter');

        titleInput.addEventListener('input', function() {
            const count = this.value.length;
            titleCounter.textContent = `${count} / 100 characters`;

            if (count > 90) {
                titleCounter.classList.add('danger');
                titleCounter.classList.remove('warning');
            } else if (count > 75) {
                titleCounter.classList.add('warning');
                titleCounter.classList.remove('danger');
            } else {
                titleCounter.classList.remove('warning', 'danger');
            }
        });

        descInput.addEventListener('input', function() {
            const count = this.value.length;
            descCounter.textContent = `${count} / 255 characters`;

            if (count > 230) {
                descCounter.classList.add('danger');
                descCounter.classList.remove('warning');
            } else if (count > 200) {
                descCounter.classList.add('warning');
                descCounter.classList.remove('danger');
            } else {
                descCounter.classList.remove('warning', 'danger');
            }
        });

        // File upload handling
        function previewUploadedImages(input) {
            const files = Array.from(input.files);
            const container = document.getElementById('imagePreviewContainer');
            const previewImages = document.getElementById('previewImages');

            // Check file count
            if (files.length > maxImages) {
                alert(`You can only upload up to ${maxImages} images`);
                input.value = '';
                return;
            }

            // Validate files
            for (let file of files) {
                if (file.size > 5 * 1024 * 1024) {
                    alert(`File "${file.name}" is too large. Maximum size is 5MB`);
                    input.value = '';
                    return;
                }

                if (!file.type.match('image.*')) {
                    alert(`File "${file.name}" is not an image`);
                    input.value = '';
                    return;
                }
            }

            // Clear previous previews
            container.innerHTML = '';
            previewImages.innerHTML = '';
            selectedFiles = files;

            // Show previews
            files.forEach((file, index) => {
                const reader = new FileReader();

                reader.onload = function(e) {
                    // Preview in upload section
                    const wrapper = document.createElement('div');
                    wrapper.className = 'preview-image-wrapper';
                    wrapper.innerHTML = `
                        <img src="${e.target.result}" alt="Preview ${index + 1}">
                        <button type="button" class="remove-preview-btn" onclick="removePreviewImage(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    container.appendChild(wrapper);

                    // Preview in post preview section
                    const previewImg = document.createElement('img');
                    previewImg.src = e.target.result;
                    previewImages.appendChild(previewImg);
                };

                reader.readAsDataURL(file);
            });
        }

        function removePreviewImage(index) {
            const fileInput = document.getElementById('imageUpload');
            const dt = new DataTransfer();

            Array.from(fileInput.files).forEach((file, i) => {
                if (i !== index) {
                    dt.items.add(file);
                }
            });

            fileInput.files = dt.files;
            previewUploadedImages(fileInput);
        }

        // Drag and drop support
        const uploadLabel = document.querySelector('.file-upload-label');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadLabel.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadLabel.addEventListener(eventName, () => {
                uploadLabel.style.borderColor = '#3b82f6';
                uploadLabel.style.background = '#eff6ff';
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadLabel.addEventListener(eventName, () => {
                uploadLabel.style.borderColor = '#cbd5e1';
                uploadLabel.style.background = '#f8fafc';
            });
        });

        uploadLabel.addEventListener('drop', function(e) {
            const files = e.dataTransfer.files;
            const fileInput = document.getElementById('imageUpload');
            fileInput.files = files;
            previewUploadedImages(fileInput);
        });

        // Preview update
        function updatePreview() {
            const title = document.getElementById('title').value;
            const description = document.getElementById('description').value;

            // Update title
            document.getElementById('previewTitle').textContent = title || 'Your title will appear here...';

            // Update description
            const descElement = document.getElementById('previewDescription');
            if (description) {
                descElement.textContent = description;
                descElement.style.display = 'block';
            } else {
                descElement.style.display = 'none';
            }
        }

        // Form submission
        document.getElementById('createPostForm').addEventListener('submit', function(e) {
            const title = document.getElementById('title').value.trim();

            if (!title) {
                e.preventDefault();
                alert('Please enter a title for your post');
                return;
            }

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Publishing...';
        });

        function goBack() {
            if (confirm('Are you sure? Any unsaved changes will be lost.')) {
                window.location.href = '/admin/CommunityManagement';
            }
        }

        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = "{{ url('/logout') }}";
            }
        }

        // Initialize preview
        updatePreview();
    </script>
</body>
</html>
