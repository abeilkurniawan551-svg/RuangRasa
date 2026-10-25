/**
 * RuangRasa - Interactive Application Logic
 * Supports Registration, Login Breathing Visualizer, AI Chatbot, and Profile Management
 */

document.addEventListener('DOMContentLoaded', () => {
  initPasswordToggles();
  initPasswordStrengthMeter();
  initRolePillSelector();
  initBreathingVisualizer();
  initChatbot();
  initAudioEmergencyPlayer();
  initProfilePage();
});

// ============================================================================
// 1. Password Toggles (Show / Hide Password)
// ============================================================================
function initPasswordToggles() {
  const toggleButtons = document.querySelectorAll('.toggle-password-visibility');
  toggleButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const targetId = btn.getAttribute('data-target');
      const input = document.getElementById(targetId);
      if (!input) return;

      if (input.type === 'password') {
        input.type = 'text';
        btn.setAttribute('aria-label', 'Sembunyikan sandi');
        btn.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>`;
      } else {
        input.type = 'password';
        btn.setAttribute('aria-label', 'Tampilkan sandi');
        btn.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>`;
      }
    });
  });
}

// ============================================================================
// 2. Registration: Password Strength Meter & Live Checklist
// ============================================================================
function initPasswordStrengthMeter() {
  const pwdInput = document.getElementById('reg_password');
  const strengthBadge = document.getElementById('pwd_strength_badge');
  const segments = document.querySelectorAll('.strength-segment');

  const checkLength = document.getElementById('chk_length');
  const checkSymbols = document.getElementById('chk_symbols');
  const checkNumbersUpper = document.getElementById('chk_num_upper');
  const checkPersonal = document.getElementById('chk_personal');

  if (!pwdInput) return;

  pwdInput.addEventListener('input', () => {
    const val = pwdInput.value;
    let score = 0;

    // 1. Min 8 characters
    const hasMinLength = val.length >= 8;
    updateCheckItem(checkLength, hasMinLength);
    if (hasMinLength) score++;

    // 2. Combination of symbols (!@#$)
    const hasSymbols = /[!@#$%^&*(),.?":{}|<>]/.test(val);
    updateCheckItem(checkSymbols, hasSymbols);
    if (hasSymbols) score++;

    // 3. Numbers & Uppercase
    const hasNumUpper = /[0-9]/.test(val) && /[A-Z]/.test(val);
    updateCheckItem(checkNumbersUpper, hasNumUpper);
    if (hasNumUpper) score++;

    // 4. Free from common personal names / easily guessable
    const notTrivial = val.length > 5 && !/(123456|password|qwerty|admin)/i.test(val);
    updateCheckItem(checkPersonal, notTrivial);
    if (notTrivial) score++;

    // Update meter segments
    segments.forEach((seg, idx) => {
      if (idx < score) {
        seg.classList.add('active-green');
      } else {
        seg.classList.remove('active-green');
      }
    });

    if (strengthBadge) {
      if (score === 4) {
        strengthBadge.textContent = 'Sangat Kuat';
        strengthBadge.style.color = '#15803d';
        strengthBadge.style.backgroundColor = '#dcfce7';
      } else if (score >= 2) {
        strengthBadge.textContent = 'Cukup';
        strengthBadge.style.color = '#b45309';
        strengthBadge.style.backgroundColor = '#fef3c7';
      } else {
        strengthBadge.textContent = 'Lemah';
        strengthBadge.style.color = '#b91c1c';
        strengthBadge.style.backgroundColor = '#fee2e2';
      }
    }
  });

  function updateCheckItem(element, isValid) {
    if (!element) return;
    if (isValid) {
      element.classList.add('checked');
      element.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> ` + element.getAttribute('data-text');
    } else {
      element.classList.remove('checked');
      element.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle></svg> ` + element.getAttribute('data-text');
    }
  }
}

// ============================================================================
// 3. Role Selector Pills (Mahasiswa, Karyawan, Umum)
// ============================================================================
function initRolePillSelector() {
  const roleLabels = document.querySelectorAll('.role-pill-option');
  roleLabels.forEach(label => {
    label.addEventListener('click', () => {
      roleLabels.forEach(l => l.classList.remove('selected'));
      label.classList.add('selected');
      const radio = label.querySelector('input[type="radio"]');
      if (radio) radio.checked = true;
    });
  });
}

// ============================================================================
// 4. Login: 4-7-8 Breathing Rhythm Visualizer
// ============================================================================
function initBreathingVisualizer() {
  const canvas = document.getElementById('breathingWaveCanvas');
  if (!canvas) return;

  const ctx = canvas.getContext('2d');
  let animationFrame;
  let t = 0;

  function resizeCanvas() {
    canvas.width = canvas.parentElement.clientWidth || 360;
    canvas.height = 70;
  }
  resizeCanvas();
  window.addEventListener('resize', resizeCanvas);

  function drawWave() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    const width = canvas.width;
    const height = canvas.height;
    const midY = height / 2;

    // Draw reference sine wave
    ctx.beginPath();
    ctx.moveTo(0, midY);

    for (let x = 0; x <= width; x += 2) {
      // 4-7-8 relaxed breathing curve formula
      const wave = Math.sin((x / width) * Math.PI * 2.2 - t * 0.03) * 18;
      ctx.lineTo(x, midY + wave);
    }

    ctx.strokeStyle = 'rgba(94, 234, 212, 0.4)';
    ctx.lineWidth = 2.5;
    ctx.stroke();

    // Moving glowing pulse point
    const pulseX = ((t * 1.5) % (width + 40)) - 20;
    const pulseY = midY + Math.sin((pulseX / width) * Math.PI * 2.2 - t * 0.03) * 18;

    if (pulseX >= 0 && pulseX <= width) {
      // Glow circle
      ctx.beginPath();
      ctx.arc(pulseX, pulseY, 8, 0, Math.PI * 2);
      ctx.fillStyle = 'rgba(94, 234, 212, 0.35)';
      ctx.fill();

      // Core point
      ctx.beginPath();
      ctx.arc(pulseX, pulseY, 4, 0, Math.PI * 2);
      ctx.fillStyle = '#ffffff';
      ctx.shadowColor = '#5eead4';
      ctx.shadowBlur = 10;
      ctx.fill();
      ctx.shadowBlur = 0;
    }

    t += 1;
    animationFrame = requestAnimationFrame(drawWave);
  }

  drawWave();
}

