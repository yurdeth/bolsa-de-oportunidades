<template>
    <!-- Navbar -->
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
            @click="toggleMenuMobile"
        ></MDBNavbarToggler>
        <MDBCollapse id="navbarColor01" v-model="collapse_mobile">
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
        <MDBCollapse
            id="navbarColor01"
            v-model="collapse_mobile"
            class="d-flex justify-content-end"
        >
            <MDBNavbarNav
                class="mb-2 mb-lg-0 d-flex flex-row justify-content-end w-100"
            >
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
        </MDBCollapse>
    </MDBNavbar>
    <!-- Navbar -->
    <MDBContainer class="my-5">
        <router-view></router-view>
    </MDBContainer>
    <!-- Footer -->
    <MDBFooter :text="['center', 'lg-start', 'muted']">
        <!-- Section: Social media -->
        <section
            class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom"
        >
            <!-- Left -->
            <div class="me-5 d-none d-lg-block">
                <span>Get connected with us on social networks:</span>
            </div>
            <!-- Left -->
            <!-- Right -->
            <div>
                <a href="" class="me-4 text-reset">
                    <MDBIcon iconStyle="fab" icon="facebook-f"></MDBIcon>
                </a>
                <a href="" class="me-4 text-reset">
                    <MDBIcon iconStyle="fab" icon="twitter"></MDBIcon>
                </a>
                <a href="" class="me-4 text-reset">
                    <MDBIcon iconStyle="fab" icon="google"></MDBIcon>
                </a>
                <a href="" class="me-4 text-reset">
                    <MDBIcon iconStyle="fab" icon="instagram"></MDBIcon>
                </a>
                <a href="" class="me-4 text-reset">
                    <MDBIcon iconStyle="fab" icon="linkedin"></MDBIcon>
                </a>
                <a href="" class="me-4 text-reset">
                    <MDBIcon iconStyle="fab" icon="github"></MDBIcon>
                </a>
            </div>
            <!-- Right -->
        </section>
        <!-- Section: Social media -->
        <!-- Section: Links  -->
        <section class="">
            <MDBContainer class="text-center text-md-start mt-5">
                <!-- Grid row -->
                <MDBRow class="mt-3">
                    <!-- Grid column -->
                    <MDBCol md="3" lg="4" xl="3" class="mx-auto mb-4">
                        <!-- Content -->
                        <h6 class="text-uppercase fw-bold mb-4">
                            <i class="fas fa-gem me-3"></i>Company name
                        </h6>
                        <p>
                            Here you can use rows and columns to organize your
                            footer content. Lorem ipsum dolor sit amet,
                            consectetur adipisicing elit.
                        </p>
                    </MDBCol>
                    <!-- Grid column -->
                    <!-- Grid column -->
                    <MDBCol md="2" lg="2" xl="2" class="mx-auto mb-4">
                        <!-- Links -->
                        <h6 class="text-uppercase fw-bold mb-4">Products</h6>
                        <p>
                            <a href="#!" class="text-reset">Angular</a>
                        </p>
                        <p>
                            <a href="#!" class="text-reset">React</a>
                        </p>
                        <p>
                            <a href="#!" class="text-reset">Vue</a>
                        </p>
                        <p>
                            <a href="#!" class="text-reset">Laravel</a>
                        </p>
                    </MDBCol>
                    <!-- Grid column -->
                    <!-- Grid column -->
                    <MDBCol md="3" lg="2" xl="2" class="mx-auto mb-4">
                        <!-- Links -->
                        <h6 class="text-uppercase fw-bold mb-4">
                            Useful links
                        </h6>
                        <p>
                            <a href="#!" class="text-reset">Pricing</a>
                        </p>
                        <p>
                            <a href="#!" class="text-reset">Settings</a>
                        </p>
                        <p>
                            <a href="#!" class="text-reset">Orders</a>
                        </p>
                        <p>
                            <a href="#!" class="text-reset">Help</a>
                        </p>
                    </MDBCol>
                    <!-- Grid column -->
                    <!-- Grid column -->
                    <MDBCol md="4" lg="3" xl="3" class="mx-auto mb-md-0 mb-4">
                        <!-- Links -->
                        <h6 class="text-uppercase fw-bold mb-4">Contact</h6>
                        <p>
                            <i class="fas fa-home me-3"></i> New York, NY 10012,
                            US
                        </p>
                        <p>
                            <i class="fas fa-envelope me-3"></i>
                            info@example.com
                        </p>
                        <p>
                            <MDBIcon icon="phone" class="me-3" /> + 01 234 567
                            88
                        </p>
                        <p>
                            <MDBIcon icon="print" class="me-3" /> + 01 234 567
                            89
                        </p>
                    </MDBCol>
                    <!-- Grid column -->
                </MDBRow>
                <!-- Grid row -->
            </MDBContainer>
        </section>
        <!-- Section: Links  -->
        <!-- Copyright -->
        <div
            class="text-center p-4"
            style="background-color: rgba(0, 0, 0, 0.05)"
        >
            © 2021 Copyright:
            <a class="text-reset fw-bold" href="https://mdbootstrap.com/"
                >MDBootstrap.com</a
            >
        </div>
        <!-- Copyright -->
    </MDBFooter>
    <!-- Footer -->
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
    MDBFooter,
    MDBContainer,
    MDBRow,
    MDBCol,
} from "mdb-vue-ui-kit";

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
        MDBFooter,
        MDBContainer,
        MDBRow,
        MDBCol,
    },
    data() {
        return {
            collapse_mobile: false,
        };
    },
    methods: {
        toggleMenuMobile() {
            this.collapse_mobile = !this.collapse_mobile;
        },
    },
};
</script>
