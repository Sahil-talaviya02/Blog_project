<div id="toast-container" class="position-fixed" style="top: 20px; right: 20px; z-index: 9999;"></div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('showToast', (event) => {
            const data = Array.isArray(event) ? event[0] : event;
            const type = data.type || 'info';
            const message = data.message || '';
            
            const bgClass = {
                'success': 'bg-success',
                'error': 'bg-danger',
                'warning': 'bg-warning',
                'info': 'bg-info'
            }[type] || 'bg-info';

            const toastId = 'toast-' + Date.now();
            const toastHtml = `
                <div id="${toastId}" class="toast ${bgClass} text-white border-0 shadow-lg mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000">
                    <div class="d-flex">
                        <div class="toast-body">
                            ${message}
                        </div>
                        <button type="button" class="ml-auto mb-1 close text-white mr-2" data-dismiss="toast" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            `;

            document.getElementById('toast-container').insertAdjacentHTML('beforeend', toastHtml);
            
            const toastElement = $(`#${toastId}`);
            toastElement.toast('show');
            
            toastElement.on('hidden.bs.toast', function () {
                $(this).remove();
            });
        });
    });
</script>
