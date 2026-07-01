/* ============================================================
   Categoria — helpers de API e utilidades de UI
   Base: mesma origem do servidor PHP (php -S localhost:4000)
   ============================================================ */

const CategoriaAPI = (() => {
  const BASE = '';

  async function tratarResposta(resp) {
    let corpo = null;
    try {
      corpo = await resp.json();
    } catch (e) {
      corpo = null;
    }

    if (!resp.ok) {
      const msg = (corpo && corpo.message) || `Erro inesperado (HTTP ${resp.status}).`;
      throw new Error(msg);
    }

    return corpo;
  }

  return {
    async listar() {
      const resp = await fetch(`${BASE}/categoria/listar`, {
        headers: { Accept: 'application/json' },
      });
      return tratarResposta(resp);
    },

    async obter(id) {
      const resp = await fetch(`${BASE}/categoria/obter/${id}`, {
        headers: { Accept: 'application/json' },
      });
      return tratarResposta(resp);
    },

    async gravar(nomeCategoria) {
      const resp = await fetch(`${BASE}/categoria/gravar`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nomeCategoria }),
      });
      return tratarResposta(resp);
    },

    async alterar(idCategoria, nomeCategoria) {
      const resp = await fetch(`${BASE}/categoria/alterar`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ idCategoria, nomeCategoria }),
      });
      return tratarResposta(resp);
    },

    async excluir(id) {
      const resp = await fetch(`${BASE}/categoria/excluir/${id}`, {
        method: 'DELETE',
        headers: { Accept: 'application/json' },
      });
      return tratarResposta(resp);
    },
  };
})();

/* ---------- Toasts ---------- */

function mostrarToast(mensagem, tipo = 'success') {
  let stack = document.querySelector('.toast-stack');
  if (!stack) {
    stack = document.createElement('div');
    stack.className = 'toast-stack';
    document.body.appendChild(stack);
  }

  const icones = {
    success:
      '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>',
    error:
      '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/></svg>',
  };

  const toast = document.createElement('div');
  toast.className = `toast toast--${tipo}`;
  toast.innerHTML = `${icones[tipo] || icones.success}<span>${mensagem}</span>`;
  stack.appendChild(toast);

  setTimeout(() => {
    toast.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
    toast.style.opacity = '0';
    toast.style.transform = 'translateY(6px)';
    setTimeout(() => toast.remove(), 250);
  }, 3200);
}

function escaparHtml(texto) {
  const div = document.createElement('div');
  div.textContent = texto ?? '';
  return div.innerHTML;
}
