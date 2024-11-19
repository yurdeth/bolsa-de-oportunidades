import axios from "axios";

const api = axios.create({
    baseURL: "/api",
});

api.defaults.headers.common = {
    "Content-Type": "application/json",
    Accept: "application/json",
    Authorization: `Bearer ${localStorage.getItem("token")}`,
};

export { api };
