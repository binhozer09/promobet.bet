document.addEventListener('DOMContentLoaded', function() {
    // Toggle Sidebar
    document.getElementById('sidebarCollapse').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
    });

    // Navegação entre páginas
    document.querySelectorAll('[data-page]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const page = this.getAttribute('data-page');
            loadPage(page);

            // Atualiza item ativo no menu
            document.querySelectorAll('.list-unstyled li').forEach(item => {
                item.classList.remove('active');
            });
            this.parentElement.classList.add('active');
        });
    });
});

// Função para carregar páginas via AJAX
function loadPage(page) {
    fetch(`pages/${page}.php`)
        .then(response => response.text())
        .then(html => {
            document.querySelector('.page-content').innerHTML = html;
        })
        .catch(error => {
            console.error('Erro ao carregar a página:', error);
        });
}

// Formatação de números
function formatCurrency(value) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(value);
}

// Copiar link para clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Link copiado com sucesso!');
    }).catch(err => {
        console.error('Erro ao copiar:', err);
    });
}