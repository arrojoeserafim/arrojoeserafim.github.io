<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Roleta da Sorte</title>
  <link rel="stylesheet" >
  <style>

    @media screen {
        
    
    * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  background-color: #0f1a20;
  color: #fff;
  font-family: Arial, sans-serif;
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
}

.app-container {
  background-color: #121212;
  border-radius: 20px;
  padding: 30px 20px;
  width: 90%;
  max-width: 400px;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
  text-align: center;
}

h1 {
  color: #FFD700;
  margin-bottom: 20px;
}

.roleta-container {
  position: relative;
  margin: 0 auto 20px auto;
  width: 300px;
  height: 300px;
}

.seta {
  position: absolute;
  top: -15px;
  left: 50%;
  transform: translateX(-50%);
  transform: rotate(59deg);
  width: 0;
  height: 0;
  border-left: 15px solid transparent;
  border-right: 15px solid transparent;
  border-bottom: 25px solid yellow;
  z-index: 10;
}

textarea {
  width: 100%;
  margin-top: 20px;
  padding: 10px;
  border-radius: 8px;
  border: none;
  resize: none;
  height: 100px;
  font-size: 14px;
}

button {
  margin-top: 15px;
  padding: 12px 20px;
  background-color: #FFD700;
  color: #000;
  border: none;
  border-radius: 20px;
  font-weight: bold;
  font-size: 16px;
  cursor: pointer;
}

button:hover {
  background-color: #e6c200;
}



}

  </style>
</head>
<body>
  <div class="app-container">
    <h1>🎉 Roleta da Sorte</h1>

    <div class="roleta-container">
      <div class="seta"></div>
      <canvas id="roleta" width="300" height="300"></canvas>
    </div>

    <textarea
      id="nomes"
      placeholder="Digite os nomes dos competidores, um por linha..."
    ></textarea>

    <button onclick="girarRoleta()">Girar</button>

    <p></p>

  </div>

  <script >
const canvas = document.getElementById("roleta");
const ctx = canvas.getContext("2d");
const mensagem = document.getElementById("mensagem");

let nomes = [];
let anguloAtual = 0;
let girando = false;

function desenharRoleta(nomes) {
  const total = nomes.length;
  const raio = canvas.width / 2;
  const centroX = canvas.width / 2;
  const centroY = canvas.height / 2;

  ctx.clearRect(0, 0, canvas.width, canvas.height);

  for (let i = 0; i < total; i++) {
    const anguloInicio = (2 * Math.PI / total) * i;
    const anguloFim = anguloInicio + 2 * Math.PI / total;

    ctx.fillStyle = `hsl(${(i * 360) / total}, 80%, 60%)`;

    ctx.beginPath();
    ctx.moveTo(centroX, centroY);
    ctx.arc(centroX, centroY, raio, anguloInicio, anguloFim);
    ctx.lineTo(centroX, centroY);
    ctx.fill();

    ctx.save();
    ctx.translate(centroX, centroY);
    ctx.rotate(anguloInicio + Math.PI / total);
    ctx.textAlign = "right";
    ctx.fillStyle = "#000";
    ctx.font = "bold 14px Arial";
    ctx.fillText(nomes[i], raio - 10, 5);
    ctx.restore();
  }
}

function girarRoleta() {
  if (girando) return;

  nomes = document
    .getElementById("nomes")
    .value.trim()
    .split("\n")
    .filter((n) => n.trim() !== "");

  if (nomes.length < 2) {
    mensagem.textContent = "Digite pelo menos dois nomes. Um em cada linha ";
    return;
  }

  desenharRoleta(nomes);

  const voltas = Math.floor(Math.random() * 5 + 5);
  const destino = Math.floor(Math.random() * nomes.length);
  const anguloDestino = (2 * Math.PI / nomes.length) * destino;

  const anguloFinal = 2 * Math.PI * voltas + anguloDestino;

  const duracao = 5000;
  const start = performance.now();
  girando = true;

  function animarRoleta(now) {
    const tempo = now - start;
    const progresso = Math.min(tempo / duracao, 1);
    const easeOut = 1 - Math.pow(1 - progresso, 3);

    const angulo = anguloAtual + (anguloFinal - anguloAtual) * easeOut;

    ctx.save();
    ctx.translate(canvas.width / 2, canvas.height / 2);
    ctx.rotate(angulo);
    ctx.translate(-canvas.width / 2, -canvas.height / 2);
    desenharRoleta(nomes);
    ctx.restore();

    if (progresso < 1) {
      requestAnimationFrame(animarRoleta);
    } else {
      anguloAtual = angulo % (2 * Math.PI);
      const vencedorIndex = nomes.length - Math.floor((anguloAtual / (2 * Math.PI)) * nomes.length) % nomes.length;

    }
  }

  requestAnimationFrame(animarRoleta);
}

  </script>
</body>
</html>
