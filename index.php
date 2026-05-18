<?php
// Nexuss Education - Main Application
session_start();
header("X-Content-Type-Options: nosniff");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexuss Education - AI Study Assistant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script src="https://js.puter.com/v2/"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: { 50: '#f5f3ff', 100: '#ede9fe', 200: '#ddd6fe', 300: '#c4b5fd', 400: '#a78bfa', 500: '#8b5cf6', 600: '#7c3aed', 700: '#6d28d9', 800: '#5b21b6', 900: '#4c1d95' },
                        dark: { 50: '#f8fafc', 100: '#f1f5f9', 200: '#e2e8f0', 300: '#cbd5e1', 400: '#94a3b8', 500: '#64748b', 600: '#475569', 700: '#334155', 800: '#1e293b', 900: '#0f172a', 950: '#020617' }
                    }
                }
            }
        }
    </script>
    <style>
        .scrollbar-thin::-webkit-scrollbar { width: 6px; height: 6px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
        .dark .scrollbar-thin::-webkit-scrollbar-thumb { background-color: #475569; border-radius: 3px; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 3px; }
        .typing-dot { animation: typing 1.4s infinite ease-in-out both; }
        .typing-dot:nth-child(1) { animation-delay: -0.32s; }
        .typing-dot:nth-child(2) { animation-delay: -0.16s; }
        @keyframes typing { 0%, 80%, 100% { transform: scale(0); } 40% { transform: scale(1); } }
        .markdown-body pre { background: #1e293b; padding: 1rem; border-radius: 0.5rem; overflow-x: auto; }
        .markdown-body code { background: rgba(139, 92, 246, 0.2); padding: 0.2rem 0.4rem; border-radius: 0.25rem; font-size: 0.9em; }
        .markdown-body pre code { background: transparent; padding: 0; }
        .markdown-body ul { list-style-type: disc; padding-left: 1.5rem; margin: 0.5rem 0; }
        .markdown-body ol { list-style-type: decimal; padding-left: 1.5rem; margin: 0.5rem 0; }
        .markdown-body blockquote { border-left: 4px solid #8b5cf6; padding-left: 1rem; color: #94a3b8; font-style: italic; }
        .sidebar-transition { transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</head>
<body class="bg-gray-50 dark:bg-dark-950 text-gray-900 dark:text-gray-100 h-screen overflow-hidden flex flex-col">

<!-- Header -->
<header class="h-14 bg-white dark:bg-dark-900 border-b border-gray-200 dark:border-dark-800 flex items-center justify-between px-4 shrink-0 z-20">
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-700 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <h1 class="text-lg font-bold bg-gradient-to-r from-primary-500 to-primary-700 bg-clip-text text-transparent">Nexuss Education</h1>
    </div>
    <div class="flex items-center gap-2">
        <button id="themeToggle" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-dark-800 transition-colors" title="Toggle Theme">
            <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
        </button>
        <button id="authBtn" class="px-3 py-1.5 text-sm bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors">Sign In</button>
    </div>
</header>

<!-- Main Content -->
<div class="flex-1 flex overflow-hidden relative">
    
    <!-- Library Sidebar -->
    <aside id="librarySidebar" class="w-72 bg-white dark:bg-dark-900 border-r border-gray-200 dark:border-dark-800 flex flex-col sidebar-transition shrink-0">
        <div class="p-3 border-b border-gray-200 dark:border-dark-800">
            <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Library</h2>
            <button onclick="showCreateCategoryModal()" class="w-full py-2 px-3 bg-primary-600 hover:bg-primary-700 text-white text-sm rounded-lg transition-colors flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Category
            </button>
        </div>
        <div id="categoriesList" class="flex-1 overflow-y-auto scrollbar-thin p-2 space-y-1">
            <!-- Categories will be rendered here -->
        </div>
        <div class="p-3 border-t border-gray-200 dark:border-dark-800">
            <label class="flex items-center justify-center w-full py-2 px-3 border-2 border-dashed border-gray-300 dark:border-dark-700 rounded-lg cursor-pointer hover:border-primary-500 dark:hover:border-primary-500 transition-colors">
                <input type="file" id="pdfUpload" accept=".pdf" class="hidden" onchange="handleFileUpload(this)">
                <div class="text-center">
                    <svg class="w-5 h-5 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-1 block">Upload PDF</span>
                </div>
            </label>
        </div>
    </aside>

    <!-- Resize Handle -->
    <div id="resizeHandle" class="w-1 bg-gray-200 dark:bg-dark-800 hover:bg-primary-500 cursor-col-resize transition-colors z-10"></div>

    <!-- Chat Area -->
    <main class="flex-1 flex flex-col min-w-0 bg-gray-50 dark:bg-dark-950">
        <!-- Model Selector -->
        <div class="p-3 bg-white dark:bg-dark-900 border-b border-gray-200 dark:border-dark-800 flex items-center gap-4">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap">AI Model:</label>
            </div>
            <div id="modelSelector" class="flex items-center gap-3 flex-1"></div>
            <div id="modelStatus" class="text-xs text-gray-500 dark:text-gray-400"></div>
        </div>

        <!-- Messages -->
        <div id="chatMessages" class="flex-1 overflow-y-auto scrollbar-thin p-4 space-y-4">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div class="bg-white dark:bg-dark-900 rounded-2xl rounded-tl-none p-4 shadow-sm border border-gray-200 dark:border-dark-800 max-w-2xl">
                    <p class="text-sm leading-relaxed">Hello! I'm your Nexuss Education AI assistant. Upload a PDF, select a page, and I'll help you understand it. I can see what's on your current page!</p>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-4 bg-white dark:bg-dark-900 border-t border-gray-200 dark:border-dark-800">
            <div class="max-w-4xl mx-auto flex gap-3">
                <textarea id="userInput" rows="1" class="flex-1 px-4 py-3 bg-gray-100 dark:bg-dark-800 border border-gray-300 dark:border-dark-700 rounded-xl resize-none focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm" placeholder="Ask about the current page..." onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();sendMessage();}"></textarea>
                <button id="sendBtn" onclick="sendMessage()" class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl transition-colors flex items-center gap-2 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <span class="hidden sm:inline">Send</span>
                </button>
            </div>
        </div>
    </main>

    <!-- PDF Viewer Panel -->
    <aside class="w-[500px] min-w-[350px] bg-white dark:bg-dark-900 border-l border-gray-200 dark:border-dark-800 flex flex-col shrink-0">
        <div class="p-3 border-b border-gray-200 dark:border-dark-800 flex items-center justify-between">
            <h3 class="font-semibold text-sm truncate" id="pdfTitle">No PDF Loaded</h3>
            <div class="flex items-center gap-2">
                <button onclick="changePage(-1)" class="p-1.5 hover:bg-gray-100 dark:hover:bg-dark-800 rounded-lg transition-colors" title="Previous Page">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <span class="text-xs font-mono bg-gray-100 dark:bg-dark-800 px-2 py-1 rounded" id="pageIndicator">0 / 0</span>
                <button onclick="changePage(1)" class="p-1.5 hover:bg-gray-100 dark:hover:bg-dark-800 rounded-lg transition-colors" title="Next Page">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
                <div class="w-px h-4 bg-gray-300 dark:bg-dark-700 mx-1"></div>
                <button onclick="adjustZoom(-10)" class="p-1.5 hover:bg-gray-100 dark:hover:bg-dark-800 rounded-lg transition-colors" title="Zoom Out">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                </button>
                <span class="text-xs font-mono bg-gray-100 dark:bg-dark-800 px-2 py-1 rounded w-12 text-center" id="zoomIndicator">100%</span>
                <button onclick="adjustZoom(10)" class="p-1.5 hover:bg-gray-100 dark:hover:bg-dark-800 rounded-lg transition-colors" title="Zoom In">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </button>
            </div>
        </div>
        <div class="flex-1 overflow-auto bg-gray-100 dark:bg-dark-950 p-4 flex items-center justify-center relative">
            <canvas id="pdfCanvas" class="shadow-lg rounded bg-white dark:bg-dark-900"></canvas>
            <div id="pdfEmptyState" class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <div class="text-center text-gray-400 dark:text-gray-600">
                    <svg class="w-16 h-16 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p class="text-sm">Upload a PDF to get started</p>
                </div>
            </div>
        </div>
    </aside>
</div>

<!-- Create Category Modal -->
<div id="createCategoryModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center">
    <div class="bg-white dark:bg-dark-900 rounded-2xl p-6 w-full max-w-md shadow-2xl border border-gray-200 dark:border-dark-800">
        <h3 class="text-lg font-bold mb-4">Create New Category</h3>
        <input type="text" id="newCategoryName" class="w-full px-4 py-2 bg-gray-100 dark:bg-dark-800 border border-gray-300 dark:border-dark-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="e.g., Mathematics, Physics, Notes...">
        <div class="flex gap-3 mt-6">
            <button onclick="hideCreateCategoryModal()" class="flex-1 py-2 px-4 bg-gray-100 dark:bg-dark-800 hover:bg-gray-200 dark:hover:bg-dark-700 rounded-lg transition-colors">Cancel</button>
            <button onclick="createCategory()" class="flex-1 py-2 px-4 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors">Create</button>
        </div>
    </div>
</div>

<!-- Rename Category Modal -->
<div id="renameCategoryModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center">
    <div class="bg-white dark:bg-dark-900 rounded-2xl p-6 w-full max-w-md shadow-2xl border border-gray-200 dark:border-dark-800">
        <h3 class="text-lg font-bold mb-4">Rename Category</h3>
        <input type="text" id="renameCategoryInput" class="w-full px-4 py-2 bg-gray-100 dark:bg-dark-800 border border-gray-300 dark:border-dark-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
        <input type="hidden" id="renameCategoryId">
        <div class="flex gap-3 mt-6">
            <button onclick="hideRenameCategoryModal()" class="flex-1 py-2 px-4 bg-gray-100 dark:bg-dark-800 hover:bg-gray-200 dark:hover:bg-dark-700 rounded-lg transition-colors">Cancel</button>
            <button onclick="saveRenameCategory()" class="flex-1 py-2 px-4 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors">Save</button>
        </div>
    </div>
</div>

<script>
// State
let categories = JSON.parse(localStorage.getItem('nexuss_categories')) || [{id: 'default', name: 'My Books', pdfs: []}];
let currentPdf = null;
let pdfDoc = null;
let currentPage = 1;
let zoom = 1.0;
let selectedCategory = 'default';
let systemPrompt = '';
let currentModel = 'openai/gpt-4o'; // Default to GPT-4o

// Initialize
document.addEventListener('DOMContentLoaded', async () => {
    initTheme();
    loadCategories();
    await loadSystemPrompt();
    await initPuterAuth();
    renderModelSelector();
    setupResizeHandler();
});

// Theme Management
function initTheme() {
    const isDark = localStorage.getItem('theme') !== 'light';
    document.documentElement.classList.toggle('dark', isDark);
    document.getElementById('themeToggle').onclick = () => {
        const isNowDark = !document.documentElement.classList.contains('dark');
        document.documentElement.classList.toggle('dark', isNowDark);
        localStorage.setItem('theme', isNowDark ? 'dark' : 'light');
    };
}

// System Prompt Loader
async function loadSystemPrompt() {
    try {
        const response = await fetch('SYSTEM.md');
        if (response.ok) {
            systemPrompt = await response.text();
        } else {
            systemPrompt = "You are a helpful study assistant. Keep responses concise and educational.";
        }
    } catch (e) {
        systemPrompt = "You are a helpful study assistant. Keep responses concise and educational.";
    }
}

// System Prompt Loader
async function loadSystemPrompt() {
    try {
        const response = await fetch('SYSTEM.md');
        if (response.ok) {
            systemPrompt = await response.text();
        } else {
            systemPrompt = "You are a helpful study assistant. Keep responses concise and educational.";
        }
    } catch (e) {
        systemPrompt = "You are a helpful study assistant. Keep responses concise and educational.";
    }
}

// Puter.js Authentication
async function initPuterAuth() {
    const authBtn = document.getElementById('authBtn');
    const modelStatus = document.getElementById('modelStatus');
    
    // Check if already authenticated
    if (typeof puter !== 'undefined' && puter.auth.isSignedIn()) {
        authBtn.textContent = 'Authenticated';
        authBtn.classList.add('opacity-50', 'cursor-not-allowed');
        authBtn.disabled = true;
        if (modelStatus) {
            modelStatus.textContent = '✓ Connected';
            modelStatus.className = 'text-xs text-green-500';
        }
    } else {
        authBtn.onclick = async () => {
            try {
                await puter.auth.signIn();
                authBtn.textContent = 'Authenticated';
                authBtn.classList.add('opacity-50', 'cursor-not-allowed');
                authBtn.disabled = true;
                if (modelStatus) {
                    modelStatus.textContent = '✓ Connected';
                    modelStatus.className = 'text-xs text-green-500';
                }
                addSystemMessage("Successfully authenticated with Puter.js!");
            } catch (err) {
                console.error('Auth error:', err);
                addSystemMessage("Authentication failed. Please try again.");
            }
        };
    }
}

// Premium AI Models List (Curated for Vision & Reasoning)
const PREMIUM_MODELS = [
  { id: "openai/gpt-4o", name: "GPT-4o", provider: "OpenAI", desc: "Flagship multimodal model" },
  { id: "anthropic/claude-opus-4.7", name: "Claude Opus 4.7", provider: "Anthropic", desc: "Complex reasoning specialist" },
  { id: "x-ai/grok-2-vision-1212", name: "Grok 2 Vision", provider: "xAI", desc: "Advanced visual accuracy" },
  { id: "qwen/qvq-max", name: "QVQ Max", provider: "Qwen", desc: "Deep multimodal reasoning" },
  { id: "qwen/qwen-vl-max", name: "Qwen VL Max", provider: "Qwen", desc: "Top-tier vision-language" },
  { id: "baidu/ernie-4.5-vl-424b-a47b", name: "ERNIE 4.5 VL", provider: "Baidu", desc: "Massive 424B parameter model" },
  { id: "mistralai/mistral-large-3", name: "Mistral Large 3", provider: "Mistral AI", desc: "675B MoE frontier model" },
  { id: "qwen/qwen3-vl-235b-a22b-instruct", name: "Qwen3 VL 235B", provider: "Qwen", desc: "Flagship 256K context" },
  { id: "stepfun-ai/step3", name: "Step3", provider: "StepFun", desc: "Multimodal MoE reasoning" },
  { id: "opengvlab/internvl3-78b", name: "InternVL3 78B", provider: "OpenGVLab", desc: "Open-source multimodal" },
  { id: "z-ai/glm-4.6v", name: "GLM 4.6V", provider: "Z.AI", desc: "Native multimodal calling" },
  { id: "qwen/qwen2.5-vl-72b-instruct", name: "Qwen2.5 VL 72B", provider: "Qwen", desc: "Open-source flagship" },
  { id: "moonshotai/kimi-k2.5", name: "Kimi K2.5", provider: "Moonshot AI", desc: "Trillion-parameter MoE" },
  { id: "perceptron/perceptron-mk1", name: "Perceptron Mk1", provider: "Perceptron", desc: "Video & spatial reasoning" },
  { id: "mistralai/mistral-medium-3.5", name: "Mistral Medium 3.5", provider: "Mistral AI", desc: "Dense 128B model" },
  { id: "qwen/qwen3.5-397b-a17b", name: "Qwen3.5 397B", provider: "Qwen", desc: "Native vision-language MoE" },
  { id: "z-ai/glm-5v-turbo", name: "GLM 5V Turbo", provider: "Z.AI", desc: "Visual perception to code" },
  { id: "allenai/molmo-2-8b", name: "Molmo2 8B", provider: "Allen AI", desc: "Efficient open VLM" },
  { id: "meta-llama/llama-3.2-11b-vision-instruct", name: "Llama 3.2 11B Vision", provider: "Meta", desc: "Visual recognition expert" },
  { id: "mistralai/pixtral-12b", name: "Pixtral 12B", provider: "Mistral AI", desc: "First Mistral multimodal" },
  { id: "rekaai/reka-edge", name: "Reka Edge", provider: "Reka AI", desc: "Efficient visual reasoning" },
  { id: "bytedance/ui-tars-1.5-7b", name: "UI-TARS 7B", provider: "ByteDance", desc: "GUI automation agent" },
  { id: "qwen/qwen3-vl-8b-instruct", name: "Qwen3 VL 8B", provider: "Qwen", desc: "Compact high-performance" },
  { id: "z-ai/glm-4.6v-flash", name: "GLM 4.6V Flash", provider: "Z.AI", desc: "Lightweight 9B variant" },
  { id: "nvidia/nemotron-nano-12b-2-vl", name: "Nemotron Nano 12B", provider: "NVIDIA", desc: "Hybrid Mamba-Transformer" }
];

// Render Elegant Model Selector
function renderModelSelector() {
  const selector = document.getElementById('modelSelector');
  const status = document.getElementById('modelStatus');
  
  if (!selector) return;
  
  selector.innerHTML = '';
  if (status) status.textContent = '';
  
  const selectWrapper = document.createElement('div');
  selectWrapper.className = 'relative group flex-1 max-w-md';
  
  const selectEl = document.createElement('select');
  selectEl.id = 'aiModelSelect';
  selectEl.className = 'appearance-none w-full bg-slate-800/50 hover:bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 pr-10 text-sm font-medium text-slate-200 focus:outline-none focus:ring-2 focus:ring-violet-500/50 transition-all cursor-pointer shadow-sm';
  
  PREMIUM_MODELS.forEach(m => {
    const option = document.createElement('option');
    option.value = m.id;
    option.textContent = `${m.name} (${m.provider})`;
    if (m.id === currentModel) option.selected = true;
    selectEl.appendChild(option);
  });
  
  const arrowIcon = document.createElement('div');
  arrowIcon.className = 'absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400';
  arrowIcon.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>';
  
  const focusLine = document.createElement('div');
  focusLine.className = 'absolute -bottom-1 left-0 right-0 h-px bg-gradient-to-r from-transparent via-violet-500/50 to-transparent opacity-0 group-focus-within:opacity-100 transition-opacity';
  
  selectWrapper.appendChild(selectEl);
  selectWrapper.appendChild(arrowIcon);
  selectWrapper.appendChild(focusLine);
  selector.appendChild(selectWrapper);
  
  const descEl = document.createElement('div');
  descEl.id = 'modelDesc';
  descEl.className = 'hidden lg:block text-xs text-slate-500 font-medium truncate max-w-[180px]';
  const selected = PREMIUM_MODELS.find(m => m.id === currentModel);
  if (selected && descEl) descEl.textContent = selected.desc;
  selector.appendChild(descEl);
  
  selectEl.addEventListener('change', (e) => {
    currentModel = e.target.value;
    const sel = PREMIUM_MODELS.find(m => m.id === currentModel);
    if (sel && descEl) descEl.textContent = sel.desc;
    localStorage.setItem('nexuss_selected_model', currentModel);
  });

  // Restore saved model
  const savedModel = localStorage.getItem('nexuss_selected_model');
  if (savedModel && PREMIUM_MODELS.find(m => m.id === savedModel)) {
    currentModel = savedModel;
    selectEl.value = savedModel;
    if (descEl) {
      const sel = PREMIUM_MODELS.find(m => m.id === savedModel);
      descEl.textContent = sel.desc;
    }
  }
}

// Category Management - Server-side
async function loadCategories() {
    try {
        const res = await fetch('api.php?action=categories');
        const data = await res.json();
        if (data.success) {
            categories = data.categories;
            if (!selectedCategory && categories.length > 0) {
                selectedCategory = categories[0].id;
            }
            renderCategories();
        }
    } catch (err) {
        console.error('Failed to load categories:', err);
        // Fallback to local storage
        categories = JSON.parse(localStorage.getItem('nexuss_categories')) || [{id: 'default', name: 'My Books', pdfs: []}];
        renderCategories();
    }
}

async function saveCategories() {
    // Sync to server
    for (const cat of categories) {
        try {
            await fetch('api.php?action=categories', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ type: 'update', id: cat.id, pdfs: cat.pdfs })
            });
        } catch (err) {
            console.error('Failed to sync category:', err);
        }
    }
    localStorage.setItem('nexuss_categories', JSON.stringify(categories));
}

async function createCategory() {
    const nameInput = document.getElementById('newCategoryName');
    const name = nameInput.value.trim();
    if (!name) return;
    
    try {
        const res = await fetch('api.php?action=categories', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ type: 'create', name })
        });
        const data = await res.json();
        if (data.success) {
            categories.push({ id: data.id, name: data.name, pdfs: [] });
            selectedCategory = data.id;
            nameInput.value = '';
            hideCreateCategoryModal();
            renderCategories();
            addSystemMessage(`✓ Created category: ${data.name}`);
        } else {
            alert('Failed: ' + data.error);
        }
    } catch (err) {
        alert('Error creating category');
    }
}

