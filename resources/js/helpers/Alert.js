import { Notyf } from "notyf";
import "notyf/notyf.min.css";

const notyf = new Notyf({
  duration: 6000,
  position: {
    x: "right",
    y: "top",
  },
  types: [
    {
      type: "info",
      background: "linear-gradient(45deg, #17a2b8, #85d1e0)", // Gradiente azul intenso
      icon: {
        className: "material-icons",
        tagName: "i",
        text: "info",
      },
    },
    {
      type: "success",
      background: "linear-gradient(45deg, #28a745, #7ddf7d)", // Gradiente verde intenso
      icon: {
        className: "material-icons",
        tagName: "i",
        text: "check",
      },
    },
    {
      type: "warning",
      background: "linear-gradient(45deg, #ffc107, #ffe68a)", // Gradiente amarillo intenso
      icon: {
        className: "material-icons",
        tagName: "i",
        text: "warning",
      },
    },
    {
      type: "error",
      background: "linear-gradient(45deg, #dc3545, #ff6666)", // Gradiente rojo intenso
      icon: {
        className: "material-icons",
        tagName: "i",
        text: "error",
      },
    },
    {
      type: "productAgotado",
      background: "linear-gradient(45deg, #dc3545, #ff6666)", // Gradiente rojo intenso
      icon: {
        className: "material-icons",
        tagName: "i",
        text: "production_quantity_limits",
      },
    },
    {
      type: "productPorAgotar",
      background: "linear-gradient(45deg, #007bff, #7db9e8)", // Gradiente azul intenso,
      icon: {
        className: "material-icons",
        tagName: "i",
        text: "inventory_2",
      },
    },
  ],
});

const Alert = (title, message, type) => {
  notyf.open({
    type: type,
    message: `<b style="color: white;font-size: 18px">${title}</b><br>${message}`,
  });
};

export default Alert;
