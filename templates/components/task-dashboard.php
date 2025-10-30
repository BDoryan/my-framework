<?php
$title = htmlspecialchars($props['title'] ?? 'Tableau de tâches');
?>
<script defer>
    if (!customElements.get("task-dashboard")) {
        class TaskDashboard extends HTMLElement {
            connectedCallback() {
                this.data = this.loadData();
                this.render();
                this.setupEvents();
            }

            loadData() {
                const saved = localStorage.getItem("task-dashboard:data");
                if (saved) return JSON.parse(saved);
                return {
                    todo: ["Faire le café", "Coder le framework"],
                    doing: ["Écrire la doc"],
                    done: ["Prendre une pause"]
                };
            }

            saveData() {
                localStorage.setItem("task-dashboard:data", JSON.stringify(this.data));
            }

            setupEvents() {
                this.querySelector(".add-task").addEventListener("click", () => {
                    const input = this.querySelector(".new-task");
                    const val = input.value.trim();
                    if (!val) return;
                    this.data.todo.push(val);
                    input.value = "";
                    this.saveData();
                    this.render();
                });

                this.querySelectorAll(".tasks").forEach(col => {
                    col.addEventListener("dragstart", e => {
                        if (e.target.tagName !== "TASK-CARD") return;
                        e.dataTransfer.setData("text/plain", e.target.textContent);
                        e.dataTransfer.effectAllowed = "move";
                        e.target.classList.add("dragging");
                    });

                    col.addEventListener("dragend", e => {
                        if (e.target.tagName === "TASK-CARD")
                            e.target.classList.remove("dragging");
                    });

                    col.addEventListener("dragover", e => e.preventDefault());

                    col.addEventListener("drop", e => {
                        e.preventDefault();
                        const text = e.dataTransfer.getData("text/plain");
                        const fromCol = Object.keys(this.data).find(k =>
                            this.data[k].includes(text)
                        );
                        const toCol = col.dataset.status;
                        if (!fromCol || !toCol || fromCol === toCol) return;
                        this.data[fromCol] = this.data[fromCol].filter(t => t !== text);
                        this.data[toCol].push(text);
                        this.saveData();
                        this.render();
                    });
                });
            }

            render() {
                const columns = [
                    { key: "todo", title: "À faire" },
                    { key: "doing", title: "En cours" },
                    { key: "done", title: "Terminé" }
                ];

                this.innerHTML = `
      <div class="dashboard">
        <header>
          <h3>${this.getAttribute("title") || "Tableau de tâches"}</h3>
          <div class="add">
            <input class="new-task" placeholder="Nouvelle tâche..." />
            <button class="add-task">Ajouter</button>
          </div>
        </header>
        <div class="columns">
          ${columns
                    .map(
                        col => `
            <div class="column" data-status="${col.key}">
              <h4>${col.title}</h4>
              <div class="tasks" data-status="${col.key}">
                ${this.data[col.key]
                            .map(task => `<task-card draggable="true" text="${task}"></task-card>`)
                            .join("")}
              </div>
            </div>`
                    )
                    .join("")}
        </div>
      </div>
    `;

                // Monte les sous-composants
                this.querySelectorAll("task-card").forEach(card => {
                    new TaskCard(card);
                });

                // Rebind les events après re-render
                this.setupEvents();
            }
        }

        class TaskCard {
            constructor(el) {
                this.el = el;
                this.text = el.getAttribute("text");
                this.render();
            }

            render() {
                this.el.innerHTML = `
      <div class="task">
        <span>${this.text}</span>
        <button class="remove">×</button>
      </div>
    `;
                this.el.querySelector(".remove").addEventListener("click", () => {
                    const dash = this.el.closest("task-dashboard");
                    const col = this.el.closest(".tasks").dataset.status;
                    dash.data[col] = dash.data[col].filter(t => t !== this.text);
                    dash.saveData();
                    dash.render();
                });
            }
        }

        customElements.define("task-dashboard", TaskDashboard);
    }
</script>

<style>
    .dashboard {
        font-family: sans-serif;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        padding: 1rem;
        max-width: 900px;
        margin: 1rem auto;
        display: flex;
        flex-direction: column;
    }
    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .add {
        display: flex;
        gap: .5rem;
    }
    .new-task {
        padding: .4rem;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    .add-task {
        background: #007bff;
        color: #fff;
        border: none;
        padding: .4rem .8rem;
        border-radius: 4px;
        cursor: pointer;
    }
    .add-task:hover { background: #0056b3; }
    .columns {
        display: flex;
        gap: 1rem;
        justify-content: space-between;
    }
    .column {
        flex: 1;
        background: #f8f9fa;
        border-radius: 6px;
        padding: .5rem;
        display: flex;
        flex-direction: column;
        min-height: 200px;
    }
    .column h4 {
        text-align: center;
        margin: .25rem 0 .5rem 0;
    }
    .tasks {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: .5rem;
    }
    task-card {
        display: block;
    }
    .task {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: .5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: grab;
        transition: box-shadow .2s;
    }
    .task:hover {
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .task .remove {
        background: transparent;
        border: none;
        color: #dc3545;
        font-size: 1.2rem;
        cursor: pointer;
    }
    .dragging {
        opacity: 0.5;
    }
</style>

<div class="dashboard">
    <header>
        <h3><?= $title ?></h3>
        <div class="add">
            <input class="new-task" placeholder="Nouvelle tâche..." />
            <button class="add-task">Ajouter</button>
        </div>
    </header>
    <div class="columns">
        <div class="column" data-status="todo">
            <h4>À faire</h4>
            <div class="tasks" data-status="todo"></div>
        </div>
        <div class="column" data-status="doing">
            <h4>En cours</h4>
            <div class="tasks" data-status="doing"></div>
        </div>
        <div class="column" data-status="done">
            <h4>Terminé</h4>
            <div class="tasks" data-status="done"></div>
        </div>
    </div>
</div>