async function deleteCategory(id) {
    if (!confirm('Delete this category and all its PDFs?')) return;
    
    try {
        const res = await fetch('api.php?action=categories', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ type: 'delete', id })
        });
        const data = await res.json();
        if (data.success) {
            categories = categories.filter(c => c.id !== id);
            if (selectedCategory === id) {
                selectedCategory = categories[0]?.id || 'default';
            }
            renderCategories();
            addSystemMessage('✓ Category deleted');
        } else {
            alert('Failed: ' + data.error);
        }
    } catch (err) {
        alert('Error deleting category');
    }
}

async function saveRenameCategory() {
    const id = document.getElementById('renameCategoryId').value;
    const nameInput = document.getElementById('renameCategoryInput');
    const newName = nameInput.value.trim();
    if (!newName) return;
    
    try {
        const res = await fetch('api.php?action=categories', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ type: 'rename', id, name: newName })
        });
        const data = await res.json();
        if (data.success) {
            const cat = categories.find(c => c.id === id);
            if (cat) cat.name = newName;
            hideRenameCategoryModal();
            renderCategories();
            addSystemMessage(`✓ Renamed to: ${newName}`);
        } else {
            alert('Failed: ' + data.error);
        }
    } catch (err) {
        alert('Error renaming category');
    }
}

