# 🎓 Nexuss Education

**AI-Powered Study Companion with Intelligent PDF Analysis**

[![License: MIT](https://img.shields.io/badge/License-MIT-purple.svg)](https://opensource.org/licenses/MIT)
[![PHP](https://img.shields.io/badge/PHP-8.0+-blue.svg)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.0-38b2ac.svg)](https://tailwindcss.com)
[![Puter.js](https://img.shields.io/badge/Puter.js-AI%20Powered-green.svg)](https://puter.com)

A modern, production-ready web application that combines a custom PDF viewer with an AI chat assistant. Built for students and educators who want intelligent, contextual help while studying PDF documents.

![Nexuss Education Demo](https://via.placeholder.com/1200x600/7c3aed/ffffff?text=Nexuss+Education+Dashboard)

## ✨ Features

### 🎨 Modern UI/UX
- **Dual Theme Support**: Beautiful light and dark modes with smooth transitions
- **Responsive Design**: Works seamlessly on desktop, tablet, and mobile devices
- **Adjustable Sidebar**: Drag-to-resize panel for personalized workspace
- **Custom Scrollbars**: Polished scrolling experience in both themes
- **Smooth Animations**: Subtle transitions and loading states

### 📄 Advanced PDF Viewer
- **Custom Canvas Rendering**: Fast, native PDF rendering (no browser plugin)
- **Page Navigation**: Previous/Next buttons with page counter
- **Zoom Controls**: Zoom in/out from 60% to 300%
- **High-Quality Capture**: 2x scale page capture for AI analysis
- **Drag & Drop Upload**: Intuitive file upload interface

### 🤖 AI-Powered Assistance
- **Vision Integration**: AI sees the current PDF page you're viewing
- **Contextual Responses**: Answers based on what you're reading
- **Markdown Support**: Formatted responses with code blocks, lists, and more
- **Study-Focused Prompts**: Designed to educate without overwhelming
- **GPT-4o Model**: State-of-the-art AI via Puter.js

### 🔒 Security & Performance
- **Aggressive Caching**: 1-year immutable cache for PDF files
- **Secure File Uploads**: Sanitized filenames, type validation
- **CORS Protection**: Proper headers for API security
- **XSS Prevention**: HTML escaping in markdown parser
- **Content-Type Sniffing Protection**: Security headers enabled

## 🚀 Quick Start

### Prerequisites
- PHP 8.0 or higher
- Web server (Apache, Nginx, or built-in PHP server)
- Modern web browser with JavaScript enabled

### Installation

1. **Clone the repository**
```bash
git clone https://github.com/nexuss0781/Nexuss-Education.git
cd Nexuss-Education
```

2. **Set permissions** (Linux/Mac)
```bash
chmod 755 books/
```

3. **Start the development server**
```bash
php -S localhost:8000
```

4. **Open in browser**
Navigate to `http://localhost:8000`

5. **Authenticate**
Click "Continue with Puter.js" to sign in and start using the AI features

## 📖 Usage Guide

### Uploading PDFs
1. Click the **Upload** button or drag & drop a PDF file
2. Wait for the upload confirmation
3. Select your PDF from the dropdown menu

### Navigating PDFs
- Use **← →** buttons to move between pages
- Click **+ / -** to zoom in/out
- Scroll naturally within the PDF viewer

### Asking Questions
1. Navigate to the page you want to ask about
2. Type your question in the chat input
3. Press Enter or click the send button
4. The AI will analyze the current page and respond

### Dark Mode
Click the moon/sun icon in the header to toggle between light and dark themes. Your preference is saved automatically.

## 🏗️ Architecture

```
Nexuss-Education/
├── index.php          # Main application (frontend + logic)
├── api.php            # Backend API (uploads, file serving)
├── books/             # PDF storage directory
├── README.md          # Documentation
└── .gitignore         # Git ignore rules
```

### Technology Stack
- **Frontend**: Pure HTML5, Vanilla JavaScript, TailwindCSS
- **Backend**: PHP 8.0+
- **PDF Rendering**: PDF.js (Mozilla)
- **AI Integration**: Puter.js (GPT-4o with Vision)
- **Authentication**: Puter Auth

## 🔧 Configuration

### Environment Variables (Optional)
No environment variables required. All configuration is handled via Puter.js authentication.

### Customization

#### Change Primary Colors
Edit the Tailwind config in `index.php`:
```javascript
colors: {
    primary: {
        500: '#8b5cf6',  // Change this
        600: '#7c3aed',  // And this
    }
}
```

#### Adjust Sidebar Width
Modify CSS variables in `index.php`:
```css
:root {
    --sidebar-width: 450px;
    --sidebar-min: 300px;
    --sidebar-max: 700px;
}
```

#### Change AI Model
Update the model parameter in the chat function:
```javascript
const response = await puter.ai.chat(messages, { model: 'gpt-4o' });
```

## 🌐 Deployment

### Shared Hosting (cPanel, Plesk)
1. Upload all files to your `public_html` directory
2. Ensure `books/` folder has write permissions (755)
3. Access via your domain

### VPS/Dedicated Server (Nginx)
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/nexuss-education;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    location /books/ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

### Docker
```dockerfile
FROM php:8.0-apache

WORKDIR /var/www/html

COPY . .

RUN chmod 755 books/

EXPOSE 80

CMD ["apache2-foreground"]
```

Build and run:
```bash
docker build -t nexuss-education .
docker run -p 8000:80 nexuss-education
```

## 🧪 Testing Checklist

- [ ] Upload PDF via button
- [ ] Upload PDF via drag & drop
- [ ] Navigate between pages
- [ ] Zoom in/out functionality
- [ ] Send message to AI
- [ ] Verify AI sees current page
- [ ] Toggle dark/light mode
- [ ] Resize sidebar
- [ ] Test on mobile device
- [ ] Verify caching headers

## 🔐 Security Considerations

### Implemented
✅ File type validation (PDF only)  
✅ Filename sanitization  
✅ CORS headers configured  
✅ XSS prevention in markdown  
✅ Content-Type sniffing protection  
✅ Immutable cache headers  

### Recommendations for Production
- Enable HTTPS (SSL/TLS)
- Add rate limiting for API endpoints
- Implement file size limits
- Set up regular backups of uploaded PDFs
- Monitor disk space usage
- Add user quota system if needed

## 🎯 Browser Compatibility

| Browser | Version | Status |
|---------|---------|--------|
| Chrome | 90+ | ✅ Full Support |
| Firefox | 88+ | ✅ Full Support |
| Safari | 14+ | ✅ Full Support |
| Edge | 90+ | ✅ Full Support |
| Opera | 76+ | ✅ Full Support |

## 📝 Roadmap

### Phase 1 (Current)
- ✅ Custom PDF viewer
- ✅ AI vision integration
- ✅ Dark/Light themes
- ✅ Responsive design

### Phase 2 (Planned)
- [ ] Note-taking feature
- [ ] Highlight annotations
- [ ] Export chat conversations
- [ ] Multiple PDF tabs

### Phase 3 (Future)
- [ ] Collaborative study rooms
- [ ] Quiz generation from PDFs
- [ ] Spaced repetition flashcards
- [ ] Mobile app (React Native)

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see below for details:

```
MIT License

Copyright (c) 2024 Nexuss Education

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

## 🙏 Acknowledgments

- [Puter.js](https://puter.com) - AI and Authentication platform
- [PDF.js](https://mozilla.github.io/pdf.js/) - Mozilla's PDF rendering library
- [TailwindCSS](https://tailwindcss.com) - Utility-first CSS framework
- [Font Awesome](https://fontawesome.com) - Icon library

## 📞 Support

For issues, questions, or suggestions:
- Open an issue on [GitHub](https://github.com/nexuss0781/Nexuss-Education/issues)
- Email: nexuss0781@gmail.com

---

**Built with ❤️ for students and lifelong learners**

*Last Updated: 2024*
