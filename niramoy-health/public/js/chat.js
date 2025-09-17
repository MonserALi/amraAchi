// Chat functionality
document.addEventListener('DOMContentLoaded', function () {
  // Initialize chat if chat container exists
  const chatContainer = document.getElementById('chat-container');
  if (chatContainer) {
    initializeChat();
  }

  // Function to initialize chat
  function initializeChat() {
    const chatMessages = document.getElementById('chat-messages');
    const chatInput = document.getElementById('chat-input');
    const sendButton = document.getElementById('send-button');
    const chatUserSelect = document.getElementById('chat-user-select');

    // Load chat history
    loadChatHistory();

    // Send message on button click
    if (sendButton) {
      sendButton.addEventListener('click', sendMessage);
    }

    // Send message on Enter key (but not Shift+Enter)
    if (chatInput) {
      chatInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
          e.preventDefault();
          sendMessage();
        }
      });
    }

    // Load chat history when user is selected
    if (chatUserSelect) {
      chatUserSelect.addEventListener('change', loadChatHistory);
    }

    // Auto-refresh chat messages every 5 seconds
    setInterval(loadChatHistory, 5000);
  }

  // Function to load chat history
  function loadChatHistory() {
    const chatMessages = document.getElementById('chat-messages');
    const chatUserSelect = document.getElementById('chat-user-select');

    if (!chatMessages || !chatUserSelect) return;

    const receiverId = chatUserSelect.value;
    if (!receiverId) return;

    // Show loading spinner
    chatMessages.innerHTML = '<div class="spinner"></div>';

    // Fetch chat history from API
    fetch(BASE_URL + 'api/chat/history?receiver_id=' + receiverId)
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        displayChatMessages(data);
      })
      .catch(error => {
        console.error('Error fetching chat history:', error);
        // Use mock data if API fails
        displayChatMessages(getMockChatMessages(receiverId));
      });
  }

  // Function to display chat messages
  function displayChatMessages(messages) {
    const chatMessages = document.getElementById('chat-messages');
    if (!chatMessages) return;

    // Clear loading spinner
    chatMessages.innerHTML = '';

    // If no messages found, show a message
    if (messages.length === 0) {
      chatMessages.innerHTML = `<p class="text-center text-muted">${isBangla ? 'কোন বার্তা নেই। একটি নতুন কথোপকথন শুরু করুন।' : 'No messages yet. Start a new conversation.'}</p>`;
      return;
    }

    // Display messages
    messages.forEach(message => {
      const messageDiv = document.createElement('div');
      messageDiv.className = 'chat-message ' + (message.sender_id === currentUserId ? 'sent' : 'received');

      const messageContent = document.createElement('div');
      messageContent.className = 'message-content';

      const messageText = document.createElement('p');
      messageText.textContent = message.message;

      const messageTime = document.createElement('span');
      messageTime.className = 'message-time';
      messageTime.textContent = formatDateTime(message.timestamp);

      messageContent.appendChild(messageText);
      messageContent.appendChild(messageTime);
      messageDiv.appendChild(messageContent);

      chatMessages.appendChild(messageDiv);
    });

    // Scroll to bottom of chat messages
    chatMessages.scrollTop = chatMessages.scrollHeight;
  }

  // Function to send a message
  function sendMessage() {
    const chatInput = document.getElementById('chat-input');
    const chatUserSelect = document.getElementById('chat-user-select');
    const sendButton = document.getElementById('send-button');

    if (!chatInput || !chatUserSelect || !sendButton) return;

    const message = chatInput.value.trim();
    const receiverId = chatUserSelect.value;

    if (!message || !receiverId) return;

    // Show loading state
    const originalContent = sendButton.innerHTML;
    sendButton.disabled = true;
    sendButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';

    // Send message to API
    fetch(BASE_URL + 'api/chat/send', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': getCsrfToken()
      },
      body: JSON.stringify({
        receiver_id: receiverId,
        message: message
      })
    })
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        // Clear input field
        chatInput.value = '';

        // Reload chat history
        loadChatHistory();
      })
      .catch(error => {
        console.error('Error sending message:', error);
        // Show error message
        showAlert(isBangla ? 'বার্তা পাঠাতে ব্যর্থ হয়েছে। আবার চেষ্টা করুন।' : 'Failed to send message. Please try again.', 'danger');
      })
      .finally(() => {
        // Restore button state
        sendButton.disabled = false;
        sendButton.innerHTML = originalContent;
      });
  }

  // Function to get mock chat messages
  function getMockChatMessages(receiverId) {
    // Return different mock messages based on receiver ID
    if (receiverId === '2') {
      return [
        {
          id: 1,
          sender_id: currentUserId,
          receiver_id: '2',
          message: isBangla ? 'ডাক্তার সাহেব, আমার প্রেসক্রিপশন সম্পর্কে একটি প্রশ্ন আছে।' : 'Hello Doctor, I have a question about my prescription.',
          timestamp: new Date(Date.now() - 3600000).toISOString()
        },
        {
          id: 2,
          sender_id: '2',
          receiver_id: currentUserId,
          message: isBangla ? 'হ্যালো রহিম, নিশ্চিত। আপনি কি জানতে চান?' : 'Hello Rahim, sure. What would you like to know?',
          timestamp: new Date(Date.now() - 3500000).toISOString()
        },
        {
          id: 3,
          sender_id: currentUserId,
          receiver_id: '2',
          message: isBangla ? 'আমি নিশ্চিত করতে চাই যে আমি কি খাবার আগে নাকি খাবার পরে ওষুধ খাব।' : 'I wanted to confirm if I should take the medicine before or after meals.',
          timestamp: new Date(Date.now() - 3400000).toISOString()
        },
        {
          id: 4,
          sender_id: '2',
          receiver_id: currentUserId,
          message: isBangla ? 'আপনার খাবারের পরে ওষুধ খাওয়া উচিত। দিনে দুইবার নির্ধারিত অনুযায়ী।' : 'You should take it after meals. Twice a day as prescribed.',
          timestamp: new Date(Date.now() - 3300000).toISOString()
        }
      ];
    } else if (receiverId === '5') {
      return [
        {
          id: 1,
          sender_id: currentUserId,
          receiver_id: '5',
          message: isBangla ? 'হ্যালো নার্স আয়েশা, আমার ডেকেয়ার অ্যাপয়েন্টমেন্ট পুনঃনির্ধারণ করতে হবে।' : 'Hello Nurse Ayesha, I need to reschedule my daycare appointment.',
          timestamp: new Date(Date.now() - 86400000).toISOString()
        },
        {
          id: 2,
          sender_id: '5',
          receiver_id: currentUserId,
          message: isBangla ? 'হ্যালো করিমা, নিশ্চিত। আপনি কখন পুনঃনির্ধারণ করতে চান?' : 'Hello Karima, sure. When would you like to reschedule to?',
          timestamp: new Date(Date.now() - 86300000).toISOString()
        },
        {
          id: 3,
          sender_id: currentUserId,
          receiver_id: '5',
          message: isBangla ? 'আমরা কি এটি কাল একই সময়ে সরাতে পারি?' : 'Can we move it to tomorrow at the same time?',
          timestamp: new Date(Date.now() - 86200000).toISOString()
        },
        {
          id: 4,
          sender_id: '5',
          receiver_id: currentUserId,
          message: isBangla ? 'আমার সময়সূচী চেক করে আপনাকে শীঘ্রই নিশ্চিত করব।' : 'Let me check my schedule and confirm with you shortly.',
          timestamp: new Date(Date.now() - 86100000).toISOString()
        }
      ];
    } else {
      return [];
    }
  }

  // Function to get CSRF token
  function getCsrfToken() {
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    return metaTag ? metaTag.getAttribute('content') : '';
  }

  // Function to format date and time
  function formatDateTime(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.round(diffMs / 60000);
    const diffHours = Math.round(diffMs / 3600000);
    const diffDays = Math.round(diffMs / 86400000);

    if (diffMins < 1) {
      return isBangla ? 'এখনই' : 'Just now';
    } else if (diffMins < 60) {
      return isBangla ? `${diffMins} মিনিট আগে` : `${diffMins} minute${diffMins > 1 ? 's' : ''} ago`;
    } else if (diffHours < 24) {
      return isBangla ? `${diffHours} ঘন্টা আগে` : `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`;
    } else if (diffDays < 7) {
      return isBangla ? `${diffDays} দিন আগে` : `${diffDays} day${diffDays > 1 ? 's' : ''} ago`;
    } else {
      return date.toLocaleDateString();
    }
  }

  // Check if language is Bangla
  const isBangla = getCookie('language') === 'bn';

  // Get current user ID from session
  const currentUserId = document.querySelector('meta[name="user-id"]')?.getAttribute('content') || '1';
});

// Global variables
let currentUserId;