function renderCategories() {
    const container = document.getElementById('categoriesList');
    container.innerHTML = '';
    
    categories.forEach(cat => {
        const div = document.createElement('div');
        div.className = `group flex items-center justify-between p-2 rounded-lg cursor-pointer transition-colors ${selectedCategory === cat.id ? 'bg-primary-100 dark:bg-primary-900/30' : 'hover:bg-gray-100 dark:hover:bg-dark-800'}`;
        div.onclick = (e) => {
            if (!e.target.closest('button')) {
                selectedCategory = cat.id;
                renderCategories();
            }
        };
        
        div.innerHTML = `
            <div class="flex items-center gap-2 flex-1 min-w-0">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                <span class="text-sm font-medium truncate">${cat.name}</span>
                <span class="text-xs text-gray-400 ml-auto">${cat.pdfs.length}</span>
            </div>
            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                <button onclick="showRenameCategoryModal('${cat.id}')" class="p-1 hover:bg-gray-200 dark:hover:bg-dark-700 rounded" title="Rename">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                </button>
                ${cat.id !== 'default' ? `
                <button onclick="deleteCategory('${cat.id}')" class="p-1 hover:bg-red-100 dark:hover:bg-red-900/30 text-red-500 rounded" title="Delete">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>` : ''}
            </div>
        `;
        container.appendChild(div);
        
        // Render PDFs inside category if selected
        if (cat.id === selectedCategory) {
            const pdfList = document.createElement('div');
            pdfList.className = 'ml-6 mt-1 space-y-1';
            cat.pdfs.forEach((pdf, idx) => {
                const pdfDiv = document.createElement('div');
                pdfDiv.className = `flex items-center gap-2 p-1.5 rounded text-xs cursor-pointer ${currentPdf && currentPdf.name === pdf.name ? 'bg-primary-200 dark:bg-primary-800' : 'hover:bg-gray-100 dark:hover:bg-dark-800'}`;
                pdfDiv.innerHTML = `
                    <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                    <span class="truncate flex-1">${pdf.name}</span>
                    <button onclick="deletePdf('${cat.id}', ${idx})" class="opacity-0 group-hover:opacity-100 hover:text-red-500">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                `;
                pdfDiv.onclick = (e) => {
                    if (!e.target.closest('button')) loadPdf(pdf);
                };
                pdfList.appendChild(pdfDiv);
            });
            container.appendChild(pdfList);
        }
    });
}

