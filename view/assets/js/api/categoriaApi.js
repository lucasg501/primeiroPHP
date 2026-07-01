const CategoriaAPI = {

    listar() {
        return httpClient.get("/categoria/listar");
    },

    obter(id) {
        return httpClient.get(`/categoria/obter/${id}`);
    },

    gravar(nomeCategoria) {
        return httpClient.post("/categoria/gravar", {
            nomeCategoria
        });
    },

    alterar(idCategoria, nomeCategoria) {
        return httpClient.put("/categoria/alterar", {
            idCategoria,
            nomeCategoria
        });
    },

    excluir(id) {
        return httpClient.delete(`/categoria/excluir/${id}`);
    }

};