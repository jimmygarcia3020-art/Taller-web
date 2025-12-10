document.addEventListener("DOMContentLoaded", () => {
  const toggleBtn = document.getElementById("toggle-btn");
  const sidebar = document.getElementById("sidebar");

  if (toggleBtn && sidebar) {
    toggleBtn.addEventListener("click", () => {
      sidebar.classList.toggle("collapsed");
    });
  }

  document.querySelectorAll(".dropdown-toggle").forEach(toggle => {
    toggle.addEventListener("click", e => {
      e.preventDefault();
      toggle.parentElement.classList.toggle("open");
    });
  });

  const btnAbrir = document.getElementById("btnSeleccionarCliente");
  const modal = document.getElementById("modalClientes");
  const btnCerrar = document.getElementById("cerrarModalClientes");
  const lista = document.getElementById("listaClientes");
  const buscador = document.getElementById("buscadorClientes");
  const nombreHeader = document.getElementById("contador-nombre");
  const bienvenida = document.getElementById("bienvenida-usuario");

  if (btnAbrir && modal) {
    btnAbrir.addEventListener("click", (e) => {
      e.preventDefault();
      modal.classList.add("active");
      modal.setAttribute("aria-hidden", "false");
      document.body.style.overflow = "hidden";
      if (buscador) {
        setTimeout(() => buscador.focus(), 60);
      }
    });
  }

  if (btnCerrar && modal) {
    btnCerrar.addEventListener("click", () => {
      modal.classList.remove("active");
      modal.setAttribute("aria-hidden", "true");
      document.body.style.overflow = "";
    });
  }

  if (modal) {
    modal.addEventListener("click", (e) => {
      if (e.target === modal) {
        modal.classList.remove("active");
        modal.setAttribute("aria-hidden", "true");
        document.body.style.overflow = "";
      }
    });
  }

  if (lista) {
    lista.addEventListener("click", (e) => {
      const item = e.target.closest(".cliente-item");
      if (!item) return;
      const nombre = item.dataset.nombre || item.textContent.trim();
      const id = item.dataset.id || "";
      if (nombreHeader) nombreHeader.innerText = nombre;
      if (bienvenida) bienvenida.innerText = `Cliente: ${nombre} ${id ? '('+id+')' : ''}`;
      modal && modal.classList.remove("active");
      document.body.style.overflow = "";
    });
  }

  if (buscador) {
    buscador.addEventListener("input", () => {
      const q = buscador.value.trim().toLowerCase();
      document.querySelectorAll("#listaClientes .cliente-item").forEach(ci => {
        const nombre = (ci.dataset.nombre || ci.textContent).toLowerCase();
        ci.style.display = nombre.includes(q) ? "block" : "none";
      });
      document.querySelectorAll("#listaClientes .grupo-clientes").forEach(gr => {
        const anyVisible = Array.from(gr.querySelectorAll(".cliente-item")).some(x => x.style.display !== "none");
        gr.style.display = anyVisible ? "block" : "none";
      });
    });
  }

});