function showCreateCategoryModal() {
    document.getElementById('createCategoryModal').classList.remove('hidden');
    document.getElementById('createCategoryModal').classList.add('flex');
    document.getElementById('newCategoryName').focus();
}

function hideCreateCategoryModal() {
    document.getElementById('createCategoryModal').classList.add('hidden');
    document.getElementById('createCategoryModal').classList.remove('flex');
    document.getElementById('newCategoryName').value = '';
}

function createCategory() {
    const name = document.getElementById('newCategoryName').value.trim();
    if (!name) return;
    
    const id = 'cat_' + Date.now();
    categories.push({ id, name, pdfs: [] });
    saveCategories();
    renderCategories();
    hideCreateCategoryModal();
}

function showRenameCategoryModal(id) {
    const cat = categories.find(c => c.id === id);
    if (!cat) return;
    
    document.getElementById('renameCategoryId').value = id;
    document.getElementById('renameCategoryInput').value = cat.name;
    document.getElementById('renameCategoryModal').classList.remove('hidden');
    document.getElementById('renameCategoryModal').classList.add('flex');
    document.getElementById('renameCategoryInput').focus();
}

function hideRenameCategoryModal() {
    document.getElementById('renameCategoryModal').classList.add('hidden');
    document.getElementById('renameCategoryModal').classList.remove('flex');
}

