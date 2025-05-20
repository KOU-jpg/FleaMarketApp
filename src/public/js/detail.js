document.addEventListener('DOMContentLoaded', function () {
    const isLoggedIn = window.LaravelIsLoggedIn || false;
    const loginUrl = window.LaravelLoginUrl || '/login';
    const starIcon = document.querySelector('.icon-star');
    const errorMessage = document.getElementById('favorite-error-message');

    if (starIcon) {
        starIcon.addEventListener('click', function () {
            if (isLoggedIn) {
                errorMessage.style.display = 'none';
                const countElement = this.querySelector('.icon-count');
                const itemId = this.closest('.product-icons').dataset.itemId;
                const isActive = this.classList.contains('active');
                this.classList.toggle('active');
                this.setAttribute('aria-pressed', !isActive);
                let count = parseInt(countElement.textContent);
                countElement.textContent = isActive ? (count - 1) : (count + 1);

                fetch('/favorite/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        item_id: itemId,
                        action: isActive ? 'remove' : 'add'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        this.classList.toggle('active');
                        this.setAttribute('aria-pressed', isActive);
                        countElement.textContent = count;
                        errorMessage.textContent = 'お気に入り処理に失敗しました。';
                        errorMessage.style.display = 'block';
                    } else {
                        countElement.textContent = data.count;
                    }
                })
                .catch(() => {
                    this.classList.toggle('active');
                    this.setAttribute('aria-pressed', isActive);
                    countElement.textContent = count;
                    errorMessage.textContent = '通信エラーが発生しました。';
                    errorMessage.style.display = 'block';
                });
            } else {
                window.location.href = loginUrl;
            }
        });
    }
});