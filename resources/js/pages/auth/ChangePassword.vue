<template>
  <br class=" p-5 m-5">
  <div class="change-password">
    <h1>Cambiar Contraseña</h1>
    <form @submit.prevent="submitForm">
      <div class="form-group">
        <label for="newPassword">Nueva Contraseña</label>
        <input
          type="password"
          id="newPassword"
          v-model="newPassword"
          required
          placeholder="Ingresa la nueva contraseña"
        />
      </div>

      <div class="form-group">
        <label for="confirmPassword">Confirmar Contraseña</label>
        <input
          type="password"
          id="confirmPassword"
          v-model="confirmPassword"
          required
          placeholder="Confirma la nueva contraseña"
        />
      </div>

      <div v-if="passwordError" class="error-message">
        Las contraseñas no coinciden.
      </div>

      <div v-if="email" class="email-info">
        <p>Correo: {{ email }}</p>
      </div>

      <button type="submit" :disabled="isSubmitting">Cambiar Contraseña</button>
    </form>
  </div>
</template>

<script>

import { api } from "../../api";
export default {
  data() {
    return {
      newPassword: "",
      confirmPassword: "",
      passwordError: false,
      isSubmitting: false,
    };
  },
  computed: {
    email() {
      return this.$route.query.email; // Obtener el email de los query parameters
    },
  },
  methods: {
    // Función para manejar el envío del formulario
    async submitForm() {
      // Verificar que las contraseñas coinciden
      if (this.newPassword !== this.confirmPassword) {
        this.passwordError = true;
        return;
      }
      this.passwordError = false;
      this.isSubmitting = true;

      try {
        const response = await api.post("/changePassword",{email: this.email, newPassword: this.newPassword});

        if (response.data.success) {
          alert("Contraseña cambiada con exito.");
          this.$router.push('/login'); 
        } else {
          alert("Hubo un error al cambiar la contraseña.");
          console.log(response.data);
        }
      } catch (error) {
        console.error("Error al cambiar la contraseña:", error);
        alert("Hubo un error en la solicitud.");
      } finally {
        this.isSubmitting = false;
      }
    },

    // Función para llamar al controlador (simulada aquí como un método)
    async changePassword({ email, newPassword }) {
      // Simulación de llamada API al backend (puedes usar Axios o Fetch)
      return new Promise((resolve, reject) => {
        setTimeout(() => {
          // Aquí harías la llamada al backend para cambiar la contraseña
          // Ejemplo usando Fetch o Axios
          resolve({ success: true }); // Respuesta simulada exitosa
        }, 2000);
      });
    },
  },
};
</script>

<style scoped>
/* Estilos básicos para el formulario */
.change-password {
  max-width: 400px;
  margin: 0 auto;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 5px;
  background-color: #f9f9f9;
}

h1 {
  text-align: center;
}

.form-group {
  margin-bottom: 15px;
}

label {
  display: block;
  margin-bottom: 5px;
}

input {
  width: 100%;
  padding: 10px;
  margin-top: 5px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

button {
  width: 100%;
  padding: 10px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

.error-message {
  color: red;
  font-size: 14px;
  margin-top: 10px;
}

.email-info {
  margin-top: 10px;
  font-size: 14px;
  color: #555;
}
</style>