function saveRenameCategory() {
    const id = document.getElementById('renameCategoryId').value;
    const name = document.getElementById('renameCategoryInput').value.trim();
    if (!name || !id) return;
    
    const cat = categories.find(c => c.id === id);
    if (cat) {
        cat.name = name;
        saveCategories();
        renderCategories();
    }
    hideRenameCategoryModal();
}

// PDF Upload & Management
async function handleFileUpload(input) {
    const file = input.files[0];
    if (!file || file.type !== 'application/pdf') {
        addSystemMessage('✗ Please select a valid PDF file.');
        return;
    }

    const formData = new FormData();
    formData.append('pdf', file);
    formData.append('category', selectedCategory);

    const btn = input.parentElement;
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<div class="flex flex-col items-center gap-1"><svg class="w-5 h-5 animate-spin text-primary-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span class="text-xs text-primary-500">Uploading...</span></div>';

    try {
        const res = await fetch('api.php?action=upload', { method: 'POST', body: formData });
        const data = await res.json();
        
        if (!data.success) {
            throw new Error(data.error || 'Upload failed: Invalid action');
        }
        
        const cat = categories.find(c => c.id === selectedCategory);
        if (cat) {
            cat.pdfs.push({ name: data.filename, path: data.path });
            await saveCategories();
            renderCategories();
            loadPdf({ name: data.filename, path: data.path });
            addSystemMessage(`✓ Uploaded: ${data.filename}`);
        }
    } catch (err) {
        console.error('Upload error:', err);
        addSystemMessage('✗ Upload failed: ' + err.message);
    } finally {
        btn.innerHTML = originalHtml;
        input.value = '';
    }
}

