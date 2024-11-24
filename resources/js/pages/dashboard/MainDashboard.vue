<template>
    <div
        class="loader"
        v-if="loading"
        style="
            width: 100%;
            height: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
        "
    >
        <div
            class="spinner-border text-danger"
            role="status"
            style="width: 100px; height: 100px"
        >
            <span class="sr-only">Cargando...</span>
        </div>
    </div>
    <div v-if="!loading">
        <h1>Bienvenido a Dashboard</h1>
        <router-link to="/">Ir a Home</router-link>
    </div>
</template>
