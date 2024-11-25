import Chart from "chart.js/auto";

function generarGraficoBarras(selector, etiquetas, datos) {
    // Generar colores aleatorios
    const coloresAleatorios = datos.map(() => {
        const randomColor = () => Math.floor(Math.random() * 256);
        return `rgba(${randomColor()}, ${randomColor()}, ${randomColor()}, 1)`;
    });

    // Crear el gráfico
    const ctx = document.querySelector(selector).getContext("2d");
    const myChart = new Chart(ctx, {
        type: "bar",
        data: {
            labels: etiquetas,
            datasets: [
                {
                    label: "Total",
                    data: datos,
                    backgroundColor: coloresAleatorios.map((color) =>
                        color.replace("1)", "0.3)")
                    ),
                    borderColor: coloresAleatorios,
                    borderWidth: 1,
                },
            ],
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
        },
    });

    return myChart;
}

export { generarGraficoBarras };