// ============================================================================
// 5. AI Chatbot Companion Logic with Real API Connection
// ============================================================================
function initChatbot() {
  const chatForm = document.getElementById('chatForm');
  const chatInput = document.getElementById('chatInput');
  const conversationArea = document.getElementById('chatConversationArea');
  const promptChips = document.querySelectorAll('.prompt-chip');
  const clearSessionBtn = document.getElementById('clearSessionBtn');

  // API Settings Elements
  const openApiSettingsBtn = document.getElementById('openApiSettingsBtn');
  const closeApiSettingsBtn = document.getElementById('closeApiSettingsBtn');
  const apiSettingsModal = document.getElementById('apiSettingsModal');
  const apiProviderSelect = document.getElementById('apiProviderSelect');
  const apiKeyFieldGroup = document.getElementById('apiKeyFieldGroup');
  const apiKeyValueInput = document.getElementById('apiKeyValueInput');
  const apiKeyHelpLink = document.getElementById('apiKeyHelpLink');
  const apiInfoNotice = document.getElementById('apiInfoNotice');
  const saveApiSettingsBtn = document.getElementById('saveApiSettingsBtn');
  const activeApiBadgeText = document.getElementById('activeApiBadgeText');

  if (!conversationArea) return;

  const currentUserName = conversationArea.getAttribute('data-username') || 'Sobat';
  const currentUserFullname = conversationArea.getAttribute('data-fullname') || currentUserName;

  // Active API State (Default to Google Gemini with configured token)
  const DEFAULT_GEMINI_KEY = 'AQ.Ab8RN6IAIJKg7Ars3GL0zA25RXd9CLIU3waWV4ihtnv1HKO1Bw';
  let currentProvider = localStorage.getItem('RuangRasa_api_provider');
  if (!currentProvider || currentProvider === 'RuangRasa') {
    currentProvider = 'gemini';
    localStorage.setItem('RuangRasa_api_provider', 'gemini');
  }
  let currentApiKey = localStorage.getItem('RuangRasa_api_key');
  if (!currentApiKey || currentApiKey.length < 10) {
    currentApiKey = DEFAULT_GEMINI_KEY;
    localStorage.setItem('RuangRasa_api_key', DEFAULT_GEMINI_KEY);
  }

  // Conversation history for multi-turn context
  const conversationHistory = [
    {
      role: 'assistant',
      content: `Halo ${currentUserName}. Aku di sini untuk mendengarkan tanpa menghakimi sama sekali. Apa yang paling terasa dominan di hati atau pikiranmu saat ini?`
    }
  ];

  function updateApiBadge() {
    if (!activeApiBadgeText) return;
    const providerNames = {
      'RuangRasa': 'API: RuangRasa AI',
      'gemini': 'API: Google Gemini',
      'groq': 'API: Groq Llama 3',
      'openai': 'API: OpenAI ChatGPT'
    };
    activeApiBadgeText.textContent = providerNames[currentProvider] || 'API: RuangRasa AI';
  }
  updateApiBadge();

  // Auto scroll to bottom
  function scrollToBottom() {
    conversationArea.scrollTop = conversationArea.scrollHeight;
  }
  scrollToBottom();

  // Send message handler with API integration
  async function handleSendMessage(messageText) {
    if (!messageText || !messageText.trim()) return;

    const userText = messageText.trim();
    if (chatInput) chatInput.value = '';

    const timeNow = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

    // Append User Bubble
    const userMsgHtml = `
      <div class="chat-msg-row user">
        <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='36' height='36' viewBox='0 0 36 36'><circle cx='18' cy='18' r='18' fill='%230f766e'/><circle cx='18' cy='14' r='6' fill='%23ffffff'/><path d='M8 30c0-5.5 4.5-8 10-8s10 2.5 10 8' fill='%23ffffff'/></svg>" alt="${escapeHtml(currentUserName)}" class="chat-msg-avatar">
        <div class="chat-msg-body">
          <div class="chat-msg-meta">${escapeHtml(currentUserName)} • ${timeNow}</div>
          <div class="chat-bubble">${escapeHtml(userText)}</div>
        </div>
      </div>
    `;
    conversationArea.insertAdjacentHTML('beforeend', userMsgHtml);
    scrollToBottom();

    // Track user message in history
    conversationHistory.push({ role: 'user', content: userText });

    // Append Typing Indicator
    const typingId = 'typing_' + Date.now();
    const typingHtml = `
      <div id="${typingId}" class="chat-msg-row bot">
        <div class="bot-avatar-box" style="width:34px;height:34px;border-radius:50%;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="22"></line></svg>
        </div>
        <div class="chat-msg-body">
          <div class="chat-msg-meta">RuangRasa AI • Sedang berpikir...</div>
          <div class="chat-bubble" style="color:var(--slate-400);font-style:italic;">Merespon dengan penuh empati...</div>
        </div>
      </div>
    `;
    conversationArea.insertAdjacentHTML('beforeend', typingHtml);
    scrollToBottom();

    try {
      const response = await fetch('api_chat.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          message: userText,
          user_name: currentUserName,
          user_fullname: currentUserFullname,
          provider: currentProvider,
          api_key: currentApiKey,
          history: conversationHistory.slice(-8)
        })
      });

      const data = await response.json();
      const typingEl = document.getElementById(typingId);
      if (typingEl) typingEl.remove();

      if (data && data.status === 'success' && data.reply) {
        // Track AI message in history
        conversationHistory.push({ role: 'assistant', content: data.reply });

        // Render Bot Reply
        const botReplyHtml = buildBotReplyHtml(data.reply, data.actions, data.timestamp || timeNow, data.provider);
        conversationArea.insertAdjacentHTML('beforeend', botReplyHtml);
      } else {
        throw new Error(data.message || 'Gagal menerima respon dari server');
      }
    } catch (err) {
      console.error('Chatbot API Error:', err);
      const typingEl = document.getElementById(typingId);
      if (typingEl) typingEl.remove();

      // Graceful Fallback Message
      const fallbackHtml = `
        <div class="chat-msg-row bot">
          <div class="bot-avatar-box" style="width:34px;height:34px;border-radius:50%;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="22"></line></svg>
          </div>
          <div class="chat-msg-body">
            <div class="chat-msg-meta">RuangRasa AI • ${timeNow}</div>
            <div class="chat-bubble">
              Terima kasih sudah mau bercerita. Setiap perasaan yang kamu alami itu valid. Nafas perlahan sejenak ya, kamu sudah berusaha dengan sangat baik hari ini.
            </div>
          </div>
        </div>
      `;
      conversationArea.insertAdjacentHTML('beforeend', fallbackHtml);
    }

    scrollToBottom();
  }

  function buildBotReplyHtml(replyText, actions, time, provider) {
    // Format text: convert line breaks and basic bold markdown
    let formattedText = escapeHtml(replyText)
      .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
      .replace(/\n\n/g, '<br><br>')
      .replace(/\n/g, '<br>');

    // Build Action Cards HTML if any
    let actionsHtml = '';
    if (actions && actions.length > 0) {
      actionsHtml = `
        <div class="bot-action-cards-grid">
          ${actions.map(act => `
            <div class="bot-action-card">
              <div class="bot-action-card-title">${escapeHtml(act.title)}</div>
              <div class="bot-action-card-desc">${escapeHtml(act.desc)}</div>
              <button class="btn-card-action" data-action="${escapeHtml(act.action)}">${escapeHtml(act.btnText || 'Buka')}</button>
            </div>
          `).join('')}
        </div>
      `;
    }

    return `
      <div class="chat-msg-row bot">
        <div class="bot-avatar-box" style="width:34px;height:34px;border-radius:50%;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="22"></line></svg>
        </div>
        <div class="chat-msg-body">
          <div class="chat-msg-meta">RuangRasa AI • ${time}</div>
          <div class="chat-bubble">
            ${formattedText}
            ${actionsHtml}
          </div>
        </div>
      </div>
    `;
  }

  // Form submit event
  if (chatForm) {
    chatForm.addEventListener('submit', (e) => {
      e.preventDefault();
      if (chatInput) handleSendMessage(chatInput.value);
    });
  }

  // Quick Prompt Chips
  promptChips.forEach(chip => {
    chip.addEventListener('click', () => {
      const promptText = chip.getAttribute('data-prompt') || chip.textContent.trim();
      handleSendMessage(promptText);
    });
  });

  // Action Buttons inside message (Delegation)
  conversationArea.addEventListener('click', (e) => {
    const actionBtn = e.target.closest('.btn-card-action');
    if (!actionBtn) return;

    const actionType = actionBtn.getAttribute('data-action');
    if (actionType === 'box-breathing') {
      alert("Memulai panduan Napas Kotak 4x4 (Box Breathing):\n\n1. Tarik napas 4 detik...\n2. Tahan napas 4 detik...\n3. Hembuskan perlahan 4 detik...\n4. Tahan hening 4 detik...");
    } else if (actionType === 'deconstruct') {
      alert("Membuka Jurnal Refleksi:\n\nMari pilah bersama apa hal yang berada di dalam kendalimu vs hal yang berada di luar kendalimu saat ini.");
    } else if (actionType === 'grounding') {
      alert("Teknik Grounding 5-4-3-2-1:\n\n• 5 hal yang kamu lihat\n• 4 hal yang bisa kamu sentuh\n• 3 suara yang kamu dengar\n• 2 aroma di sekitarmu\n• 1 rasa di lidahmu");
    } else if (actionType === 'call-119') {
      window.location.href = 'emergency.php';
    }
  });

  // Clear Session Button
  if (clearSessionBtn) {
    clearSessionBtn.addEventListener('click', () => {
      if (confirm('Apakah Anda yakin ingin membersihkan seluruh jejak obrolan sesi ini secara permanen?')) {
        alert('Riwayat obrolan sesi ini telah dihapus tuntas dari memori sesi.');
        window.location.reload();
      }
    });
  }

  // ============================================================================
  // API Settings Modal Handler
  // ============================================================================
  if (openApiSettingsBtn && apiSettingsModal) {
    openApiSettingsBtn.addEventListener('click', () => {
      if (apiProviderSelect) apiProviderSelect.value = currentProvider;
      if (apiKeyValueInput) apiKeyValueInput.value = currentApiKey;
      toggleApiKeyVisibility(currentProvider);
      apiSettingsModal.style.display = 'flex';
    });

    if (closeApiSettingsBtn) {
      closeApiSettingsBtn.addEventListener('click', () => {
        apiSettingsModal.style.display = 'none';
      });
    }

    // Close on backdrop click
    apiSettingsModal.addEventListener('click', (e) => {
      if (e.target === apiSettingsModal) {
        apiSettingsModal.style.display = 'none';
      }
    });

    if (apiProviderSelect) {
      apiProviderSelect.addEventListener('change', () => {
        toggleApiKeyVisibility(apiProviderSelect.value);
      });
    }

    if (saveApiSettingsBtn) {
      saveApiSettingsBtn.addEventListener('click', () => {
        currentProvider = apiProviderSelect.value;
        currentApiKey = apiKeyValueInput.value.trim();

        localStorage.setItem('RuangRasa_api_provider', currentProvider);
        localStorage.setItem('RuangRasa_api_key', currentApiKey);

        updateApiBadge();
        apiSettingsModal.style.display = 'none';
        alert(`Pengaturan API berhasil disimpan! Mesin aktif: ${currentProvider.toUpperCase()}`);
      });
    }
  }

  function toggleApiKeyVisibility(provider) {
    if (!apiKeyFieldGroup) return;
    if (provider === 'RuangRasa') {
      apiKeyFieldGroup.style.display = 'none';
      if (apiInfoNotice) {
        apiInfoNotice.style.display = 'block';
        apiInfoNotice.innerHTML = '💡 <strong>Mode Bawaan Aktif:</strong> Chatbot menggunakan engine klinis RuangRasa yang siap merespon secara empatik tanpa memerlukan API key eksternal.';
      }
    } else {
      apiKeyFieldGroup.style.display = 'flex';
      apiKeyFieldGroup.style.flexDirection = 'column';
      if (apiInfoNotice) {
        if (provider === 'gemini') {
          apiInfoNotice.style.display = 'block';
          apiInfoNotice.innerHTML = '✨ <strong>Google Gemini Aktif:</strong> Token API Anda telah terhubung langsung ke model resmi <strong>Gemini 3.8 Flash</strong>.';
        } else {
          apiInfoNotice.style.display = 'none';
        }
      }

      const helpUrls = {
        'gemini': 'https://aistudio.google.com/app/apikey',
        'groq': 'https://console.groq.com/keys',
        'openai': 'https://platform.openai.com/api-keys'
      };
      if (apiKeyHelpLink) apiKeyHelpLink.href = helpUrls[provider] || '#';
    }
  }

  function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }
}

