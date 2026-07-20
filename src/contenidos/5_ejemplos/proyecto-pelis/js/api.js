import { API_KEY, BASE_URL, IMAGE_URL, NO_IMAGE } from "./config.js";

function buildUrl(endpoint, extraParams = {}) {
  const params = new URLSearchParams({
    api_key: API_KEY,
    language: "es-ES",
    ...extraParams,
  });
  console.log(`${BASE_URL}${endpoint}?${params}`);
  return `${BASE_URL}${endpoint}?${params}`;
}

export async function getPopularMovies() {
  const url = buildUrl("/movie/popular");
  const response = await fetch(url);
  if (!response.ok) throw new Error(`Error ${response.status}`);
  const data = await response.json();
  return data.results;
}

export async function searchMovies(query) {
  if (!query.trim()) return getPopularMovies();

  const url = buildUrl("/search/movie", { query });
  const response = await fetch(url);
  if (!response.ok) throw new Error(`Error ${response.status}`);
  const data = await response.json();
  return data.results;
}

export function getImageUrl(path) {
  return path ? `${IMAGE_URL}${path}` : NO_IMAGE;
}
