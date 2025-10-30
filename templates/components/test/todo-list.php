<?php
$title = htmlspecialchars($props['title'] ?? 'Ma To-Do List');
?>
<script defer>
    if (!customElements.get("todo-list")) {
        class TodoList extends HTMLElement {
            connectedCallback() {
                this.listEl = this.querySelector(".todo-items");
                this.inputEl = this.querySelector(".todo-input");
                this.formEl = this.querySelector(".todo-form");

                this.loadItems();
                this.formEl.addEventListener("submit", e => {
                    e.preventDefault();
                    const text = this.inputEl.value.trim();
                    if (!text) return;
                    this.addItem(text);
                    this.inputEl.value = "";
                });

                this.listEl.addEventListener("click", e => {
                    if (e.target.classList.contains("remove")) {
                        e.target.closest("li").remove();
                        this.saveItems();
                    }
                    if (e.target.classList.contains("toggle")) {
                        e.target.closest("li").classList.toggle("done");
                        this.saveItems();
                    }
                });
            }

            loadItems() {
                const key = this.getStorageKey();
                const items = JSON.parse(localStorage.getItem(key) || "[]");
                this.listEl.innerHTML = "";
                for (const item of items) {
                    this.addItem(item.text, item.done);
                }
            }

            saveItems() {
                const key = this.getStorageKey();
                const items = Array.from(this.listEl.querySelectorAll("li")).map(li => ({
                    text: li.querySelector(".text").textContent,
                    done: li.classList.contains("done")
                }));
                localStorage.setItem(key, JSON.stringify(items));
            }

            addItem(text, done = false) {
                const li = document.createElement("li");
                li.innerHTML = `
        <span class="text">${text}</span>
        <div class="actions">
          <button type="button" class="btn btn-sm btn-success toggle">${done ? "↺" : "✓"}</button>
          <button type="button" class="btn btn-sm btn-danger remove">×</button>
        </div>
      `;
                if (done) li.classList.add("done");
                this.listEl.appendChild(li);
                this.saveItems();
            }

            getStorageKey() {
                // Permet plusieurs listes distinctes selon l'attribut "title"
                return "todo-list:" + (this.getAttribute("title") || "default");
            }
        }

        customElements.define("todo-list", TodoList);
    }
</script>

<style>
    .todo {
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 1rem;
        max-width: 300px;
        font-family: sans-serif;
        background: #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .todo h4 {
        margin-top: 0;
        margin-bottom: .75rem;
    }
    .todo-form {
        display: flex;
        gap: .5rem;
        margin-bottom: .75rem;
    }
    .todo-input {
        flex: 1;
        padding: .4rem;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    .todo-items {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .todo-items li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: .25rem 0;
        border-bottom: 1px solid #eee;
    }
    .todo-items li.done .text {
        text-decoration: line-through;
        color: #777;
    }
    .todo-items .actions {
        display: flex;
        gap: .25rem;
    }
    .todo-items button {
        border: none;
        cursor: pointer;
        font-size: .9rem;
        line-height: 1;
    }
    .btn {
        padding: .2rem .4rem;
        border-radius: 4px;
    }
    .btn-success { background: #28a745; color: #fff; }
    .btn-danger { background: #dc3545; color: #fff; }
</style>

<div class="todo">
    <h4><?= $title ?></h4>
    <form class="todo-form">
        <input type="text" class="todo-input" placeholder="Nouvelle tâche..." />
        <button type="submit" class="btn btn-primary">+</button>
    </form>
    <ul class="todo-items"></ul>
</div>
