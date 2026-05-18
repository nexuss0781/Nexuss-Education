<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexuss Education - AI-Powered Study Companion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://js.puter.com/v2/"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 400px;
            --sidebar-min: 250px;
            --sidebar-max: 800px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            overflow: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .app-container {
            display: flex;
            height: 100vh;
            width: 100vw;
        }
        
        .sidebar {
            width: var(--sidebar-width);
            min-width: var(--sidebar-min);
            max-width: var(--sidebar-max);
            background: #ffffff;
            display: flex;
            flex-direction: column;
            position: relative;
            transition: width 0.1s ease;
        }
        
        .main-content {
            flex: 1;
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .resize-handle {
            width: 8px;
            background: linear-gradient(180deg, #e2e8f0 0%, #cbd5e1 50%, #e2e8f0 100%);
            cursor: col-resize;
            position: relative;
            z-index: 10;
            transition: background 0.2s;
        }
        
        .resize-handle:hover,
        .resize-handle.resizing {
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
        }
        
        .resize-handle::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 2px;
            height: 30px;
            background: rgba(0,0,0,0.2);
            border-radius: 1px;
        }
        
        .chat-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .message {
            max-width: 85%;
            padding: 1rem 1.25rem;
            border-radius: 1rem;
            line-height: 1.5;
            animation: messageSlide 0.3s ease-out;
        }
        
        @keyframes messageSlide {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .message.user {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 0.25rem;
        }
        
        .message.ai {
            background: white;
            color: #1e293b;
            align-self: flex-start;
            border-bottom-left-radius: 0.25rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        .message.system {
            background: #fef3c7;
            color: #92400e;
            align-self: center;
            font-size: 0.875rem;
            border-radius: 0.5rem;
        }
        
        .chat-input-area {
            padding: 1.5rem;
            background: white;
            border-top: 1px solid #e2e8f0;
        }
        
        .chat-input-wrapper {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }
        
        .chat-input {
            flex: 1;
            padding: 0.875rem 1.25rem;
            border: 2px solid #e2e8f0;
            border-radius: 1.5rem;
            outline: none;
            transition: all 0.2s;
            font-size: 0.95rem;
        }
        
        .chat-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .send-btn {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        .send-btn:hover:not(:disabled) {
            transform: scale(1.05);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
        }
        
        .send-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .pdf-container {
            flex: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        
        .pdf-toolbar {
            background: white;
            padding: 0.75rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        
        .pdf-viewer {
            flex: 1;
            overflow: auto;
            background: #525659;
            display: flex;
            justify-content: center;
            padding: 2rem;
        }
        
        .pdf-viewer iframe {
            width: 100%;
            height: 100%;
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        
        .book-list {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
        }
        
        .book-item {
            padding: 1rem;
            background: #f8fafc;
            border-radius: 0.75rem;
            margin-bottom: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid transparent;
        }
        
        .book-item:hover {
            background: white;
            border-color: #667eea;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        }
        
        .book-item.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .upload-zone {
            border: 2px dashed #cbd5e1;
            border-radius: 0.75rem;
            padding: 2rem 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            margin-bottom: 1rem;
        }
        
        .upload-zone:hover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.05);
        }
        
        .upload-zone.dragover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.1);
        }
        
        .page-indicator {
            background: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            color: #64748b;
        }
        
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .typing-indicator {
            display: flex;
            gap: 4px;
            padding: 8px;
        }
        
        .typing-indicator span {
            width: 8px;
            height: 8px;
            background: #667eea;
            border-radius: 50%;
            animation: typing 1.4s infinite;
        }
        
        .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
        .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }
        
        @keyframes typing {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-10px); }
        }

        /* Onboarding Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            backdrop-filter: blur(4px);
        }
        
        .modal-overlay.active {
            display: flex;
        }
        
        .modal-content {
            background: white;
            border-radius: 1.5rem;
            padding: 2.5rem;
            max-width: 500px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        .modal-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
        }
        
        .modal-text {
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        
        .cta-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            border-radius: 1rem;
            border: none;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            width: 100%;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.25rem;
            font-weight: 700;
        }
        
        .logo-icon {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #667eea;
        }

        .page-canvas-container {
            display: none;
        }
    </style>
</head>
<body>
    <!-- Onboarding Modal -->
    <div class="modal-overlay" id="onboardingModal">
        <div class="modal-content">
            <div class="logo" style="justify-content: center; margin-bottom: 1.5rem;">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <span style="color: #667eea;">Nexuss Education</span>
            </div>
            <h2 class="modal-title">Welcome to Your AI Study Companion</h2>
            <p class="modal-text">
                Get personalized help while studying your PDFs. Our AI can see the current page 
                you're reading and provide contextual explanations, summaries, and answers to your questions.
            </p>
            <button class="cta-button" id="authButton">
                <i class="fas fa-sign-in-alt"></i> Continue with Puter.js
            </button>
        </div>
    </div>

    <div class="app-container">
        <!-- Sidebar - Chat -->
        <div class="sidebar" id="sidebar">
            <div class="chat-header">
                <div class="logo">
                    <div class="logo-icon" style="width: 32px; height: 32px;">
                        <i class="fas fa-graduation-cap" style="font-size: 0.9rem;"></i>
                    </div>
                    <span>Nexuss Education</span>
                </div>
                <div id="userInfo" style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
                    <i class="fas fa-user-circle"></i>
                    <span id="userName"></span>
                </div>
            </div>
            
            <div class="chat-messages" id="chatMessages">
                <div class="message system">
                    👋 Welcome! I'm your AI study assistant. Select a PDF and I'll help you understand it better. 
                    I can see the page you're currently viewing!
                </div>
            </div>
            
            <div class="chat-input-area">
                <div class="chat-input-wrapper">
                    <input 
                        type="text" 
                        class="chat-input" 
                        id="chatInput" 
                        placeholder="Ask about the current page..."
                        autocomplete="off"
                    >
                    <button class="send-btn" id="sendBtn" disabled>
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Resize Handle -->
        <div class="resize-handle" id="resizeHandle"></div>
        
        <!-- Main Content - PDF Viewer -->
        <div class="main-content">
            <div class="pdf-container">
                <div class="pdf-toolbar">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <select id="bookSelect" class="chat-input" style="width: 250px; padding: 0.5rem 1rem;">
                            <option value="">Select a PDF...</option>
                        </select>
                        <label for="fileUpload" class="upload-zone" style="padding: 0.5rem 1rem; margin: 0; cursor: pointer;">
                            <i class="fas fa-upload"></i> Upload PDF
                        </label>
                        <input type="file" id="fileUpload" accept=".pdf" style="display: none;">
                    </div>
                    <div class="page-indicator" id="pageIndicator">
                        Page: <span id="currentPage">-</span>
                    </div>
                </div>
                
                <div class="pdf-viewer" id="pdfViewer">
                    <iframe id="pdfFrame" src=""></iframe>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Hidden canvas for page capture -->
    <canvas id="pageCanvas" class="page-canvas-container"></canvas>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        // Initialize PDF.js worker
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
        
        // State
        let currentUser = null;
        let currentPdf = null;
        let currentPageNum = 1;
        let totalPages = 0;
        let pdfDoc = null;
        
        // DOM Elements
        const onboardingModal = document.getElementById('onboardingModal');
        const authButton = document.getElementById('authButton');
        const chatMessages = document.getElementById('chatMessages');
        const chatInput = document.getElementById('chatInput');
        const sendBtn = document.getElementById('sendBtn');
        const resizeHandle = document.getElementById('resizeHandle');
        const sidebar = document.getElementById('sidebar');
        const bookSelect = document.getElementById('bookSelect');
        const fileUpload = document.getElementById('fileUpload');
        const pdfFrame = document.getElementById('pdfFrame');
        const currentPageEl = document.getElementById('currentPage');
        const userNameEl = document.getElementById('userName');
        const pageCanvas = document.getElementById('pageCanvas');
        
        // Check authentication on load
        async function checkAuth() {
            try {
                const user = await puter.auth.getUser();
                if (user) {
                    currentUser = user;
                    userNameEl.textContent = user.username || user.email;
                    onboardingModal.classList.remove('active');
                    loadBooks();
                } else {
                    onboardingModal.classList.add('active');
                }
            } catch (error) {
                console.error('Auth error:', error);
                onboardingModal.classList.add('active');
            }
        }
        
        // Authentication
        authButton.addEventListener('click', async () => {
            try {
                authButton.innerHTML = '<span class="loading-spinner"></span> Authenticating...';
                authButton.disabled = true;
                
                const user = await puter.auth.signIn();
                currentUser = user;
                userNameEl.textContent = user.username || user.email;
                onboardingModal.classList.remove('active');
                
                addMessage('system', `✅ Welcome, ${user.username || user.email}! You're now authenticated.`);
                loadBooks();
            } catch (error) {
                console.error('Authentication error:', error);
                authButton.innerHTML = '<i class="fas fa-sign-in-alt"></i> Continue with Puter.js';
                authButton.disabled = false;
                addMessage('system', '❌ Authentication failed. Please try again.');
            }
        });
        
        // Load books
        async function loadBooks() {
            try {
                const response = await fetch('api.php?action=list');
                const data = await response.json();
                
                if (data.success) {
                    bookSelect.innerHTML = '<option value="">Select a PDF...</option>';
                    data.books.forEach(book => {
                        const option = document.createElement('option');
                        option.value = book.path;
                        option.textContent = book.name;
                        bookSelect.appendChild(option);
                    });
                    
                    if (data.books.length > 0) {
                        addMessage('system', `📚 Found ${data.books.length} PDF(s) in your library.`);
                    }
                }
            } catch (error) {
                console.error('Error loading books:', error);
            }
        }
        
        // Book selection
        bookSelect.addEventListener('change', async (e) => {
            const path = e.target.value;
            if (!path) return;
            
            pdfFrame.src = path + '#toolbar=0';
            addMessage('system', `📖 Loaded: ${path.split('/').pop()}`);
            
            // Load PDF for page extraction
            try {
                const response = await fetch(path);
                const arrayBuffer = await response.arrayBuffer();
                pdfDoc = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;
                totalPages = pdfDoc.numPages;
                currentPageNum = 1;
                updatePageIndicator();
                
                // Capture first page
                await captureCurrentPage();
            } catch (error) {
                console.error('Error loading PDF:', error);
            }
        });
        
        // File upload
        fileUpload.addEventListener('change', async (e) => {
            const file = e.target.files[0];
            if (!file) return;
            
            const formData = new FormData();
            formData.append('pdf_upload', file);
            
            try {
                const response = await fetch('api.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                
                if (data.success) {
                    addMessage('system', `✅ Uploaded: ${data.filename}`);
                    loadBooks();
                    bookSelect.value = data.path;
                    bookSelect.dispatchEvent(new Event('change'));
                } else {
                    addMessage('system', `❌ ${data.error}`);
                }
            } catch (error) {
                console.error('Upload error:', error);
                addMessage('system', '❌ Upload failed.');
            }
            
            e.target.value = '';
        });
        
        // Drag and drop
        const dropZone = document.querySelector('.upload-zone');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        dropZone.addEventListener('dragenter', () => dropZone.classList.add('dragover'));
        dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
        dropZone.addEventListener('drop', handleDrop);
        
        async function handleDrop(e) {
            dropZone.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length && files[0].type === 'application/pdf') {
                fileUpload.files = files;
                fileUpload.dispatchEvent(new Event('change'));
            }
        }
        
        // Sidebar resize
        let isResizing = false;
        
        resizeHandle.addEventListener('mousedown', (e) => {
            isResizing = true;
            resizeHandle.classList.add('resizing');
            document.body.style.cursor = 'col-resize';
        });
        
        document.addEventListener('mousemove', (e) => {
            if (!isResizing) return;
            
            const newWidth = Math.max(250, Math.min(800, e.clientX));
            sidebar.style.width = newWidth + 'px';
        });
        
        document.addEventListener('mouseup', () => {
            isResizing = false;
            resizeHandle.classList.remove('resizing');
            document.body.style.cursor = '';
        });
        
        // Chat functionality
        chatInput.addEventListener('input', () => {
            sendBtn.disabled = !chatInput.value.trim();
        });
        
        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !sendBtn.disabled) {
                sendMessage();
            }
        });
        
        sendBtn.addEventListener('click', sendMessage);
        
        async function sendMessage() {
            const message = chatInput.value.trim();
            if (!message || !currentUser) return;
            
            addMessage('user', message);
            chatInput.value = '';
            sendBtn.disabled = true;
            
            // Capture current page
            const pageImage = await captureCurrentPage();
            
            // Show typing indicator
            const typingDiv = document.createElement('div');
            typingDiv.className = 'message ai';
            typingDiv.id = 'typingIndicator';
            typingDiv.innerHTML = `
                <div class="typing-indicator">
                    <span></span><span></span><span></span>
                </div>
            `;
            chatMessages.appendChild(typingDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
            
            try {
                // Prepare context
                const systemPrompt = `You are an expert educational AI assistant helping students understand academic content. 
You are currently viewing page ${currentPageNum} of ${totalPages || '?'} from a PDF document.
Your role is to:
- Explain complex concepts clearly and concisely
- Provide context-aware answers based on the visible page
- Help with note-taking and understanding key points
- Break down difficult topics into digestible parts
- Encourage critical thinking and deeper understanding

Be friendly, supportive, and educational. Keep responses focused and not overwhelming.`;

                let aiResponse;
                
                if (pageImage) {
                    // Use vision model with image
                    const response = await puter.ai.chat(
                        [
                            {
                                role: "system",
                                content: systemPrompt
                            },
                            {
                                role: "user",
                                content: [
                                    {
                                        type: "image",
                                        url: pageImage
                                    },
                                    {
                                        type: "text",
                                        text: `Question about this page: ${message}`
                                    }
                                ]
                            }
                        ],
                        { model: 'gpt-4o' }
                    );
                    aiResponse = response.message.content;
                } else {
                    // Text-only fallback
                    const response = await puter.ai.chat(
                        [
                            {
                                role: "system",
                                content: systemPrompt
                            },
                            {
                                role: "user",
                                content: `I'm on page ${currentPageNum}. ${message}`
                            }
                        ],
                        { model: 'gpt-4o' }
                    );
                    aiResponse = response.message.content;
                }
                
                // Remove typing indicator
                document.getElementById('typingIndicator')?.remove();
                
                // Format response (basic markdown)
                const formattedResponse = formatResponse(aiResponse);
                addMessage('ai', formattedResponse, true);
                
            } catch (error) {
                console.error('AI error:', error);
                document.getElementById('typingIndicator')?.remove();
                addMessage('system', '❌ Failed to get AI response. Please try again.');
            }
        }
        
        function addMessage(type, content, isHtml = false) {
            const div = document.createElement('div');
            div.className = `message ${type}`;
            
            if (isHtml) {
                div.innerHTML = content;
            } else {
                div.textContent = content;
            }
            
            chatMessages.appendChild(div);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        function formatResponse(text) {
            // Basic markdown formatting
            return text
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.*?)\*/g, '<em>$1</em>')
                .replace(/`(.*?)`/g, '<code style="background: rgba(0,0,0,0.1); padding: 2px 6px; border-radius: 3px;">$1</code>')
                .replace(/\n\n/g, '<br><br>')
                .replace(/\n/g, '<br>');
        }
        
        // Capture current page as image
        async function captureCurrentPage() {
            if (!pdfDoc || !currentPageNum) return null;
            
            try {
                const page = await pdfDoc.getPage(currentPageNum);
                const viewport = page.getViewport({ scale: 1.5 });
                
                pageCanvas.width = viewport.width;
                pageCanvas.height = viewport.height;
                
                const ctx = pageCanvas.getContext('2d');
                await page.render({
                    canvasContext: ctx,
                    viewport: viewport
                }).promise;
                
                return pageCanvas.toDataURL('image/jpeg', 0.8);
            } catch (error) {
                console.error('Error capturing page:', error);
                return null;
            }
        }
        
        function updatePageIndicator() {
            currentPageEl.textContent = `${currentPageNum} / ${totalPages}`;
        }
        
        // Listen for page changes (polling URL hash for PDF viewer)
        let lastKnownUrl = '';
        setInterval(async () => {
            if (pdfFrame.src && pdfFrame.src !== lastKnownUrl) {
                lastKnownUrl = pdfFrame.src;
                // PDF loaded/reloaded
                if (pdfDoc) {
                    currentPageNum = 1;
                    updatePageIndicator();
                    await captureCurrentPage();
                }
            }
        }, 1000);
        
        // Keyboard navigation for pages
        document.addEventListener('keydown', (e) => {
            if (!pdfDoc) return;
            
            if (e.key === 'ArrowRight' && currentPageNum < totalPages) {
                currentPageNum++;
                updatePageIndicator();
                captureCurrentPage();
            } else if (e.key === 'ArrowLeft' && currentPageNum > 1) {
                currentPageNum--;
                updatePageIndicator();
                captureCurrentPage();
            }
        });
        
        // Initialize
        checkAuth();
    </script>
</body>
</html>
