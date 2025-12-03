@if(session('success'))
<div class="alert alert-success success-alert-animated" role="alert" style="position: relative; overflow: hidden;">
    <div class="d-flex align-items-center">
        <div class="success-icon-container me-3">
            <svg class="success-checkmark" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle class="checkmark-circle" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                <path class="checkmark-check" d="M7 12l3 3 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
            </svg>
        </div>
        <div class="flex-grow-1">
            <strong>{{ session('success') }}</strong>
        </div>
        <button type="button" class="btn-close" onclick="this.parentElement.parentElement.style.display='none'" aria-label="Close"></button>
    </div>
    <div class="success-particles"></div>
</div>

<style>
.success-alert-animated {
    animation: slideDownFadeIn 0.6s ease-out;
    border-left: 4px solid #28a745;
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.15);
    margin-bottom: 20px;
}

.success-icon-container {
    animation: scaleIn 0.5s ease-out 0.2s both;
    flex-shrink: 0;
}

.success-checkmark {
    color: #28a745;
}

.checkmark-circle {
    stroke-dasharray: 62.83;
    stroke-dashoffset: 62.83;
    animation: circleDraw 0.6s ease-out 0.3s both;
}

.checkmark-check {
    stroke-dasharray: 15;
    stroke-dashoffset: 15;
    animation: checkDraw 0.5s ease-out 0.8s both;
}

.success-particles::before,
.success-particles::after {
    content: '';
    position: absolute;
    width: 8px;
    height: 8px;
    background: #28a745;
    border-radius: 50%;
    opacity: 0;
    animation: particleFloat 2.5s ease-in-out infinite;
}

.success-particles::before {
    top: 15px;
    right: 25px;
    animation-delay: 1s;
}

.success-particles::after {
    top: 20px;
    right: 35px;
    animation-delay: 1.3s;
}

@keyframes slideDownFadeIn {
    from {
        transform: translateY(-30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes scaleIn {
    from {
        transform: scale(0);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes circleDraw {
    to {
        stroke-dashoffset: 0;
    }
}

@keyframes checkDraw {
    to {
        stroke-dashoffset: 0;
    }
}

@keyframes particleFloat {
    0% {
        transform: translateY(0) translateX(0) scale(0);
        opacity: 0;
    }
    20% {
        opacity: 1;
    }
    50% {
        transform: translateY(-25px) translateX(15px) scale(1);
        opacity: 0.8;
    }
    100% {
        transform: translateY(-40px) translateX(25px) scale(0);
        opacity: 0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const successAlert = document.querySelector('.success-alert-animated');
    if (successAlert) {
        // Scroll to top smoothly to show the message
        setTimeout(function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }, 100);
        
        // Auto-hide after 6 seconds
        setTimeout(function() {
            successAlert.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
            successAlert.style.opacity = '0';
            successAlert.style.transform = 'translateY(-20px)';
            setTimeout(function() {
                successAlert.style.display = 'none';
            }, 500);
        }, 6000);
    }
});
</script>
@endif

