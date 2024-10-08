<div id="session-timer">
    <p>Tempo restante da sessão: <span id="countdown"></span></p>
</div>

<script>
    // Defina o tempo de sessão em segundos (por exemplo, 300 segundos para 5 minutos)
    var sessionDuration = "<?php echo SessionTimer::getSessionTime(); ?>";
    var countdownElement = document.getElementById('countdown');

    function startCountdown(duration) {
        var timer = duration, minutes, seconds;

        setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            countdownElement.textContent = minutes + ":" + seconds;

            if (--timer < 0) {
                // A sessão expirou, redirecione para o login
                window.location.href = '<?php echo Yii::app()->createUrl('site/login'); ?>';
            }
        }, 1000);
    }

    startCountdown(sessionDuration);
</script>
