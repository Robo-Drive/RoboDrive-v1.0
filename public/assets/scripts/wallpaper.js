const canvas = document.getElementById("myCanvas");
const ctx = canvas.getContext("2d");

const tipos = ["triangulo", "quadrado", "retangulo", "losango", "pentagono", "hexagono"];
let formas = [];
let largura = 0;
let altura = 0;
let dpr = 1;
let quantidadeAlvo = 0;

function aleatorio(min, max)
{
	return Math.random() * (max - min) + min;
}

function inteiroAleatorio(min, max)
{
	return Math.floor(aleatorio(min, max + 1));
}

function escolherTipo()
{
	return tipos[inteiroAleatorio(0, tipos.length - 1)];
}

function corVermelhoCinza()
{
	const saturacao = Math.floor(aleatorio(0, 100));
	const luminosidade = Math.floor(aleatorio(12, 62));
	const alpha = aleatorio(0.35, 0.95).toFixed(2);

	return `hsla(0, ${saturacao}%, ${luminosidade}%, ${alpha})`;
}

function criarForma(index)
{
	const tipo = escolherTipo();
	const base = Math.min(largura, altura);
	const tamanho = aleatorio(base * 0.05, base * 0.18);
	const larguraForma = tipo === "retangulo" ? tamanho * aleatorio(0.9, 1.9) : tamanho;
	const alturaForma = tipo === "retangulo" ? tamanho * aleatorio(0.55, 1.25) : tamanho;
	const margemX = Math.max(base * 0.06, larguraForma * 0.9);
	const margemY = Math.max(base * 0.06, alturaForma * 0.9);
	const margemSaida = Math.max(base * 0.12, Math.max(larguraForma, alturaForma));
	const borda = inteiroAleatorio(0, 3);
	const velocidadeBase = aleatorio(base * 0.00035, base * 0.0008);
	let x;
	let y;
	let vx;
	let vy;

	if (borda === 0)
	{
		x = -margemSaida;
		y = aleatorio(margemY, Math.max(margemY + 1, altura - margemY));
		vx = velocidadeBase;
		vy = aleatorio(-velocidadeBase * 0.25, velocidadeBase * 0.25);
	}
	else if (borda === 1)
	{
		x = largura + margemSaida;
		y = aleatorio(margemY, Math.max(margemY + 1, altura - margemY));
		vx = -velocidadeBase;
		vy = aleatorio(-velocidadeBase * 0.25, velocidadeBase * 0.25);
	}
	else if (borda === 2)
	{
		x = aleatorio(margemX, Math.max(margemX + 1, largura - margemX));
		y = -margemSaida;
		vx = aleatorio(-velocidadeBase * 0.25, velocidadeBase * 0.25);
		vy = velocidadeBase;
	}
	else
	{
		x = aleatorio(margemX, Math.max(margemX + 1, largura - margemX));
		y = altura + margemSaida;
		vx = aleatorio(-velocidadeBase * 0.25, velocidadeBase * 0.25);
		vy = -velocidadeBase;
	}

	return {
		tipo,
		x,
		y,
		largura: larguraForma,
		altura: alturaForma,
		rotacao: aleatorio(0, Math.PI * 2),
		velocidadeRotacao: aleatorio(-0.0009, 0.0009),
		vx,
		vy,
		cor: corVermelhoCinza(),
		espessura: aleatorio(1.4, 4.5)
	};
}

function criarFormas()
{
	quantidadeAlvo = Math.max(28, Math.floor((largura * altura) / 22000));
	formas = Array.from({ length: quantidadeAlvo }, (_, index) => criarForma(index));
}

function redimensionarCanvas()
{
	dpr = window.devicePixelRatio || 1;
	largura = window.innerWidth;
	altura = window.innerHeight;

	canvas.width = Math.floor(largura * dpr);
	canvas.height = Math.floor(altura * dpr);
	canvas.style.width = `${largura}px`;
	canvas.style.height = `${altura}px`;

	ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
	ctx.lineJoin = "round";
	ctx.lineCap = "round";

	criarFormas();
}

function desenharGradeDeFundo()
{
	const gradiente = ctx.createLinearGradient(0, 0, largura, altura);
	gradiente.addColorStop(0, "#000000");
	gradiente.addColorStop(0.55, "#0b0505");
	gradiente.addColorStop(1, "#1d0505");

	ctx.fillStyle = gradiente;
	ctx.fillRect(0, 0, largura, altura);
}

function desenharPoligono(forma, tempo)
{
	const ladosPorTipo = {
		triangulo: 3,
		quadrado: 4,
		retangulo: 4,
		losango: 4,
		pentagono: 5,
		hexagono: 6
	};

	const lados = ladosPorTipo[forma.tipo] || 4;
	const deslocamento = Math.sin(tempo * 0.001 + forma.x * 0.01 + forma.y * 0.01) * 4;
	const cx = forma.x + deslocamento;
	const cy = forma.y + deslocamento * 0.4;
	const rotacao = forma.rotacao + tempo * forma.velocidadeRotacao;

	ctx.save();
	ctx.translate(cx, cy);
	ctx.rotate(rotacao);
	ctx.strokeStyle = forma.cor;
	ctx.lineWidth = forma.espessura;
	ctx.beginPath();

	if (forma.tipo === "retangulo")
	{
		const larguraRet = forma.largura;
		const alturaRet = forma.altura;
		ctx.rect(-larguraRet / 2, -alturaRet / 2, larguraRet, alturaRet);
	}
	else if (forma.tipo === "losango")
	{
		const raioX = forma.largura * 0.65;
		const raioY = forma.altura * 0.65;
		ctx.moveTo(0, -raioY);
		ctx.lineTo(raioX, 0);
		ctx.lineTo(0, raioY);
		ctx.lineTo(-raioX, 0);
		ctx.closePath();
	}
	else
	{
		const raio = forma.largura * (forma.tipo === "quadrado" ? 0.58 : 0.72);
		const angulo = (Math.PI * 2) / lados;

		for (let i = 0; i < lados; i++)
		{
			const x = Math.cos(i * angulo - Math.PI / 2) * raio;
			const y = Math.sin(i * angulo - Math.PI / 2) * raio;

			if (i === 0)
			{
				ctx.moveTo(x, y);
			}
			else
			{
				ctx.lineTo(x, y);
			}
		}

		ctx.closePath();
	}

	ctx.stroke();
	ctx.restore();
}

function desenhar(tempo)
{
	desenharGradeDeFundo();

	for (const forma of formas)
	{
		desenharPoligono(forma, tempo);
	}
}

function animar(tempo)
{
	desenhar(tempo);

	formas = formas.filter((forma) =>
	{
		forma.x += forma.vx;
		forma.y += forma.vy;

		const margem = Math.max(forma.largura, forma.altura) * 1.4;
		const saiuHorizontalmente = forma.x < -margem || forma.x > largura + margem;
		const saiuVerticalmente = forma.y < -margem || forma.y > altura + margem;

		return !(saiuHorizontalmente || saiuVerticalmente);
	});

	while (formas.length < quantidadeAlvo)
	{
		formas.push(criarForma(formas.length));
	}

	requestAnimationFrame(animar);
}

window.addEventListener("resize", redimensionarCanvas);
redimensionarCanvas();
requestAnimationFrame(animar);
