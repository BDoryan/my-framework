<?php
$title = htmlspecialchars($props['title'] ?? 'Galerie de produits');
$categoryFilter = htmlspecialchars($props['category'] ?? 'all');
?>
<script defer>
    if (!customElements.get("product-gallery")) {
        class ProductGallery extends HTMLElement {
            connectedCallback() {
                // Lecture des attributs
                this.title = this.getAttribute("title") || "Galerie de produits";
                this.categoryFilter = this.getAttribute("category") || "all";
                this.products = this.parseProductsAttribute(this.getAttribute("products"));

                // Construction du DOM
                this.innerHTML = `
        <div class="product-gallery">
          <header>
            <h3>${this.title}</h3>
            <select class="category-filter">
              <option value="all">Toutes les catégories</option>
              <option value="vetement">Vêtements</option>
              <option value="accessoire">Accessoires</option>
              <option value="chaussure">Chaussures</option>
            </select>
          </header>
          <div class="product-grid"></div>
        </div>
      `;

                this.render(this.categoryFilter);

                // Écoute du filtre
                this.querySelector(".category-filter").addEventListener("change", e => {
                    this.render(e.target.value);
                });
            }

            parseProductsAttribute(value) {
                try {
                    return JSON.parse(value) || [];
                } catch (e) {
                    console.warn("Produit(s) invalide(s) dans l'attribut 'products' :", e);
                    return [];
                }
            }

            render(filter = "all") {
                const grid = this.querySelector(".product-grid");
                grid.innerHTML = "";

                const visible = this.products.filter(p => filter === "all" || p.category === filter);
                if (visible.length === 0) {
                    grid.innerHTML = "<p class='no-products'>Aucun produit trouvé.</p>";
                    return;
                }

                for (const product of visible) {
                    const card = document.createElement("product-card");
                    card.setAttribute("name", product.name);
                    card.setAttribute("price", product.price);
                    card.setAttribute("img", product.img);
                    card.setAttribute("category", product.category);
                    grid.appendChild(card);
                }
            }
        }

        customElements.define("product-gallery", ProductGallery);
    }

    if (!customElements.get("product-card")) {
        class ProductCard extends HTMLElement {
            connectedCallback() {
                const name = this.getAttribute("name");
                const price = this.getAttribute("price");
                const img = this.getAttribute("img");
                const category = this.getAttribute("category");
                this.innerHTML = `
        <div class="product-card">
          <img src="${img}" alt="${name}">
          <div class="info">
            <h5>${name}</h5>
            <p class="price">${price} €</p>
            <p class="category">${category}</p>
            <button class="fav">♡</button>
          </div>
        </div>
      `;
                this.querySelector(".fav").addEventListener("click", (e) => {
                    e.target.classList.toggle("active");
                });
            }
        }

        customElements.define("product-card", ProductCard);
    }
</script>


<style>
    .product-gallery {
        font-family: sans-serif;
        background: #fff;
        border-radius: 10px;
        padding: 1rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .product-gallery header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .category-filter {
        padding: .4rem;
        border-radius: 4px;
        border: 1px solid #ccc;
    }
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 1rem;
    }
    .product-card {
        border: 1px solid #eee;
        border-radius: 8px;
        padding: .5rem;
        text-align: center;
        position: relative;
        transition: transform .2s ease;
        background: #fafafa;
    }
    .product-card:hover {
        transform: scale(1.03);
    }
    .product-card img {
        width: 100%;
        height: auto;
        border-radius: 6px;
    }
    .product-card .info {
        margin-top: .5rem;
    }
    .product-card .price {
        font-weight: bold;
    }
    .product-card .fav {
        border: none;
        background: transparent;
        color: #aaa;
        font-size: 1.4rem;
        cursor: pointer;
        position: absolute;
        top: 8px;
        right: 8px;
        transition: color .2s ease;
    }
    .product-card .fav.active {
        color: red;
    }
    .no-products {
        text-align: center;
        color: #777;
        grid-column: 1 / -1;
    }
</style>

<div class="product-gallery">
    <header>
        <h3><?= $title ?></h3>
        <select class="category-filter">
            <option value="all">Toutes les catégories</option>
            <option value="vetement">Vêtements</option>
            <option value="accessoire">Accessoires</option>
            <option value="chaussure">Chaussures</option>
        </select>
    </header>
    <div class="product-grid"></div>
</div>
