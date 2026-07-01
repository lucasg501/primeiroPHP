const ProdutoApi = {
    listar(){
        return httpClient.get('/produto/listar')
    },
    obter(id){
        return httpClient.get(`/produto/obter/${id}`)
    },
    gravar(nomeProduto, valorProduto, idCategoria){
        return httpClient.post('/produto/gravar', {
            nomeProduto,
            valorProduto,
            idCategoria
        });
    },
    alterar(idProduto, nomeProduto, valorProduto, idCategoria){
        return httpClient.put(`/produto/alterar/`, {
            idProduto,
            nomeProduto,
            valorProduto,
            idCategoria
        });
    },
    excluir(idProduto){
        return httpClient.delete(`/produto/excluir/${idProduto}`);
    }
}