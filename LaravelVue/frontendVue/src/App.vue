<template>
  <v-app id="inspire">
    <v-app-bar flat>
      <v-container class="mx-auto d-flex align-center justify-center">
        <label>Talking IA</label>

      </v-container>
    </v-app-bar>

    <v-main class="bg-grey-lighten-3">
      <v-container>
        <v-row>
          <v-col>
            <v-sheet min-height="70vh" rounded="lg">
              <v-container>
                <v-row justify="center">
                  <v-col cols="12" md="8">
                    <!-- Mensagens do Chat -->
                    <div
                      v-for="(message, index) in messages"
                      :key="index"
                      class="message-container"
                    >
                      <v-card :class="['message-card', message.sender]">
                        <v-card-text>
                          <pre>{{ message.text }}</pre>
                        </v-card-text>
                      </v-card>
                    </div>
                  </v-col>
                </v-row>
              </v-container>
            </v-sheet>
          </v-col>
          <v-footer color="primary" app inset>
              <v-container>
                <v-row align="center" justify="center">
                  <v-col cols="12" md="6">
                    <v-form @submit.prevent="sendMessage">
                      <v-textarea
                        v-model="inputText"
                        label="Digite sua mensagem..."
                        outlined
                        auto-grow
                        rows="1"
                        hide-details
                        class="input-field"
                      ></v-textarea>
                      <v-btn type="submit" color="white" class="mt-2" block>
                        Enviar
                      </v-btn>
                    </v-form>
                  </v-col>
                </v-row>
              </v-container>
            </v-footer>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script setup>
  const links = ['Dashboard', 'Messages', 'Profile', 'Updates']
</script>

<script>
import axios from "axios";

  export default {
    data() {
      return {
        inputText: '',
        messages: [
          { text: 'Olá! Como posso ajudar você hoje?', sender: 'bot' },
        ],
      }
    },
    methods: {
      sendMessage() {
        if (this.inputText.trim()) {
          axios.post(this.$ApiAddress + '/api/perguntar', {
            pergunta: this.inputText,
        })
        .then(response => {
            // Adiciona a mensagem do usuário
            this.messages.push({ text: this.inputText, sender: 'user' })
            console.log('Resposta do backend:', response.data),
            console.log('Melhor resposta:', response.data.melhor_resposta),
         // Resposta
          setTimeout(() => {
            this.messages.push({
              text: response.data.melhor_resposta,
              sender: 'bot',
            })
          }, 1000)
        })
        .catch(error => {
            console.error('Erro ao enviar a pergunta:', error);
        });
          // Limpa o campo de entrada
          this.inputText = ''
        }
      },
    },
  }
</script>
<style scoped>
  .chat-container {
    padding-top: 64px; /* Ajuste para o cabeçalho fixo */
    padding-bottom: 120px; /* Ajuste para o campo de entrada fixo */
  }

  .message-container {
    margin-bottom: 16px;
  }

  .message-card {
    max-width: 70%;
    padding: 12px;
    border-radius: 12px;
  }

  .message-card.user {
    margin-left: auto;
    background-color: #e3f2fd;
  }

  .message-card.bot {
    margin-right: auto;
    background-color: #f5f5f5;
  }

  .input-field {
    border-radius: 8px;
  }
</style>
