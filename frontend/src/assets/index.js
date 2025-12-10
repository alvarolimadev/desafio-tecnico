export function maskCPF(value) {
    if (!value) return "";
    return value
        .toString()
        .replace(/\D/g, "")
        .replace(/(\d{3})(\d)/, '$1.$2')
        .replace(/(\d{3})(\d)/, '$1.$2')
        .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
}

export function maskCEP(value) {
    if (!value) return "";
    return value
        .replace(/\D/g, '')
        .replace(/(\d{5})(\d)/, "$1-$2")
}