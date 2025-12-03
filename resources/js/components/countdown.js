const registerCountdownComponent = () => {
    if (!window.Alpine || typeof window.Alpine.data !== 'function') {
        return;
    }

    window.Alpine.data('countdownTimer', (opts = {}) => ({
        endAt: opts.endAt ?? null,
        timerId: null,
        days: 0,
        hours: 0,
        minutes: 0,
        seconds: 0,
        init() {
            const target = this.parseEndAt(this.endAt);
            if (!target) {
                this.setZero();
                return;
            }

            this.tick(target);
            this.timerId = window.setInterval(() => this.tick(target), 1000);
        },
        destroy() {
            if (this.timerId) {
                window.clearInterval(this.timerId);
                this.timerId = null;
            }
        },
        parseEndAt(value) {
            if (!value) return null;
            const parsed = new Date(value);
            return Number.isNaN(parsed.getTime()) ? null : parsed;
        },
        tick(target) {
            const diffMs = target.getTime() - Date.now();
            if (diffMs <= 0) {
                this.setZero();
                this.destroy();
                return;
            }

            const totalSeconds = Math.floor(diffMs / 1000);
            this.days = Math.floor(totalSeconds / 86400);
            const remainderAfterDays = totalSeconds % 86400;
            this.hours = Math.floor(remainderAfterDays / 3600);
            const remainderAfterHours = remainderAfterDays % 3600;
            this.minutes = Math.floor(remainderAfterHours / 60);
            this.seconds = remainderAfterHours % 60;
        },
        setZero() {
            this.days = this.hours = this.minutes = this.seconds = 0;
        },
        pad(value) {
            return String(value).padStart(2, '0');
        },
    }));
};

if (window.Alpine) {
    registerCountdownComponent();
} else {
    document.addEventListener('alpine:init', registerCountdownComponent);
}

const registerLivewireCleanup = () => {
    if (!window.Livewire || typeof window.Livewire.hook !== 'function') {
        return;
    }

    window.Livewire.hook('morph.removing', ({ el }) => {
        try {
            const data = el.__x && el.__x.$data;
            if (data && typeof data.destroy === 'function') {
                data.destroy();
            }
        } catch (_) {
            // silently ignore cleanup failures
        }
    });
};

if (window.Livewire && typeof window.Livewire.hook === 'function') {
    registerLivewireCleanup();
} else {
    document.addEventListener('livewire:init', registerLivewireCleanup);
}
