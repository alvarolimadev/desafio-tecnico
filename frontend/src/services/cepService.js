export async function buscarCEP(cep) {
  cep = cep.replace(/\D/g, '');

  if (cep.length !== 8) return null;

  try {
    const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
    const data = await response.json();

    if (data.erro) return null;

    return {
      street: data.logradouro || '',
      neighborhood: data.bairro || '',
      city: data.localidade || '',
      state: data.uf || '',
      zip: data.cep || '',
      country: 'Brasil'
    };
  } catch (e) {
    console.error("Erro ao buscar CEP:", e);
    return null;
  }
}
