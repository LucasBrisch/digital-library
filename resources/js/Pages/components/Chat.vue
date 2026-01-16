<template>
  <div v-if="isOpen" class="chat-overlay" @click.self="closeChat">
    <div class="chat-container">
      <!-- Header -->
      <div class="chat-header">
        <div class="chat-header-info">
          <div class="user-avatar">{{ friendName.charAt(0).toUpperCase() }}</div>
          <div>
            <h3 class="friend-name">{{ friendName }}</h3>
            <span class="online-status">Online</span>
          </div>
        </div>
        <button @click="closeChat" class="close-button">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>

      <!-- Messages Area -->
      <div class="messages-container" ref="messagesContainer">
        <div v-if="messages.length === 0" class="no-messages">
          <p>Nenhuma mensagem ainda. Seja o primeiro a enviar!</p>
        </div>
        <div v-for="message in messages" :key="message.id" 
             :class="['message', message.sender_id === currentUserId ? 'sent' : 'received']">
          <div class="message-content">
            <p>{{ message.message }}</p>
            <span class="message-time">{{ formatTime(message.created_at) }}</span>
          </div>
        </div>
      </div>

      <!-- Input Area -->
      <div class="input-container">
        <div class="input-actions">
          <button class="action-button" title="Emoji">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"></circle>
              <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
              <line x1="9" y1="9" x2="9.01" y2="9"></line>
              <line x1="15" y1="9" x2="15.01" y2="9"></line>
            </svg>
          </button>
          <button class="action-button" title="Anexar arquivo">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
            </svg>
          </button>
          <button class="action-button" title="Imagem">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
              <circle cx="8.5" cy="8.5" r="1.5"></circle>
              <polyline points="21 15 16 10 5 21"></polyline>
            </svg>
          </button>
        </div>
        <input 
          v-model="newMessage" 
          @keypress.enter="sendMessage"
          type="text" 
          class="message-input" 
          placeholder="Enviar Mensagem"
        />
        <button @click="sendMessage" class="send-button" :disabled="!newMessage.trim()">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="22" y1="2" x2="11" y2="13"></line>
            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, nextTick, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

const props = defineProps({
  isOpen: Boolean,
  friendId: Number,
  friendName: String,
  currentUserId: Number
});

const emit = defineEmits(['close']);

const messages = ref([]);
const newMessage = ref('');
const messagesContainer = ref(null);
let refreshInterval = null;

const closeChat = () => {
  emit('close');
};

const loadMessages = async () => {
  if (!props.friendId) return;
  
  try {
    const response = await axios.get(`/chat/messages/${props.friendId}`);
    messages.value = response.data;
    await nextTick();
    scrollToBottom();
  } catch (error) {
    console.error('Error loading messages:', error);
  }
};

const sendMessage = async () => {
  if (!newMessage.value.trim()) return;

  try {
    const response = await axios.post('/chat/messages', {
      receiver_id: props.friendId,
      message: newMessage.value
    });
    
    messages.value.push(response.data);
    newMessage.value = '';
    await nextTick();
    scrollToBottom();
  } catch (error) {
    console.error('Error sending message:', error);
    alert('Erro ao enviar mensagem. Tente novamente.');
  }
};

const scrollToBottom = () => {
  if (messagesContainer.value) {
    messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
  }
};

const formatTime = (datetime) => {
  const date = new Date(datetime);
  const now = new Date();
  const diffMs = now - date;
  const diffMins = Math.floor(diffMs / 60000);
  
  if (diffMins < 1) return 'Agora';
  if (diffMins < 60) return `${diffMins}m atrás`;
  
  const diffHours = Math.floor(diffMins / 60);
  if (diffHours < 24) return `${diffHours}h atrás`;
  
  return date.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit' });
};

watch(() => props.isOpen, (newVal) => {
  if (newVal) {
    loadMessages();
    // Refresh messages every 3 seconds when chat is open
    refreshInterval = setInterval(loadMessages, 3000);
  } else {
    if (refreshInterval) {
      clearInterval(refreshInterval);
      refreshInterval = null;
    }
  }
});

onUnmounted(() => {
  if (refreshInterval) {
    clearInterval(refreshInterval);
  }
});
</script>

<style scoped>
.chat-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 1rem;
}

