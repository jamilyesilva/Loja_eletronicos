(function(){
  function autoHideAlerts(){
    const alerts = document.querySelectorAll('.container .alert, .page-auth .alert, fieldset .alert');
    alerts.forEach(alert => {
      setTimeout(() => {
        alert.style.transition = 'opacity 0.5s ease';
        alert.style.opacity = '0';
        setTimeout(() => { if (alert.parentNode) alert.parentNode.removeChild(alert); }, 600);
      }, 2000);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', autoHideAlerts);
  } else {
    autoHideAlerts();
  }
})();

(function(){
  function initImagePreview(){
    const input = document.getElementById('image-input');
    const preview = document.getElementById('img-preview');
    const label = document.getElementById('image-label');
    if (!input || !preview || !label) return;

    if (preview.src) {
      preview.style.display = 'block';
      label.style.display = 'none';
    }

    input.addEventListener('change', function(){
      const f = this.files && this.files[0];
      if (!f) return;
      const url = URL.createObjectURL(f);
      preview.src = url;
      preview.style.display = 'block';
      label.style.display = 'none';
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initImagePreview);
  } else {
    initImagePreview();
  }
})();
