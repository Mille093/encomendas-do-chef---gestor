// main.js - Scripts do sistema de gestão
console.log('Gestor JS carregado');

// Função para confirmar exclusões
function confirmDelete(message = 'Tem certeza que deseja excluir este item?') {
    return confirm(message);
}

// Função para formatar valores monetários nos inputs
function formatMoney(input) {
    let value = input.value.replace(/\D/g, '');
    value = (value / 100).toFixed(2);
    value = value.replace('.', ',');
    input.value = value;
}

// Auto-aplicar formatação nos campos de preço
document.addEventListener('DOMContentLoaded', function() {
    // Campos de preço
    const priceInputs = document.querySelectorAll('input[name*="preco"], input[name*="valor"]');
    priceInputs.forEach(input => {
        input.addEventListener('blur', () => formatMoney(input));
        input.addEventListener('input', function() {
            this.value = this.value.replace(/[^\d,]/g, '');
        });
    });

    // Confirmação de exclusão nos links de delete
    const deleteLinks = document.querySelectorAll('a[href*="/delete/"]');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirmDelete()) {
                e.preventDefault();
            }
        });
    });

    // Preview de imagem em uploads
    const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let preview = document.querySelector('.image-preview');
                    if (!preview) {
                        preview = document.createElement('div');
                        preview.className = 'image-preview';
                        input.parentNode.appendChild(preview);
                    }
                    preview.innerHTML = `<img src="${e.target.result}" style="max-width: 200px; max-height: 200px; border-radius: 8px; margin-top: 10px;">`;
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // Auto-dismiss alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000); // Remove após 5 segundos
    });
});

// Função para atualizar contadores do dashboard via AJAX
function updateDashboardCounters() {
    fetch('/api/pedidos-count')
        .then(response => response.json())
        .then(data => {
            if (data.ok) {
                // Atualizar contadores específicos
                const totalPedidos = document.querySelector('[data-counter="total_pedidos"]');
                const pendentes = document.querySelector('[data-counter="pendentes"]'); 
                const totalVendas = document.querySelector('[data-counter="total_vendas"]');
                
                if (totalPedidos) totalPedidos.textContent = data.total_pedidos;
                if (pendentes) pendentes.textContent = data.pendentes;
                if (totalVendas) totalVendas.textContent = 'R$ ' + data.total_vendas.toLocaleString('pt-BR', {minimumFractionDigits: 2});
            }
        })
        .catch(error => {
            console.error('Erro ao atualizar contadores:', error);
        });
}

// Atualizar contadores a cada 30 segundos se estiver no dashboard
if (window.location.pathname === '/' || window.location.pathname === '/dashboard') {
    setInterval(updateDashboardCounters, 30000);
}