<style>
html,
body {
    margin: 0;
    padding: 0;
    font-family: "Roboto", sans-serif;
    background-color: #f7f7f7; /* Fondo de la página */
    color: #4b4b4b; /* Texto Principal */
    overflow: hidden;
}

.loader {
    width: 100%;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    position: absolute;
    top: 0;
    left: 0;
    background-color: rgba(0, 0, 0, 0.5);
}

.loader .spinner-border {
    width: 100px;
    height: 100px;
}
</style>

<template>
    <div class="loader" v-if="loading">
        <div class="spinner-border text-danger" role="status">
            <span class="sr-only">Cargando...</span>
        </div>
    </div>
    <div>
        <h3>Companias</h3>
    </div>
</template>

<script>
export default {
    data() {
        return {
            loading: false,
        };
    },
};
</script>