function deletePdf(catId, pdfIndex) {
    if (!confirm('Delete this PDF?')) return;
    
    const cat = categories.find(c => c.id === catId);
    if (cat && cat.pdfs[pdfIndex]) {
        const pdfName = cat.pdfs[pdfIndex].name;
        
        // Delete from server
        fetch('api.php', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ filename: pdfName })
        }).catch(console.error);
        
        cat.pdfs.splice(pdfIndex, 1);
        if (currentPdf && currentPdf.name === pdfName) {
            currentPdf = null;
            pdfDoc = null;
            clearCanvas();
            document.getElementById('pdfTitle').textContent = 'No PDF Loaded';
            document.getElementById('pageIndicator').textContent = '0 / 0';
        }
        saveCategories();
        renderCategories();
        addSystemMessage(`Deleted: ${pdfName}`);
    }
}

// PDF Rendering
async function loadPdf(pdfInfo) {
    try {
        document.getElementById('pdfTitle').textContent = pdfInfo.name;
        document.getElementById('pdfEmptyState').style.display = 'none';
        
        const loadingTask = pdfjsLib.getDocument(pdfInfo.path);
        pdfDoc = await loadingTask.promise;
        
        currentPdf = pdfInfo;
        currentPage = 1;
        renderPage(currentPage);
        
        document.getElementById('pageIndicator').textContent = `1 / ${pdfDoc.numPages}`;
        addSystemMessage(`Loaded: ${pdfInfo.name} (${pdfDoc.numPages} pages)`);
    } catch (err) {
        console.error('PDF load error:', err);
        alert('Failed to load PDF: ' + err.message);
        addSystemMessage('✗ Failed to load PDF');
    }
}

