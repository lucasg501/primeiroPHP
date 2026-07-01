const httpClient = (() => {

    async function tratarResposta(resp) {

        let corpo = null;

        try {
            corpo = await resp.json();
        } catch (e) {
            corpo = null;
        }

        if (!resp.ok) {

            throw new Error(
                corpo?.message ??
                corpo?.erro ??
                `Erro HTTP ${resp.status}`
            );

        }

        return corpo;
    }

    async function request(endpoint, options = {}) {

        const resposta = await fetch(endpoint, {
            headers: {
                Accept: "application/json",
                ...(options.headers || {})
            },
            ...options
        });

        return tratarResposta(resposta);
    }

    return {

        get(endpoint) {

            return request(endpoint);

        },

        post(endpoint, body, headers = {}) {

            return request(endpoint, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    ...headers
                },
                body: JSON.stringify(body)
            });

        },

        put(endpoint, body, headers = {}) {

            return request(endpoint, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    ...headers
                },
                body: JSON.stringify(body)
            });

        },

        delete(endpoint) {

            return request(endpoint, {
                method: "DELETE"
            });

        },

        upload(endpoint, formData) {

            return request(endpoint, {
                method: "POST",
                body: formData
            });

        }

    };

})();