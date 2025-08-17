// Aplikasi Pencatatan Keuangan - JavaScript
class FinanceApp {
    constructor() {
        this.transactions = [];
        this.categories = [];
        this.currentFilters = {
            jenis: '',
            kategori: '',
            tanggal: '',
            deskripsi: ''
        };
        
        this.init();
    }

    async init() {
        // Set tanggal default ke hari ini
        document.getElementById('tanggal').value = this.formatDate(new Date());
        
        // Load data awal
        await this.loadCategories();
        await this.loadTransactions();
        this.updateSummary();
        
        // Setup event listeners
        this.setupEventListeners();
        
        // Setup filter change handlers
        this.setupFilters();
    }

    setupEventListeners() {
        // Form submission
        document.getElementById('transaction-form').addEventListener('submit', (e) => {
            e.preventDefault();
            this.submitTransaction();
        });

        // Edit form submission
        document.getElementById('edit-form').addEventListener('submit', (e) => {
            e.preventDefault();
            this.updateTransaction();
        });

        // Delete button
        document.getElementById('btn-delete').addEventListener('click', () => {
            this.deleteTransaction();
        });

        // Modal close
        document.querySelector('.close').addEventListener('click', () => {
            this.closeModal();
        });

        // Close modal when clicking outside
        window.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal')) {
                this.closeModal();
            }
        });

        // Jenis transaksi change handler
        document.getElementById('jenis').addEventListener('change', (e) => {
            this.updateCategoryOptions(e.target.value);
        });

        document.getElementById('edit-jenis').addEventListener('change', (e) => {
            this.updateEditCategoryOptions(e.target.value);
        });
    }

    setupFilters() {
        document.getElementById('filter-jenis').addEventListener('change', (e) => {
            this.currentFilters.jenis = e.target.value;
            this.applyFilters();
        });

        document.getElementById('filter-kategori').addEventListener('change', (e) => {
            this.currentFilters.kategori = e.target.value;
            this.applyFilters();
        });

        document.getElementById('filter-tanggal').addEventListener('change', (e) => {
            this.currentFilters.tanggal = e.target.value;
            this.applyFilters();
        });

        document.getElementById('filter-deskripsi').addEventListener('input', (e) => {
            this.currentFilters.deskripsi = e.target.value;
            this.applyFilters();
        });
    }

    async loadCategories() {
        try {
            const response = await fetch('api/categories.php');
            const data = await response.json();
            
            if (data.success) {
                this.categories = data.categories;
                this.populateCategoryOptions();
            } else {
                // Fallback ke kategori hardcoded jika API gagal
                this.categories = [
                    // Kategori Pemasukan
                    { nama: 'Penjualan', jenis: 'masuk' },
                    { nama: 'Modal', jenis: 'masuk' },
                    { nama: 'Investasi', jenis: 'masuk' },
                    { nama: 'Bonus', jenis: 'masuk' },
                    { nama: 'Lainnya', jenis: 'masuk' },
                    
                    // Kategori Pengeluaran
                    { nama: 'Pembelian', jenis: 'keluar' },
                    { nama: 'Operasional', jenis: 'keluar' },
                    { nama: 'Gaji Karyawan', jenis: 'keluar' },
                    { nama: 'Biaya Utilitas', jenis: 'keluar' },
                    { nama: 'Lainnya', jenis: 'keluar' }
                ];
                this.populateCategoryOptions();
            }
        } catch (error) {
            console.error('Error loading categories:', error);
            // Fallback ke kategori hardcoded jika API gagal
            this.categories = [
                // Kategori Pemasukan
                { nama: 'Penjualan', jenis: 'masuk' },
                { nama: 'Modal', jenis: 'masuk' },
                { nama: 'Investasi', jenis: 'masuk' },
                { nama: 'Bonus', jenis: 'masuk' },
                { nama: 'Lainnya', jenis: 'masuk' },
                
                // Kategori Pengeluaran
                { nama: 'Pembelian', jenis: 'keluar' },
                { nama: 'Operasional', jenis: 'keluar' },
                { nama: 'Gaji Karyawan', jenis: 'keluar' },
                { nama: 'Biaya Utilitas', jenis: 'keluar' },
                { nama: 'Lainnya', jenis: 'keluar' }
            ];
            this.populateCategoryOptions();
        }
    }

    async loadTransactions() {
        try {
            const response = await fetch('api/transactions.php');
            const data = await response.json();
            
            if (data.success) {
                this.transactions = data.transactions;
                this.renderTransactions();
            }
        } catch (error) {
            console.error('Error loading transactions:', error);
            this.showNotification('Gagal memuat transaksi', 'error');
        }
    }

    populateCategoryOptions() {
        const kategoriSelect = document.getElementById('kategori');
        const editKategoriSelect = document.getElementById('edit-kategori');
        const filterKategoriSelect = document.getElementById('filter-kategori');
        
        // Clear existing options
        kategoriSelect.innerHTML = '<option value="">Pilih Kategori</option>';
        editKategoriSelect.innerHTML = '';
        filterKategoriSelect.innerHTML = '<option value="">Semua Kategori</option>';
        
        const addedCategories = new Set();
        
        this.categories.forEach(cat => {
            if (!addedCategories.has(cat.nama)) {
                const option = document.createElement('option');
                option.value = cat.nama;
                option.textContent = cat.nama;
                filterKategoriSelect.appendChild(option);
                addedCategories.add(cat.nama);
            }
        });
    }

    updateCategoryOptions(jenis) {
        const kategoriSelect = document.getElementById('kategori');
        kategoriSelect.innerHTML = '<option value="">Pilih Kategori</option>';
        const added = new Set();

        if (jenis) {
            const filteredCategories = this.categories.filter(cat => cat.jenis === jenis);
            filteredCategories.forEach(cat => {
                if (!added.has(cat.nama)) {
                    const option = document.createElement('option');
                    option.value = cat.nama;
                    option.textContent = cat.nama;
                    kategoriSelect.appendChild(option);
                    added.add(cat.nama);
                }
            });
        }
    }

    updateEditCategoryOptions(jenis) {
        const editKategoriSelect = document.getElementById('edit-kategori');
        editKategoriSelect.innerHTML = '';
        const added = new Set();

        if (jenis) {
            const filteredCategories = this.categories.filter(cat => cat.jenis === jenis);
            filteredCategories.forEach(cat => {
                if (!added.has(cat.nama)) {
                    const option = document.createElement('option');
                    option.value = cat.nama;
                    option.textContent = cat.nama;
                    editKategoriSelect.appendChild(option);
                    added.add(cat.nama);
                }
            });
        }
    }

    async submitTransaction() {
        const formData = new FormData(document.getElementById('transaction-form'));
        
        try {
            const response = await fetch('api/transactions.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showNotification('Transaksi berhasil disimpan!', 'success');
                document.getElementById('transaction-form').reset();
                document.getElementById('tanggal').value = this.formatDate(new Date());
                await this.loadTransactions();
                this.updateSummary();
            } else {
                this.showNotification(data.message || 'Gagal menyimpan transaksi', 'error');
            }
        } catch (error) {
            console.error('Error submitting transaction:', error);
            this.showNotification('Gagal menyimpan transaksi', 'error');
        }
    }

    async updateTransaction() {
        const form = document.getElementById('edit-form');
        const id = form.querySelector('#edit-id').value;
        const tanggal = form.querySelector('#edit-tanggal').value;
        const jenis = form.querySelector('#edit-jenis').value;
        const kategori = form.querySelector('#edit-kategori').value;
        const jumlah = form.querySelector('#edit-jumlah').value;
        const deskripsi = form.querySelector('#edit-deskripsi').value;

        // Create a URLSearchParams object
        const body = new URLSearchParams();
        body.append('id', id);
        body.append('tanggal', tanggal);
        body.append('jenis', jenis);
        body.append('kategori', kategori);
        body.append('jumlah', jumlah);
        body.append('deskripsi', deskripsi);
        
        try {
            const response = await fetch('api/transactions.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: body
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showNotification('Transaksi berhasil diupdate!', 'success');
                this.closeModal();
                await this.loadTransactions();
                this.updateSummary();
            } else {
                this.showNotification(data.message || 'Gagal mengupdate transaksi', 'error');
            }
        } catch (error) {
            console.error('Error updating transaction:', error);
            this.showNotification('Gagal mengupdate transaksi', 'error');
        }
    }

    async deleteTransaction() {
        const id = document.getElementById('edit-id').value;
        
        if (!confirm('Apakah Anda yakin ingin menghapus transaksi ini?')) {
            return;
        }
        
        try {
            const response = await fetch(`api/transactions.php?id=${id}`, {
                method: 'DELETE'
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showNotification('Transaksi berhasil dihapus!', 'success');
                this.closeModal();
                await this.loadTransactions();
                this.updateSummary();
            } else {
                this.showNotification(data.message || 'Gagal menghapus transaksi', 'error');
            }
        } catch (error) {
            console.error('Error deleting transaction:', error);
            this.showNotification('Gagal menghapus transaksi', 'error');
        }
    }

    renderTransactions() {
        const container = document.getElementById('transactions-list');
        
        if (this.transactions.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-receipt"></i>
                    <h3>Belum ada transaksi</h3>
                    <p>Mulai dengan menambahkan transaksi pertama Anda</p>
                </div>
            `;
            return;
        }
        
        const filteredTransactions = this.getFilteredTransactions();
        
        container.innerHTML = filteredTransactions.map(transaction => `
            <div class="transaction-item ${transaction.jenis}">
                <div class="transaction-header">
                    <span class="transaction-type ${transaction.jenis}">
                        ${transaction.jenis === 'masuk' ? 'Pemasukan' : 'Pengeluaran'}
                    </span>
                    <span class="transaction-amount">
                        Rp ${this.formatNumber(transaction.jumlah)}
                    </span>
                </div>
                <div class="transaction-details">
                    <div class="transaction-category">
                        <i class="fas fa-tag"></i> ${transaction.kategori}
                    </div>
                    ${transaction.deskripsi ? `<div class="transaction-description">${transaction.deskripsi}</div>` : ''}
                    <div class="transaction-date">
                        <i class="fas fa-calendar"></i> ${this.formatDate(transaction.tanggal)}
                    </div>
                </div>
                <div class="transaction-actions">
                    <button class="btn-edit" onclick="app.editTransaction(${transaction.id})">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                </div>
            </div>
        `).join('');
    }

    getFilteredTransactions() {
        let filtered = [...this.transactions];
        
        if (this.currentFilters.jenis) {
            filtered = filtered.filter(t => t.jenis === this.currentFilters.jenis);
        }
        
        if (this.currentFilters.kategori) {
            filtered = filtered.filter(t => t.kategori === this.currentFilters.kategori);
        }
        
        if (this.currentFilters.tanggal) {
            filtered = filtered.filter(t => t.tanggal === this.currentFilters.tanggal);
        }

        if (this.currentFilters.deskripsi) {
            const searchTerm = this.currentFilters.deskripsi.toLowerCase();
            filtered = filtered.filter(t => 
                t.deskripsi && t.deskripsi.toLowerCase().includes(searchTerm)
            );
        }
        
        return filtered.sort((a, b) => new Date(b.tanggal) - new Date(a.tanggal));
    }

    applyFilters() {
        this.renderTransactions();
    }

    async editTransaction(id) {
        const transaction = this.transactions.find(t => t.id == id);
        
        if (!transaction) {
            this.showNotification('Transaksi tidak ditemukan', 'error');
            return;
        }
        
        // Populate edit form
        document.getElementById('edit-id').value = transaction.id;
        document.getElementById('edit-tanggal').value = transaction.tanggal;
        document.getElementById('edit-jenis').value = transaction.jenis;
        document.getElementById('edit-jumlah').value = transaction.jumlah;
        document.getElementById('edit-deskripsi').value = transaction.deskripsi || '';
        
        // Update category options based on jenis
        this.updateEditCategoryOptions(transaction.jenis);
        
        // Add a small delay to ensure options are rendered before setting value
        setTimeout(() => {
            document.getElementById('edit-kategori').value = transaction.kategori;
        }, 50); // 50ms delay
        
        // Show modal
        document.getElementById('edit-modal').style.display = 'block';
    }

    closeModal() {
        document.getElementById('edit-modal').style.display = 'none';
    }

    updateSummary() {
        const totalIncome = this.transactions
            .filter(t => t.jenis === 'masuk')
            .reduce((sum, t) => sum + parseFloat(t.jumlah), 0);
            
        const totalExpense = this.transactions
            .filter(t => t.jenis === 'keluar')
            .reduce((sum, t) => sum + parseFloat(t.jumlah), 0);
            
        const balance = totalIncome - totalExpense;
        
        document.getElementById('total-income').textContent = `Rp ${this.formatNumber(totalIncome)}`;
        document.getElementById('total-expense').textContent = `Rp ${this.formatNumber(totalExpense)}`;
        document.getElementById('total-balance').textContent = `Rp ${this.formatNumber(balance)}`;
        
        // Update balance color
        const balanceElement = document.getElementById('total-balance');
        if (balance >= 0) {
            balanceElement.style.color = '#4CAF50';
        } else {
            balanceElement.style.color = '#f44336';
        }
    }

    formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }

    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toISOString().split('T')[0];
    }

    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                <span>${message}</span>
            </div>
        `;
        
        // Add styles
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#4CAF50' : type === 'error' ? '#f44336' : '#2196F3'};
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            max-width: 300px;
        `;
        
        // Add to page
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
}

// Initialize app when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.app = new FinanceApp();
});