async function renderPage(pageNum) {
    if (!pdfDoc) return;
    
    try {
        const page = await pdfDoc.getPage(pageNum);
        const canvas = document.getElementById('pdfCanvas');
        const ctx = canvas.getContext('2d');
        
        const viewport = page.getViewport({ scale: zoom });
        canvas.width = viewport.width;
        canvas.height = viewport.height;
        
        await page.render({ canvasContext: ctx, viewport: viewport }).promise;
        
        currentPage = pageNum;
        document.getElementById('pageIndicator').textContent = `${pageNum} / ${pdfDoc.numPages}`;
    } catch (err) {
        console.error('Render error:', err);
    }
}

function changePage(delta) {
    if (!pdfDoc) return;
    const newPage = currentPage + delta;
    if (newPage >= 1 && newPage <= pdfDoc.numPages) {
        renderPage(newPage);
    }
}

function adjustZoom(delta) {
    zoom = Math.max(0.5, Math.min(3.0, zoom + delta / 100));
    document.getElementById('zoomIndicator').textContent = Math.round(zoom * 100) + '%';
    if (pdfDoc) renderPage(currentPage);
}

function clearCanvas() {
    const canvas = document.getElementById('pdfCanvas');
    const ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    canvas.width = 800;
    canvas.height = 600;
    document.getElementById('pdfEmptyState').style.display = 'flex';
}

// Capture Current Page as Image for AI
async function captureCurrentPage() {
    if (!pdfDoc || !currentPdf) return null;
    
    try {
        const page = await pdfDoc.getPage(currentPage);
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        
        // High quality capture (2x scale)
        const viewport = page.getViewport({ scale: 2.0 });
        canvas.width = viewport.width;
        canvas.height = viewport.height;
        
        await page.render({ canvasContext: ctx, viewport: viewport }).promise;
        
        return canvas.toDataURL('image/jpeg', 0.9);
    } catch (err) {
        console.error('Capture error:', err);
        return null;
    }
}

