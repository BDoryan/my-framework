<?php
    $name = htmlspecialchars($props['name'] ?? 'Inconnu');
    $age = htmlspecialchars($props['age'] ?? '?');
?>
<script defer>
    class UserCard extends HTMLElement {
        connectedCallback() {
            const button = this.querySelector(".increment");
            const ageEl = this.querySelector(".age");

            if (button && ageEl) {
                button.addEventListener("click", () => {
                    let age = parseInt(ageEl.textContent, 10);
                    ageEl.textContent = ++age;
                });
            }
        }
    }

    customElements.define("user-card", UserCard);
</script>
<style>
    .user-card {
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 1rem;
        font-family: sans-serif;
        width: 200px;
    }

    .user-card h3 {
        margin: 0 0 .5rem 0;
    }

    .user-card button {
        margin-top: .5rem;
        cursor: pointer;
    }
</style>
<div class="card" style="width: 18rem;">
    <div class="card-body">
        <h5 class="card-title"><?= $name ?></h5>
        <p class="card-text">Âge : <span class="age"><?= $age ?></span> ans</p>
        <button class="btn btn-primary increment">+1 an</button>
    </div>
</div>