<template>
    <li class="requisito-item">{{ valor }}</li>
</template>

<script>
export default {
    name: "Requisito",
    props: {
        valor: {
            type: String,
            required: true
        }
    }
};
</script>

<style scoped>
.requisito-item {
    list-style: none;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    position: relative;
    padding-left: 2rem;
}

.requisito-item::before {
    content: "✔";
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #b80000;
    color: #ffffff;
    font-weight: bold;
    font-size: 0.9rem;
    width: 1.5rem;
    height: 1.5rem;
    border-radius: 50%;
    position: absolute;
    left: 0;
}
</style>
