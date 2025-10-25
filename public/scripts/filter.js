document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.querySelector('.search-form input[name="query"]');
    const recipes = document.querySelectorAll(".recipe-card");
    const filterButtons = document.querySelectorAll(".filter");

    if (!searchInput || recipes.length === 0) return;

    // Normalisation : supprime accents et majuscules
    const normalize = (text) =>
        text.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

    let activeCategory = "all";

    // Fonction principale de filtrage
    const filterRecipes = () => {
        const search = normalize(searchInput.value.trim());

        recipes.forEach((recipe) => {
            const title = normalize(recipe.querySelector("h2").textContent);
            const description = normalize(recipe.querySelector("p").textContent);
            const category = normalize(recipe.dataset.category || "");
            const time = parseInt(recipe.dataset.time || "0", 10);
            const prep = parseInt(recipe.dataset.prep || "0", 10); // ✅ nouveau champ : temps de préparation

            // --- Vérifie la catégorie ---
            let matchCategory = false;

            if (activeCategory === "all") {
                matchCategory = true;
            }
            // ✅ Catégorie spéciale : plats rapides (-15 min) — basé sur préparation uniquement
            else if (activeCategory === "rapide") {
                matchCategory = prep <= 15;
            }
            else {
                matchCategory = category === activeCategory;
            }

            // --- Vérifie la recherche texte ---
            const matchSearch =
                search === "" ||
                title.includes(search) ||
                description.includes(search) ||
                category.includes(search);

            // ✅ Affiche uniquement si les deux conditions sont vraies
            recipe.style.display = matchCategory && matchSearch ? "block" : "none";
        });
    };

    // 🔍 Recherche en direct
    searchInput.addEventListener("input", filterRecipes);

    // 🧭 Filtres par catégorie
    filterButtons.forEach((btn) => {
        btn.addEventListener("click", () => {
            filterButtons.forEach((b) => b.classList.remove("active"));
            btn.classList.add("active");
            activeCategory = normalize(btn.dataset.category);
            filterRecipes();
        });
    });
});