// Chat Functionality
async function sendMessage() {
    const input = document.getElementById('userInput');
    const message = input.value.trim();
    if (!message) return;
    
    // Add user message
    addMessage(message, 'user');
    input.value = '';
    
    // Show typing indicator
    const typingId = showTypingIndicator();
    
    try {
        // Capture current page
        const pageImage = await captureCurrentPage();
        
        let userMessage = message;
        
        if (pageImage) {
            userMessage = message + "\n\n[Current PDF page is attached as image for context]";
        }
        
        // Get selected model
        const model = document.getElementById('modelSelector').value || 'gpt-4o';
        
        // Prepare messages array
        const messages = [
            { role: 'system', content: systemPrompt },
            { role: 'user', content: userMessage }
        ];
        
        // Call Puter AI with proper error handling
        let response;
        try {
            if (pageImage) {
                // For vision models with image
                response = await puter.ai.chat([
                    { 
                        role: 'user', 
                        content: [
                            { type: 'text', text: systemPrompt },
                            { type: 'text', text: userMessage },
                            { type: 'image_url', image_url: { url: pageImage } }
                        ]
                    }
                ], { model: model });
            } else {
                response = await puter.ai.chat([
                    { role: 'system', content: systemPrompt },
                    { role: 'user', content: userMessage }
                ], { model: model });
            }
        } catch (aiErr) {
            if (aiErr.message && aiErr.message.includes('usage-limited-chat')) {
                throw new Error('Rate limit reached. Please try again later or use a different model.');
            } else if (aiErr.message && aiErr.message.includes('authentication')) {
                throw new Error('Not authenticated. Please sign in first.');
            } else {
                throw aiErr;
            }
        }
        
        removeTypingIndicator(typingId);
        addMessage(response.message?.content || response.toString(), 'ai');
        
    } catch (err) {
        removeTypingIndicator(typingId);
        console.error('AI Error:', err);
        addMessage(`Error: ${err.message}. Please check your connection or try a different model.`, 'error');
    }
}

function addMessage(content, type) {
    const container = document.getElementById('chatMessages');
    const div = document.createElement('div');
    div.className = 'flex items-start gap-3';
    
    const isUser = type === 'user';
    const isError = type === 'error';
    
    div.innerHTML = `
        <div class="w-8 h-8 ${isUser ? 'bg-gray-300 dark:bg-dark-700' : isError ? 'bg-red-500' : 'bg-gradient-to-br from-primary-500 to-primary-700'} rounded-full flex items-center justify-center shrink-0">
            ${isUser 
                ? '<svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>'
                : isError
                    ? '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                    : '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>'
            }
        </div>
        <div class="${isUser ? 'bg-gray-200 dark:bg-dark-800' : isError ? 'bg-red-100 dark:bg-red-900/30 border-red-200 dark:border-red-800' : 'bg-white dark:bg-dark-900 border-gray-200 dark:border-dark-800'} rounded-2xl ${isUser ? 'rounded-tr-none' : 'rounded-tl-none'} p-4 shadow-sm border max-w-2xl">
            <div class="markdown-body text-sm leading-relaxed">${isUser ? escapeHtml(content) : marked.parse(content)}</div>
        </div>
    `;
    
    container.appendChild(div);
    container.scrollTop = container.scrollHeight;
}

function addSystemMessage(content) {
    const container = document.getElementById('chatMessages');
    const div = document.createElement('div');
    div.className = 'text-center text-xs text-gray-400 my-4';
    div.textContent = content;
    container.appendChild(div);
    container.scrollTop = container.scrollHeight;
}

function showTypingIndicator() {
    const container = document.getElementById('chatMessages');
    const id = 'typing-' + Date.now();
    const div = document.createElement('div');
    div.id = id;
    div.className = 'flex items-start gap-3';
    div.innerHTML = `
        <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <div class="bg-white dark:bg-dark-900 rounded-2xl rounded-tl-none p-4 shadow-sm border border-gray-200 dark:border-dark-800">
            <div class="flex gap-1">
                <div class="w-2 h-2 bg-gray-400 rounded-full typing-dot"></div>
                <div class="w-2 h-2 bg-gray-400 rounded-full typing-dot"></div>
                <div class="w-2 h-2 bg-gray-400 rounded-full typing-dot"></div>
            </div>
        </div>
    `;
    container.appendChild(div);
    container.scrollTop = container.scrollHeight;
    return id;
}

function removeTypingIndicator(id) {
    const el = document.getElementById(id);
    if (el) el.remove();
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Resize Handler
function setupResizeHandler() {
    const handle = document.getElementById('resizeHandle');
    const sidebar = handle.previousElementSibling;
    
    let isResizing = false;
    
    handle.onmousedown = (e) => {
        isResizing = true;
        document.body.style.cursor = 'col-resize';
        e.preventDefault();
    };
    
    document.onmousemove = (e) => {
        if (!isResizing) return;
        const rect = sidebar.getBoundingClientRect();
        const newWidth = e.clientX - rect.left;
        if (newWidth >= 200 && newWidth <= 600) {
            sidebar.style.width = newWidth + 'px';
        }
    };
    
    document.onmouseup = () => {
        isResizing = false;
        document.body.style.cursor = '';
    };
}
</script>
</body>
</html>
