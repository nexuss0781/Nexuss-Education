# Nexuss Education - AI Study Assistant

A production-grade PDF study assistant with intelligent AI chat capabilities, featuring smart rate limit handling, multi-key rotation for OpenRouter API, and advanced vision model support.

![License](https://img.shields.io/badge/license-MIT-blue.svg)
![PHP](https://img.shields.io/badge/PHP-8.0+-purple.svg)
![OpenRouter](https://img.shields.io/badge/OpenRouter-API-green.svg)

## 🎯 Features

### Core Capabilities
- **Modern PDF Viewer** - Clean, responsive PDF rendering with PDF.js, zoom controls (50%-300%), and smooth page navigation
- **AI Chat Integration** - Ask questions about your PDF content with context-aware responses
- **Smart Model Selection** - Choose from 31+ AI models categorized by capability (vision vs text-only)
- **Category Management** - Organize PDFs into custom categories with metadata persistence
- **Dark/Light Theme** - Toggle between themes with localStorage persistence
- **Responsive Design** - Optimized for desktop and tablet use

### 🔑 Smart Rate Limit Handling
- **Multi-Key Rotation** - Automatically rotates through multiple OpenRouter API keys in round-robin fashion
- **Graceful Fallback** - When one key hits rate limit (429), seamlessly tries the next key without user interruption
- **Key Status Monitoring** - Real-time view of all configured API keys with usage statistics
- **Retry Logic** - Automatic retries with exponential backoff for transient failures
- **Credit Tracking** - Monitor daily, weekly, and monthly usage per key
- **Intelligent Key Selection** - Prioritizes keys with remaining credits

### 👁️ Vision Capabilities
- **Vision Models** - 25+ premium and free models that can analyze PDF pages as images
  - GPT-4o, Claude Opus 4.7, Grok 2 Vision
  - Qwen VL Max, QVQ Max, GLM 4.6V series
  - Google Gemma 4, NVIDIA Nemotron Nano Omni
  - And 15+ more...
- **Text-Only Models** - 6+ efficient models optimized for text-based queries
  - Qwen3 Coder 480B, Hermes 3 405B
  - DeepSeek V4 Flash, Llama 3.3 70B
- **Auto-Detection** - System automatically detects and displays model capabilities with badges
- **Fallback Strategy** - Intelligently routes vision requests to capable models

### ⚡ Production Features
- **Aggressive Caching** - Browser caching with immutable headers for uploaded PDFs
- **Error Recovery** - Comprehensive error handling with user-friendly messages
- **Security Headers** - X-Content-Type-Options, CORS configuration, nosniff protection
- **Input Validation** - Server-side validation for file uploads and API requests
- **Directory Isolation** - Category-based storage with proper permissions (0755/0644)
- **Memory Efficiency** - Streaming responses and optimized canvas rendering
- **Request Deduplication** - Prevents duplicate concurrent requests
- **Connection Pooling** - Efficient cURL session management

## 📋 Configuration

### 1. Configure OpenRouter API Keys

Edit `api.php` and add your OpenRouter API keys:

```php
$OPENROUTER_KEYS = [
    "sk-or-v1-YOUR_FIRST_KEY_HERE",
    "sk-or-v1-YOUR_SECOND_KEY_HERE",
    "sk-or-v1-YOUR_THIRD_KEY_HERE", // Add more as needed
];
```

**Best Practices:**
- Add at least 2 keys for redundancy
- Each free tier account gets ~$5/day credit
- With 2 keys: ~$10/day combined quota
- Keys are tried in order; place preferred keys first
- Empty keys are automatically skipped

### 2. Available Models

#### Premium Vision Models (Power Rank 1-10)
| Model | Provider | Context | Description |
|-------|----------|---------|-------------|
| GPT-4o | OpenAI | 128K | Flagship multimodal model |
| Claude Opus 4.7 | Anthropic | 200K | Complex reasoning specialist |
| Grok 2 Vision 1212 | xAI | 128K | Improved accuracy & instruction-following |
| QVQ Max | Qwen | 256K | Visual reasoning flagship |
| Qwen VL Max | Qwen | 256K | Most capable Qwen vision model |
| ERNIE 4.5 VL 424B | Baidu | 128K | Largest multimodal model |
| Mistral Large 3 | Mistral AI | 32K | 675B sparse MoE model |
| Qwen3 VL 235B | Qwen | 256K | Flagship MoE vision model |
| Step3 | StepFun | 128K | 321B multimodal reasoning |
| InternVL3 78B | OpenGVLab | 8K | Open-source multimodal |

#### Free Vision Models (OpenRouter)
| Model | Parameters | Context | Description |
|-------|------------|---------|-------------|
| Google Gemma 4 31B | 31B | 256K | Latest Gemma with vision |
| NVIDIA Nemotron Nano Omni 30B | 30B | 256K | Text, image, video, audio |
| Google Gemma 4 26B A4B | 26B | 256K | Efficient MoE variant |
| NVIDIA Nemotron Nano 12B 2 VL | 12B | 128K | Hybrid Mamba-Transformer |
| Google Lyria 3 Pro Preview | - | 1M | Experimental preview |
| Google Lyria 3 Clip Preview | - | 1M | Short-form preview |

#### Text-Only Models (Free Tier)
| Model | Parameters | Context | Description |
|-------|------------|---------|-------------|
| Qwen3 Coder 480B | 480B | 1M | Massive coding specialist |
| Nous Hermes 3 405B | 405B | 128K | General purpose instruct |
| DeepSeek V4 Flash | 284B | 1M | Fast inference tier |
| Llama 3.3 70B | 70B | 128K | Meta's latest open model |
| Qwen3 Next 80B | 80B | 256K | Optimized instruction model |

## 🚀 Usage

### Quick Start
1. **Upload PDF** - Click the upload button in the sidebar to add a PDF file
2. **Select Category** - Organize PDFs into custom categories (e.g., Mathematics, Physics)
3. **Choose Model** - Select an AI model from the dropdown
   - 👁️ Vision badge = can analyze images directly
   - 📝 Text badge = text processing only (requires OCR for images)
4. **Navigate Pages** - Use page controls to browse your PDF
5. **Ask Questions** - Type your question about the current page
6. **View Key Status** - Click the key icon to monitor API key usage

### Advanced Usage
- **Model Switching** - Change models mid-conversation for different perspectives
- **Category Organization** - Create separate categories for different subjects
- **Theme Customization** - Toggle dark/light mode for comfortable reading
- **Zoom Control** - Adjust PDF zoom from 50% to 300% for detailed analysis

## 📊 Rate Limits & Quotas

### OpenRouter Free Tier
- **Per Account**: ~$5 USD / day credit
- **Combined (2 keys)**: ~$10 USD / day
- **Combined (4 keys)**: ~$20 USD / day
- **Reset Time**: Daily at 00:00 UTC

### Model Pricing (Approximate)
| Tier | Cost per 1K tokens | Example Models |
|------|-------------------|----------------|
| Premium | $0.01 - $0.15 | GPT-4o, Claude Opus |
| Mid-tier | $0.001 - $0.01 | Qwen VL, GLM 4.6V |
| Free | $0.00 | Gemma, Llama, Qwen Coder |

### Smart Quota Management
The system implements intelligent quota management:
1. **Round-Robin Distribution** - Spreads requests evenly across keys
2. **Failure Detection** - Marks keys as temporarily unavailable on rate limit
3. **Recovery Monitoring** - Periodically checks if limited keys are available again
4. **Priority Routing** - Routes expensive requests to keys with higher remaining credits

## 🏗️ Architecture

### File Structure
```
/workspace/
├── index.php              # Main application (frontend + client logic)
├── api.php                # Backend API server
│   ├── Key rotation logic
│   ├── OpenRouter proxy
│   ├── File upload handler
│   └── Category management
├── config.example.php     # Configuration template
├── SYSTEM.md              # Custom system prompt (optional)
├── books/                 # Legacy PDF storage (auto-created)
├── categories/            # Category-based PDF storage (auto-created)
│   ├── default/           # Default category
│   │   ├── meta.json      # Category metadata
│   │   └── *.pdf          # PDF files
│   └── [custom]/          # User-created categories
└── README.md              # This documentation
```

### Request Flow
```
User Input → Frontend Validation → API Proxy → Key Selection
                                              ↓
                              Try Key 1 → Success? → Return Response
                                      ↓ No (429)
                              Try Key 2 → Success? → Return Response
                                      ↓ No (429)
                              Try Key N → ...
                                      ↓ All Failed
                              Return Error with Details
```

### Key Rotation Algorithm
```javascript
1. Maintain array of API keys
2. For each request:
   a. Start with first non-limited key
   b. Attempt API call
   c. On success (200): cache key, return response
   d. On rate limit (429): mark key limited, try next
   e. On other error: stop, return error
3. Periodic health check: reset limited keys after timeout
```

## 🔒 Security Considerations

### Implemented Security Measures
- **CORS Configuration**: Restricts cross-origin requests to same origin
- **Content-Type Validation**: Prevents MIME type sniffing attacks
- **File Upload Validation**: Only accepts .pdf files with proper extension checking
- **Path Sanitization**: Removes special characters from filenames and category names
- **Permission Hardening**: Files created with 0644, directories with 0755
- **Session Management**: PHP sessions for stateful operations
- **Cache Headers**: Prevents caching of sensitive API responses

### Best Practices for Production
1. **Environment Variables**: Move API keys to environment variables in production
   ```php
   $OPENROUTER_KEYS = explode(',', getenv('OPENROUTER_KEYS'));
   ```
2. **HTTPS Enforcement**: Deploy behind HTTPS reverse proxy (nginx/Apache)
3. **Rate Limiting**: Add additional rate limiting at web server level
4. **Logging**: Implement access logging for audit trails
5. **Monitoring**: Set up alerts for unusual API usage patterns

## 🛠️ Troubleshooting

### Common Issues

**Issue**: "All API keys failed" error
- **Solution**: Verify API keys are valid and have remaining credits
- Check key status using the key icon in the UI

**Issue**: PDF upload fails
- **Solution**: Check `categories/` directory permissions (should be 0755)
- Ensure PHP has write permissions

**Issue**: Model not responding
- **Solution**: Try a different model; some free models may be temporarily unavailable
- Check OpenRouter status page

**Issue**: Vision model not analyzing images
- **Solution**: Ensure selected model has 👁️ Vision badge
- Check browser console for JavaScript errors

### Debugging Tips
1. Open browser DevTools (F12) to view console errors
2. Check Network tab for failed API requests
3. Enable PHP error logging in `api.php`:
   ```php
   error_reporting(E_ALL);
   ini_set('display_errors', 0);
   ini_set('log_errors', 1);
   ```

## 📈 Performance Optimization

### Client-Side Optimizations
- **Lazy Loading**: PDF pages loaded on-demand
- **Canvas Caching**: Rendered pages cached in memory
- **Debounced Inputs**: Prevents excessive API calls
- **Compressed Images**: Vision requests use optimized canvas exports

### Server-Side Optimizations
- **cURL Timeout**: 60-second timeout prevents hanging requests
- **Connection Reuse**: cURL sessions properly closed
- **Memory Management**: Large files streamed, not loaded entirely
- **JSON Efficiency**: Minimal JSON parsing overhead

### Caching Strategy
| Resource | Cache Duration | Strategy |
|----------|---------------|----------|
| Uploaded PDFs | 1 year | Immutable, fingerprinted |
| API Responses | 0 | No caching (dynamic) |
| Static Assets | Browser default | Standard caching |
| Model List | Session | In-memory cache |

## 🤝 Contributing

We welcome contributions! Please follow these guidelines:

1. **Fork the Repository**
2. **Create Feature Branch**: `git checkout -b feature/amazing-feature`
3. **Commit Changes**: `git commit -m 'Add amazing feature'`
4. **Push to Branch**: `git push origin feature/amazing-feature`
5. **Open Pull Request**

### Development Setup
```bash
# Clone repository
git clone https://github.com/nexuss0781/Nexuss-Education.git

# Navigate to directory
cd Nexuss-Education

# Configure API keys
cp config.example.php api.php
# Edit api.php with your keys

# Start PHP development server
php -S localhost:8000
```

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

### Attribution
- **PDF.js** - Mozilla (Apache 2.0)
- **Tailwind CSS** - Tailwind Labs (MIT)
- **Marked** - Marked Project (MIT)
- **OpenRouter API** - OpenRouter (Commercial)

## 🙏 Acknowledgments

- OpenRouter for providing unified API access to multiple AI models
- PDF.js team for excellent PDF rendering library
- Tailwind CSS for modern utility-first styling
- All AI model providers for making their models accessible

## 📞 Support

For issues, questions, or suggestions:
- **GitHub Issues**: https://github.com/nexuss0781/Nexuss-Education/issues
- **Email**: nexuss0781@gmail.com

---

**Built with ❤️ for education by Nexuss Education**
