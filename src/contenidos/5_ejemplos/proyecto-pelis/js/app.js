import { getPopularMovies, searchMovies, getImageUrl } from "./api.js";

const moviesGrid = document.getElementById("movies");
const searchInput = document.getElementById("searchInput");
const loadingEl = document.getElementById("loading");
const errorEl = document.getElementById("error");
const errorMsgEl = document.getElementById("errorMessage");
const retryBtn = document.getElementById("retryBtn");

let searchTimeout = null;

// --- Utilidades UI ---
const show = (el) => el.classList.remove("hidden");
const hide = (el) => el.classList.add("hidden");

function showLoading() {
  show(loadingEl);
  hide(moviesGrid);
  hide(errorEl);
}
function showError(msg) {
  errorMsgEl.textContent = msg;
  show(errorEl);
  hide(loadingEl);
  hide(moviesGrid);
}
function showGrid() {
  show(moviesGrid);
  hide(loadingEl);
  hide(errorEl);
}

// --- Renderizado ---
function createMovieCard(movie) {
  const card = document.createElement("div");
  card.className = "movie-card";
  card.innerHTML = `
        <img
            src="${getImageUrl(movie.poster_path)}"
            alt="${movie.title}"
            class="movie-poster"
            loading="lazy"
        >
        <div class="movie-info">
            <p class="movie-title">${movie.title}</p>
            <p class="movie-rating">⭐ ${movie.vote_average?.toFixed(1) ?? "N/D"}</p>
        </div>
    `;
  return card;
}

function renderMovies(movies) {
  moviesGrid.innerHTML = "";

  if (movies.length === 0) {
    moviesGrid.innerHTML =
      '<p style="grid-column:1/-1;text-align:center;opacity:0.6">No se encontraron películas</p>';
    showGrid();
    return;
  }

  movies.forEach((movie) => moviesGrid.appendChild(createMovieCard(movie)));
  showGrid();
}

// --- Carga de datos ---
async function loadMovies(query = "") {
  showLoading();
  try {
    const movies = query ? await searchMovies(query) : await getPopularMovies();
    renderMovies(movies);
  } catch (err) {
    showError(
      err.message.includes("404")
        ? "Película no encontrada"
        : "Error al conectar con la API. Comprueba tu API key.",
    );
  }
}

// --- Eventos ---
searchInput.addEventListener("input", (e) => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => loadMovies(e.target.value), 500);
});

retryBtn.addEventListener("click", () => loadMovies(searchInput.value));

// --- Inicio ---
loadMovies();
