@php
    use App\Services\LifeService;
    $lifeService = app(LifeService::class);
    $lives = auth()->user()->lives;
    $maxLives = LifeService::MAX_LIVES;
    $refillSeconds = $lifeService->secondsUntilNextRefill(auth()->user());
@endphp

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const livesEl = document.querySelector('.lives-count');
        const timerEl = document.querySelector('.lives-timer');
        if (!livesEl) return;

        let lives = {{ $lives }};
        const maxLives = {{ $maxLives }};
        let seconds = {{ $refillSeconds }};

        function formatTime(totalSec) {
            const h = Math.floor(totalSec / 3600);
            const m = Math.floor((totalSec % 3600) / 60);
            const s = Math.floor(totalSec % 60);
            return (h > 0 ? h + ':' : '') + String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
        }

        function tick() {
            if (lives >= maxLives) {
                timerEl.classList.add('hidden');
                return;
            }
            if (seconds <= 0) {
                lives++;
                seconds = {{ LifeService::REFILL_INTERVAL_HOURS * 3600 }};
                livesEl.textContent = lives;

                if (lives >= maxLives) {
                    timerEl.classList.add('hidden');
                    return;
                }
            }
            timerEl.textContent = formatTime(seconds);
            timerEl.classList.remove('hidden');
            seconds--;
        }

        tick();
        setInterval(tick, 1000);
    });
</script>