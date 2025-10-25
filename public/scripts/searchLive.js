document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.querySelector('.search-form input[name="query"]');
  const recipes = document.querySelectorAll(".recipe-card");

  if (!searchInput || recipes.length === 0) return;

  // Fonction pour normaliser le texte (supprime majuscules et accents)
  const normalize = (text) =>
    text
      .toLowerCase()
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "");

  const filterRecipes = () => {
    const search = normalize(searchInput.value.trim());

    recipes.forEach((recipe) => {
      const title = normalize(recipe.querySelector("h2").textContent);
      const description = normalize(recipe.querySelector("p").textContent);
      const category = normalize(recipe.dataset.category || "");

      // ✅ vérifie aussi la catégorie
      const match =
        search === "" ||
        title.includes(search) ||
        description.includes(search) ||
        category.includes(search);

      recipe.style.display = match ? "block" : "none";
    });
  };

  // 🔍 recherche en direct
  searchInput.addEventListener("input", filterRecipes);
});
