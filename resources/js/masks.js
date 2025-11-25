// Funções de máscara para formulários tradicionais (não Livewire)

function initMasks() {
    // Verificar se IMask está disponível (pode ser carregado via CDN)
    const useIMask = typeof IMask !== 'undefined';
    
    if (useIMask) {
        applyIMasks();
    } else {
        // Se IMask não estiver disponível, usar máscaras puras
        applyPureMasks();
    }
}

// Aguardar DOM e IMask estarem prontos
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        // Aguardar um pouco para garantir que IMask (CDN) esteja carregado
        setTimeout(initMasks, 100);
    });
} else {
    // DOM já está pronto
    setTimeout(initMasks, 100);
}

function applyIMasks() {
    // CPF Mask
    document.querySelectorAll('.mask-cpf').forEach(element => {
        if (element.maskInstance) {
            element.maskInstance.destroy();
        }
        element.maskInstance = IMask(element, {
            mask: '000.000.000-00'
        });
    });

    // RG Mask (formato varia, mas geralmente XX.XXX.XXX-X)
    document.querySelectorAll('.mask-rg').forEach(element => {
        if (element.maskInstance) {
            element.maskInstance.destroy();
        }
        element.maskInstance = IMask(element, {
            mask: [
                { mask: '00.000.000-0' },
                { mask: '00.000.000-00' },
                { mask: '000000000-0' },
                { mask: '00000000-0' }
            ],
            dispatch: function (appended, dynamicMasked) {
                const number = (dynamicMasked.value + appended).replace(/\D/g, '');
                // Detectar formato baseado no tamanho
                if (number.length <= 8) {
                    return dynamicMasked.compiledMasks.find(m => m.mask === '00000000-0') || this.compiledMasks[0];
                } else if (number.length === 9) {
                    return dynamicMasked.compiledMasks.find(m => m.mask === '00.000.000-0') || this.compiledMasks[0];
                } else {
                    return dynamicMasked.compiledMasks.find(m => m.mask === '00.000.000-00') || this.compiledMasks[0];
                }
            }
        });
    });

    // CNPJ Mask
    document.querySelectorAll('.mask-cnpj').forEach(element => {
        if (element.maskInstance) {
            element.maskInstance.destroy();
        }
        element.maskInstance = IMask(element, {
            mask: '00.000.000/0000-00'
        });
    });

    // Phone Mask (Telefone fixo)
    document.querySelectorAll('.mask-phone').forEach(element => {
        if (element.maskInstance) {
            element.maskInstance.destroy();
        }
        element.maskInstance = IMask(element, {
            mask: [
                { mask: '(00) 0000-0000' },
                { mask: '(00) 00000-0000' }
            ],
            dispatch: function (appended, dynamicMasked) {
                const number = (dynamicMasked.value + appended).replace(/\D/g, '');
                return dynamicMasked.compiledMasks.find(function (m) {
                    const re = m.mask === '(00) 0000-0000' ? /^\d{10}$/ : /^\d{11}$/;
                    return number.match(re);
                }) || this.compiledMasks[0];
            }
        });
    });

    // Cellphone Mask (Celular)
    document.querySelectorAll('.mask-cellphone').forEach(element => {
        if (element.maskInstance) {
            element.maskInstance.destroy();
        }
        element.maskInstance = IMask(element, {
            mask: '(00) 00000-0000'
        });
    });
}

function applyPureMasks() {
    // CPF Mask
    document.querySelectorAll('.mask-cpf').forEach(element => {
        element.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            }
            e.target.value = value;
        });
    });

    // RG Mask (formato varia, mas geralmente XX.XXX.XXX-X)
    document.querySelectorAll('.mask-rg').forEach(element => {
        element.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 8) {
                // Formato simples: 00000000-0
                value = value.replace(/(\d{8})(\d)/, '$1-$2');
            } else if (value.length <= 9) {
                // Formato padrão: 00.000.000-0
                value = value.replace(/(\d{2})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1})$/, '$1-$2');
            } else {
                // Formato com 2 dígitos finais: 00.000.000-00
                value = value.replace(/(\d{2})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{2})$/, '$1-$2');
            }
            e.target.value = value;
        });
    });

    // CNPJ Mask
    document.querySelectorAll('.mask-cnpj').forEach(element => {
        element.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 14) {
                value = value.replace(/^(\d{2})(\d)/, '$1.$2');
                value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
                value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
                value = value.replace(/(\d{4})(\d)/, '$1-$2');
            }
            e.target.value = value;
        });
    });

    // Phone Mask (Telefone fixo)
    document.querySelectorAll('.mask-phone').forEach(element => {
        element.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 10) {
                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{4})(\d)/, '$1-$2');
            } else if (value.length <= 11) {
                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
            }
            e.target.value = value;
        });
    });

    // Cellphone Mask (Celular)
    document.querySelectorAll('.mask-cellphone').forEach(element => {
        element.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
            }
            e.target.value = value;
        });
    });
}
