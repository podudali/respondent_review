var minuteLeft = 1;
var secondLeft = 60;
var timerId;

// Проверяем, есть ли сохраненные значения времени в localStorage
if(localStorage.getItem('minuteLeft') && localStorage.getItem('secondLeft')) {
  minuteLeft = parseInt(localStorage.getItem('minuteLeft'));
  secondLeft = parseInt(localStorage.getItem('secondLeft'));
}

// Функция, вызываемая каждую секунду
function countdown() {
    // Обновляем значение элемента на странице
    document.getElementById('countdown').textContent = minuteLeft;
    document.getElementById('seconds').textContent = secondLeft;
    
    // Если время закончилось, останавливаем таймер и выполняем редирект
    if (minuteLeft <= 0 && secondLeft <= 0) {
        clearInterval(timerId);
        
        // Очищаем сохраненные значения времени в localStorage
        localStorage.removeItem('minuteLeft');
        localStorage.removeItem('secondLeft');
        // Получаем значение параметра id из URL
        var urlParams = new URLSearchParams(window.location.search);
        var id = urlParams.get('respondentId');
        var count = urlParams.get('count');
        
        // Используем значение id для редиректа
        window.location.href = "/testing/check?respondentId=" + id + "&count=" + count;
    } else {
        // Уменьшаем оставшееся время на 1 секунду
        if (secondLeft <= 0) {
            minuteLeft--;
            secondLeft = 59;
        } else {
            secondLeft--;
        }
    }
    
    // Сохраняем значения времени в localStorage
    localStorage.setItem('minuteLeft', minuteLeft);
    localStorage.setItem('secondLeft', secondLeft);
}

// Запускаем таймер каждую секунду
timerId = setInterval(countdown, 1000);



function resetTimer() {
    
    minuteLeft = 1;
    secondLeft = 60;
  
    // Очистить сохраненные значения времени в localStorage
    localStorage.removeItem('minuteLeft');
    localStorage.removeItem('secondLeft');
  
    // Остановить существующий таймер
    clearInterval(timerId);
  
    // Запустить новый таймер
    timerId = setInterval(countdown, 1000);
  }
  // Устанавливаем интервал в 30 минут (30 минут * 60 секунд * 1000 миллисекунд)
var resetInterval = 30 * 60 * 1000;

// Запускаем таймер сброса каждые 30 минут
setInterval(resetTimer, resetInterval);