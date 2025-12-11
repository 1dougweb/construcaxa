// Formatação de CPF
function formatCPF(value) {
    value = value.replace(/\D/g, '');
    if (value.length <= 11) {
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    }
    return value;
}

// Formatação de CNPJ
function formatCNPJ(value) {
    value = value.replace(/\D/g, '');
    if (value.length <= 14) {
        value = value.replace(/(\d{2})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1/$2');
        value = value.replace(/(\d{4})(\d{1,2})$/, '$1-$2');
    }
    return value;
}

// Formatação de CEP
function formatCEP(value) {
    value = value.replace(/\D/g, '');
    if (value.length <= 8) {
        value = value.replace(/(\d{5})(\d)/, '$1-$2');
    }
    return value;
}

// Formatação de Telefone
function formatPhone(value) {
    value = value.replace(/\D/g, '');
    if (value.length <= 11) {
        if (value.length <= 10) {
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{4})(\d)/, '$1-$2');
        } else {
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
        }
    }
    return value;
}

// Toggle entre Pessoa Física e Jurídica
window.toggleClientType = function() {
    const isIndividual = document.getElementById('type_individual').checked;
    const cpfField = document.getElementById('cpf_field');
    const cnpjField = document.getElementById('cnpj_field');
    const tradingNameField = document.getElementById('trading_name_field');
    const nameLabel = document.getElementById('name_label');
    
    if (isIndividual) {
        cpfField.classList.remove('hidden');
        cnpjField.classList.add('hidden');
        tradingNameField.classList.add('hidden');
        nameLabel.textContent = 'Nome Completo *';
        document.getElementById('cpf').required = true;
        document.getElementById('cnpj').required = false;
        document.getElementById('cpf').value = '';
        document.getElementById('cnpj').value = '';
    } else {
        cpfField.classList.add('hidden');
        cnpjField.classList.remove('hidden');
        tradingNameField.classList.remove('hidden');
        nameLabel.textContent = 'Razão Social *';
        document.getElementById('cpf').required = false;
        document.getElementById('cnpj').required = true;
        document.getElementById('cpf').value = '';
        document.getElementById('cnpj').value = '';
    }
};

// Buscar CNPJ via API
window.searchCNPJ = async function() {
    const cnpjInput = document.getElementById('cnpj');
    const cnpj = cnpjInput.value.replace(/\D/g, '');
    const loadingDiv = document.getElementById('cnpj_loading');
    const searchBtn = document.getElementById('search_cnpj_btn');
    
    if (cnpj.length !== 14) {
        alert('CNPJ deve ter 14 dígitos');
        return;
    }
    
    loadingDiv.classList.remove('hidden');
    searchBtn.disabled = true;
    searchBtn.innerHTML = '<i class="bi bi-hourglass-split mr-2"></i>Buscando...';
    
    try {
        const response = await fetch(`/api/clients/fetch-cnpj?cnpj=${cnpj}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Preencher campos
            document.getElementById('name').value = data.data.name || '';
            document.getElementById('trading_name').value = data.data.trading_name || '';
            document.getElementById('address').value = data.data.address || '';
            document.getElementById('neighborhood').value = data.data.neighborhood || '';
            document.getElementById('city').value = data.data.city || '';
            document.getElementById('state').value = data.data.state || '';
            document.getElementById('zip_code').value = formatCEP(data.data.zip_code || '');
            document.getElementById('phone').value = formatPhone(data.data.phone || '');
            document.getElementById('email').value = data.data.email || '';
        } else {
            alert('Erro ao buscar CNPJ: ' + data.message);
        }
    } catch (error) {
        alert('Erro ao buscar CNPJ: ' + error.message);
    } finally {
        loadingDiv.classList.add('hidden');
        searchBtn.disabled = false;
        searchBtn.innerHTML = '<i class="bi bi-search mr-2"></i>Buscar';
    }
};

// Inicialização quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    // Formatação de CPF
    const cpfInput = document.getElementById('cpf');
    if (cpfInput) {
        cpfInput.addEventListener('input', function(e) {
            e.target.value = formatCPF(e.target.value);
        });
    }
    
    // Formatação de CNPJ
    const cnpjInput = document.getElementById('cnpj');
    if (cnpjInput) {
        cnpjInput.addEventListener('input', function(e) {
            e.target.value = formatCNPJ(e.target.value);
        });
    }
    
    // Formatação de CEP
    const zipCodeInput = document.getElementById('zip_code');
    if (zipCodeInput) {
        zipCodeInput.addEventListener('input', function(e) {
            e.target.value = formatCEP(e.target.value);
        });
    }
    
    // Formatação de Telefone
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            e.target.value = formatPhone(e.target.value);
        });
    }
    
    // Buscar CNPJ ao pressionar Enter
    if (cnpjInput) {
        cnpjInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchCNPJ();
            }
        });
    }
});