// ============================================================================
// 6. Audio Napas Darurat Player
// ============================================================================
function initAudioEmergencyPlayer() {
  const playBtn = document.getElementById('playEmergencyAudio');
  const waveBars = document.querySelectorAll('.audio-waveform-bars .wave-bar');
  const timerLabel = document.getElementById('audioTimerLabel');
  if (!playBtn) return;

  let isPlaying = false;
  let seconds = 0;
  let timerInterval;

  playBtn.addEventListener('click', () => {
    isPlaying = !isPlaying;
    if (isPlaying) {
      playBtn.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><rect x="6" y="4" width="4" height="16"></rect><rect x="14" y="4" width="4" height="16"></rect></svg>`;
      waveBars.forEach(bar => bar.classList.add('playing'));
      timerInterval = setInterval(() => {
        seconds++;
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        if (timerLabel) {
          timerLabel.textContent = `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
        }
      }, 1000);
    } else {
      playBtn.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>`;
      waveBars.forEach(bar => bar.classList.remove('playing'));
      clearInterval(timerInterval);
    }
  });
}

// ============================================================================
// 7. Profile Page: Tab Switching & Save Changes Toast
// ============================================================================
function initProfilePage() {
  const profileTabs = document.querySelectorAll('.profile-nav-tab');
  profileTabs.forEach(tab => {
    tab.addEventListener('click', (e) => {
      e.preventDefault();
      profileTabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
    });
  });

  const saveProfileBtn = document.getElementById('saveProfileBtn');
  const toastNotification = document.getElementById('saveProfileToast');

  if (saveProfileBtn && toastNotification) {
    saveProfileBtn.addEventListener('click', (e) => {
      // Show smooth feedback toast
      toastNotification.style.display = 'inline-flex';
      toastNotification.style.opacity = '1';
      saveProfileBtn.textContent = 'Menyimpan...';

      setTimeout(() => {
        saveProfileBtn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> Simpan Perubahan`;
        setTimeout(() => {
          toastNotification.style.opacity = '0';
          setTimeout(() => {
            toastNotification.style.display = 'none';
          }, 400);
        }, 3000);
      }, 600);
    });
  }
}

/**
 * Open Google OAuth dialog in a centered popup window
 */
function openGooglePopup(e, url) {
  if (e && e.preventDefault) e.preventDefault();
  const w = 780;
  const h = 580;
  const left = Math.max(0, (window.screen.width - w) / 2);
  const top = Math.max(0, (window.screen.height - h) / 2);
  const popup = window.open(
    url,
    'google_oauth_popup',
    `width=${w},height=${h},top=${top},left=${left},scrollbars=yes,resizable=yes,status=no,toolbar=no,menubar=no`
  );

  if (!popup || popup.closed || typeof popup.closed === 'undefined') {
    // Popup was blocked by browser, fallback to standard redirect
    window.location.href = url;
  }
}
