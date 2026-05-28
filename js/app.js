const selectTipo = document.getElementById("tipo");
const selectCategoria = document.getElementById("categoria_id");

function filtrarCategorias(tipo) {
  const options = selectCategoria.querySelectorAll("option");
  options.forEach((opt) => {
    if (!opt.value) return;
    opt.hidden = opt.dataset.tipo !== tipo;
  });
  selectCategoria.value = "";
}

selectTipo.addEventListener("change", () => {
  filtrarCategorias(selectTipo.value);
});

const mesAtual =
  document.querySelector('input[name="mes"]')?.value ??
  new Date().toISOString().slice(0, 7);

const CORES = [
  "#2563eb",
  "#16a34a",
  "#dc2626",
  "#d97706",
  "#7c3aed",
  "#0891b2",
  "#db2777",
  "#65a30d",
];

async function fetchJSON(url) {
  const res = await fetch(url);
  return res.json();
}

async function carregarPizza() {
  const dados = await fetchJSON(`api/graficos.php?tipo=pizza&mes=${mesAtual}`);
  const canvas = document.getElementById("graficoPizza");
  const aviso = document.getElementById("vazioizza");

  if (!dados.labels.length) {
    canvas.style.display = "none";
    if (aviso) aviso.style.display = "block";
    return;
  }

  new Chart(canvas, {
    type: "doughnut",
    data: {
      labels: dados.labels,
      datasets: [
        {
          data: dados.valores,
          backgroundColor: CORES.slice(0, dados.labels.length),
          borderWidth: 2,
          borderColor: "#fff",
        },
      ],
    },
    options: {
      plugins: {
        legend: { position: "bottom", labels: { font: { size: 12 } } },
        tooltip: {
          callbacks: {
            label: (ctx) => {
              const v = ctx.parsed;
              return ` R$ ${v.toLocaleString("pt-BR", { minimumFractionDigits: 2 })}`;
            },
          },
        },
      },
    },
  });
}

async function carregarBarras() {
  const dados = await fetchJSON("api/graficos.php?tipo=barras");

  const labelsFormatados = dados.labels.map((mes) => {
    const [ano, num] = mes.split("-");
    const nomes = [
      "Jan",
      "Fev",
      "Mar",
      "Abr",
      "Mai",
      "Jun",
      "Jul",
      "Ago",
      "Set",
      "Out",
      "Nov",
      "Dez",
    ];
    return `${nomes[parseInt(num) - 1]}/${ano.slice(2)}`;
  });

  new Chart(document.getElementById("graficoBarras"), {
    type: "bar",
    data: {
      labels: labelsFormatados,
      datasets: [
        {
          label: "Entradas",
          data: dados.entradas,
          backgroundColor: "#16a34a",
          borderRadius: 4,
        },
        {
          label: "Saídas",
          data: dados.saidas,
          backgroundColor: "#dc2626",
          borderRadius: 4,
        },
      ],
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: (v) => "R$ " + v.toLocaleString("pt-BR"),
          },
        },
      },
      plugins: {
        legend: { position: "bottom" },
        tooltip: {
          callbacks: {
            label: (ctx) => {
              const v = ctx.parsed.y;
              return ` R$ ${v.toLocaleString("pt-BR", { minimumFractionDigits: 2 })}`;
            },
          },
        },
      },
    },
  });
}

carregarPizza();
carregarBarras();
