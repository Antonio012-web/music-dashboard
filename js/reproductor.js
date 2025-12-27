const reproductor = document.getElementById("reproductor-global");
const audio = document.getElementById("audio-global");
const btnPlay = document.getElementById("reproductor-play");
const titulo = document.getElementById("reproductor-titulo");
const portada = document.getElementById("reproductor-portada");
const progreso = document.getElementById("reproductor-progreso");
const volumen = document.getElementById("reproductor-volumen");

function cargarCancion(tituloCancion, archivo, portadaUrl) {
  titulo.textContent = tituloCancion;
  portada.src = portadaUrl;
  audio.src = archivo;
  audio.play();
  btnPlay.innerHTML = `<i class="bi bi-pause-fill"></i>`;
  reproductor.style.display = "flex";
}

btnPlay.addEventListener("click", () => {
  if (audio.paused) {
    audio.play();
    btnPlay.innerHTML = `<i class="bi bi-pause-fill"></i>`;
  } else {
    audio.pause();
    btnPlay.innerHTML = `<i class="bi bi-play-fill"></i>`;
  }
});

audio.addEventListener("timeupdate", () => {
  progreso.max = audio.duration;
  progreso.value = audio.currentTime;
});

progreso.addEventListener("input", () => {
  audio.currentTime = progreso.value;
});

volumen.addEventListener("input", () => {
  audio.volume = volumen.value;
});
