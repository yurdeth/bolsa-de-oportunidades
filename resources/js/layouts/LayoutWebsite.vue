<template>
    <MDBNavbar
        container
        expand="lg"
        bg="light"
        class="d-flex justify-content-between"
    >
        <MDBNavbarBrand>
            <MDBIcon icon="mdb" iconStyle="fab" size="2x" />
        </MDBNavbarBrand>
        <MDBNavbarToggler
            target="#navbarColor01"
            @click="collapse7 = !collapse7"
        ></MDBNavbarToggler>
        <MDBCollapse id="navbarColor01" v-model="collapse7">
            <MDBNavbarNav class="mb-2 mb-lg-0">
                <MDBNavbarItem href="#" linkClass="link-secondary"
                    >Dashboard</MDBNavbarItem
                >
                <MDBNavbarItem href="#" linkClass="link-secondary"
                    >Team</MDBNavbarItem
                >
                <MDBNavbarItem href="#" linkClass="link-secondary"
                    >Projects</MDBNavbarItem
                >
            </MDBNavbarNav>
        </MDBCollapse>

        <MDBNavbarNav class="mb-2 mb-lg-0 d-flex flex-row">
            <MDBNavbarItem
                to="#"
                class="me-3 me-lg-0"
                linkClass="link-secondary"
                ><MDBIcon icon="shopping-cart"></MDBIcon
            ></MDBNavbarItem>
            <MDBNavbarItem
                to="#"
                class="me-3 me-lg-0"
                linkClass="link-secondary"
            >
                <MDBIcon icon="bell" />
                <MDBBadge notification color="danger" pill>1</MDBBadge>
            </MDBNavbarItem>
            <MDBNavbarItem href="#" class="me-3 me-lg-0">
                <img
                    src="https://mdbootstrap.com/img/Photos/Avatars/img (31).jpg"
                    class="rounded-circle"
                    height="22"
                    alt=""
                    loading="lazy"
                />
            </MDBNavbarItem>
        </MDBNavbarNav>
    </MDBNavbar>
    <router-view />
    <footer></footer>
</template>

<script>
import {
    MDBNavbar,
    MDBNavbarItem,
    MDBNavbarBrand,
    MDBNavbarNav,
    MDBNavbarToggler,
    MDBBadge,
    MDBIcon,
    MDBCollapse,
} from "mdb-vue-ui-kit";
import { ref } from "vue";

export default {
    components: {
        MDBNavbar,
        MDBNavbarItem,
        MDBNavbarBrand,
        MDBNavbarNav,
        MDBNavbarToggler,
        MDBBadge,
        MDBIcon,
        MDBCollapse,
    },
    setup() {
        const collapse7 = ref(false);

        return {
            collapse7,
        };
    },
};
</script>