.chat-container {
  background: white;
  border-radius: 16px;
  width: 100%;
  max-width: 450px;
  height: 600px;
  display: flex;
  flex-direction: column;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
  animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.chat-header {
  padding: 1.25rem 1.5rem;
  background: linear-gradient(135deg, #1E3A34 0%, #2d5449 100%);
  color: white;
  border-radius: 16px 16px 0 0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.chat-header-info {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.user-avatar {
  width: 45px;
  height: 45px;
  border-radius: 50%;
  background: linear-gradient(135deg, #3FA796, #5bc4b1);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
  font-weight: 600;
  color: white;
  border: 3px solid rgba(255, 255, 255, 0.3);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.friend-name {
  font-size: 1.1rem;
  font-weight: 600;
  margin: 0;
  color: white;
}

.online-status {
  font-size: 0.8rem;
  color: #a8e6d7;
  display: flex;
  align-items: center;
  gap: 0.4rem;
}

.online-status::before {
  content: '';
  width: 8px;
  height: 8px;
  background: #4ade80;
  border-radius: 50%;
  display: inline-block;
  box-shadow: 0 0 6px #4ade80;
}

.close-button {
  background: rgba(255, 255, 255, 0.15);
  border: none;
  width: 36px;
  height: 36px;
  border-radius: 8px;
  color: white;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background-color 0.2s;
}

.close-button:hover {
  background: rgba(255, 255, 255, 0.25);
}

.messages-container {
  flex: 1;
  overflow-y: auto;
  padding: 1.5rem;
  background: #f8fafa;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.messages-container::-webkit-scrollbar {
  width: 6px;
}

.messages-container::-webkit-scrollbar-track {
  background: #e6f2ef;
}

.messages-container::-webkit-scrollbar-thumb {
  background: #3FA796;
  border-radius: 3px;
}

.no-messages {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
  color: #999;
  text-align: center;
  padding: 2rem;
}

.message {
  display: flex;
  margin-bottom: 0.5rem;
}

.message.sent {
  justify-content: flex-end;
}

.message.received {
  justify-content: flex-start;
}

.message-content {
  max-width: 70%;
  padding: 0.85rem 1.1rem;
  border-radius: 16px;
  word-wrap: break-word;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
}

.message.sent .message-content {
  background: linear-gradient(135deg, #3FA796, #4bb5a3);
  color: white;
  border-bottom-right-radius: 4px;
}

.message.received .message-content {
  background: white;
  color: #2E2E2E;
  border: 1px solid #e6f2ef;
  border-bottom-left-radius: 4px;
}

.message-content p {
  margin: 0 0 0.3rem 0;
  font-size: 0.95rem;
  line-height: 1.4;
}

.message-time {
  font-size: 0.7rem;
  opacity: 0.75;
  display: block;
  margin-top: 0.25rem;
}

.input-container {
  padding: 1rem 1.5rem;
  background: white;
  border-top: 1px solid #e6f2ef;
  display: flex;
  gap: 0.75rem;
  align-items: center;
  border-radius: 0 0 16px 16px;
}

.input-actions {
  display: flex;
  gap: 0.5rem;
}

.action-button {
  background: transparent;
  border: none;
  padding: 0.5rem;
  cursor: pointer;
  color: #666;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 6px;
  transition: all 0.2s;
}

.action-button:hover {
  background: #f0f0f0;
  color: #3FA796;
}

.message-input {
  flex: 1;
  padding: 0.85rem 1.1rem;
  border: 2px solid #e6f2ef;
  border-radius: 24px;
  font-size: 0.95rem;
  outline: none;
  transition: border-color 0.2s;
  background: #f8fafa;
}

.message-input:focus {
  border-color: #3FA796;
  background: white;
}

.send-button {
  background: linear-gradient(135deg, #3FA796, #4bb5a3);
  border: none;
  width: 42px;
  height: 42px;
  border-radius: 50%;
  color: white;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
  box-shadow: 0 2px 8px rgba(63, 167, 150, 0.3);
}

.send-button:hover:not(:disabled) {
  transform: scale(1.05);
  box-shadow: 0 4px 12px rgba(63, 167, 150, 0.4);
}

.send-button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

@media (max-width: 480px) {
  .chat-container {
    max-width: 100%;
    height: 100%;
    border-radius: 0;
  }

  .chat-header {
    border-radius: 0;
  }

  .input-container {
    border-radius: 0;
  }

  .input-actions {
    display: none;
  }
}
</style>
