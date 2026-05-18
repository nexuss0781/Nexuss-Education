# 🎓 Nexuss Education

**AI-Powered PDF Study Companion** - Transform your learning experience with intelligent, context-aware assistance.

![Nexuss Education](https://img.shields.io/badge/Nexuss-Education-blue?style=for-the-badge)
![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.0-38B2AC?style=for-the-badge&logo=tailwindcss&logoColor=white)
![Puter.js](https://img.shields.io/badge/Puter.js-AI%20Powered-FF6B6B?style=for-the-badge)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

---

## 🌟 Overview

Nexuss Education is a **world-class study assistant** that combines PDF viewing with AI-powered chat. Built for students, researchers, and lifelong learners, it provides real-time, page-specific insights without overwhelming you with information.

### ✨ Key Features

- **📚 Split-View Interface**: Adjustable sidebar with PDF viewer on the right and modern chat on the left
- **🧠 Context-Aware AI**: Automatically captures the current PDF page and sends it as an image to the AI model
- **🎯 Smart System Prompt**: Educational-focused AI that provides concise, relevant answers tailored to your current study material
- **🔐 Secure Authentication**: Puter.js integration with persistent user sessions
- **📤 Easy Upload**: Drag-and-drop PDF uploads stored securely in `/books/` directory
- **🎨 Modern UI**: Beautiful, responsive design powered by Tailwind CSS
- **⚡ Pure PHP Backend**: Lightweight, fast, and easy to deploy

---

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

2. **Start the development server**
   ```bash
   php -S localhost:8000
   ```

3. **Open in browser**
   Navigate to `http://localhost:8000`

4. **Authenticate with Puter.js**
   - Click "Connect with Puter" on first load
   - Complete the onboarding flow
   - Your session will be retained automatically

---

## 📖 Usage Guide

### Studying with Nexuss

1. **Upload a PDF**: Click the upload button or drag-and-drop a PDF file
2. **Navigate Pages**: Use the page controls to move through your document
3. **Ask Questions**: Type questions in the chat about the current page
4. **Get Smart Answers**: The AI sees exactly what you're reading and provides contextual help

### Example Workflow

```
Student opens research paper → Navigates to page 4 (Methodology section)
→ Asks: "What statistical method did they use?"
→ AI analyzes page 4 image → Returns: "They used ANOVA with post-hoc Tukey tests..."
```

---

## 🏗️ Architecture

### Tech Stack

| Component | Technology | Purpose |
|-----------|------------|---------|
| **Frontend** | HTML5, TailwindCSS, Vanilla JS | Modern, responsive UI |
| **PDF Rendering** | PDF.js | High-quality PDF display & page capture |
| **Backend** | Pure PHP | Lightweight API for file handling |
| **AI Integration** | Puter.js | Secure authentication & GPT-4o vision |
| **Storage** | Local filesystem (`/books/`) | Simple, portable PDF storage |

### Directory Structure

```
Nexuss-Education/
├── index.php           # Main application (frontend + logic)
├── api.php             # REST API for file uploads
├── books/              # PDF storage directory
│   └── *.pdf          # Uploaded PDF files
├── .gitignore          # Git ignore rules
└── README.md           # This file
```

---

## 🔧 Configuration

### Environment Variables (Optional)

For production deployments, consider setting:

```php
// In api.php
define('MAX_FILE_SIZE', 50 * 1024 * 1024); // 50MB limit
define('ALLOWED_EXTENSIONS', ['pdf']);
define('BOOKS_DIR', __DIR__ . '/books/');
```

### AI Model Customization

The app uses `gpt-4o` by default via Puter.js. To modify the system prompt or model:

```javascript
// In index.php, find the puter.ai.chat call
const systemPrompt = `You are an expert educational assistant...`;
```

---

## 🛡️ Security Considerations

### Production Checklist

- [ ] **File Upload Validation**: Already implemented (PDF-only, size limits)
- [ ] **HTTPS**: Enable SSL/TLS for all traffic
- [ ] **Directory Permissions**: Ensure `/books/` is writable but not executable
- [ ] **Input Sanitization**: All user inputs are sanitized
- [ ] **Rate Limiting**: Consider adding rate limiting for API endpoints
- [ ] **Backup Strategy**: Regularly backup the `/books/` directory

### File Upload Security

```php
// Implemented in api.php
- MIME type validation
- Extension whitelisting
- Filename sanitization
- Size limits enforced
```

---

## 🎨 Customization

### Branding

To customize the brand:

1. **Logo & Name**: Edit the header section in `index.php`
2. **Color Scheme**: Modify Tailwind config or use custom CSS classes
3. **System Prompt**: Adjust the AI personality in the JavaScript section

### Adding Features

- **Multiple AI Models**: Extend Puter.js integration to support model selection
- **Cloud Storage**: Integrate AWS S3, Google Cloud Storage, etc.
- **User Accounts**: Add database-backed user management
- **Annotations**: Implement PDF highlighting and note-taking
- **Export**: Add functionality to export chat history

---

## 🧪 Testing

### Manual Testing Checklist

- [ ] PDF upload works for various file sizes
- [ ] Page navigation updates AI context correctly
- [ ] Chat responses are relevant to current page
- [ ] Sidebar resizing is smooth and responsive
- [ ] Authentication persists across sessions
- [ ] Mobile responsiveness (tablet/desktop optimized)

### Browser Compatibility

- ✅ Chrome/Edge (Chromium)
- ✅ Firefox
- ✅ Safari
- ⚠️ Mobile browsers (optimized for tablet/desktop)

---

## 🚀 Deployment

### Shared Hosting

1. Upload all files via FTP/SFTP
2. Ensure PHP 8.0+ is enabled
3. Set `/books/` permissions to `755` or `777` (if needed)
4. Access via your domain

### VPS/Cloud

```bash
# Example: Ubuntu with Nginx
sudo apt install php-fpm php-cli nginx
sudo cp -r Nexuss-Education /var/www/html/
sudo chown -R www-data:www-data /var/www/html/books/
# Configure Nginx virtual host...
```

### Docker (Future Enhancement)

```dockerfile
FROM php:8.2-apache
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html/books/
EXPOSE 80
```

---

## 📊 Performance Optimization

### Current Optimizations

- ✅ Minimal dependencies (no npm, no bundlers)
- ✅ CDN-hosted libraries (Tailwind, PDF.js, Marked)
- ✅ Lazy loading for PDF pages
- ✅ Efficient canvas-based page capture

### Future Enhancements

- [ ] Service Worker for offline support
- [ ] IndexedDB for local caching
- [ ] WebSocket for real-time collaboration
- [ ] Progressive Web App (PWA) features

---

## 🤝 Contributing

We welcome contributions! Here's how you can help:

1. **Fork the repository**
2. **Create a feature branch** (`git checkout -b feature/amazing-feature`)
3. **Commit your changes** (`git commit -m 'Add amazing feature'`)
4. **Push to the branch** (`git push origin feature/amazing-feature`)
5. **Open a Pull Request**

### Contribution Guidelines

- Follow PSR-12 coding standards for PHP
- Use meaningful commit messages
- Test thoroughly before submitting
- Document new features in this README

---

## 📄 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

### What You Can Do

✅ Use commercially  
✅ Modify and distribute  
✅ Private use  
✅ Patent use  

✅ **No warranty provided** - Use at your own risk

---

## 🙏 Acknowledgments

- **[Puter.js](https://puter.js.org/)** - For providing seamless AI integration
- **[PDF.js](https://mozilla.github.io/pdf.js/)** - Mozilla's powerful PDF rendering library
- **[Tailwind CSS](https://tailwindcss.com/)** - Utility-first CSS framework
- **[Marked](https://marked.js.org/)** - Fast Markdown parser for chat responses

---

## 📞 Support & Contact

- **GitHub Issues**: [Report bugs or request features](https://github.com/nexuss0781/Nexuss-Education/issues)
- **Email**: nexuss0781@gmail.com
- **Documentation**: This README serves as the primary documentation

---

## 🗺️ Roadmap

### Phase 1 (Current) ✅
- [x] Core PDF viewing and chat functionality
- [x] Puter.js authentication
- [x] Page-specific AI context
- [x] Responsive design

### Phase 2 (Planned)
- [ ] Multi-user collaboration
- [ ] Annotation tools (highlight, underline)
- [ ] Export chat history to PDF/Markdown
- [ ] Advanced search within PDFs

### Phase 3 (Future)
- [ ] Mobile app (React Native)
- [ ] Desktop app (Electron)
- [ ] Integration with LMS (Canvas, Moodle)
- [ ] Voice input/output support

---

<div align="center">

**Made with ❤️ for students everywhere**

[Nexuss Education](https://github.com/nexuss0781/Nexuss-Education) © 2024

</div>
