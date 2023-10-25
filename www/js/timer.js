var timerId;

// Проверяем, есть ли сохраненные значения времени в localStorage для текущего id
function checkLocalStorage(id) {
  var minuteLeft = 19;
  var secondLeft = 59;
  
  if(localStorage.getItem('minuteLeft-' + id) && localStorage.getItem('secondLeft-' + id)) {
    minuteLeft = parseInt(localStorage.getItem('minuteLeft-' + id));
    secondLeft = parseInt(localStorage.getItem('secondLeft-' + id));
  }
  
  return {
    minuteLeft: minuteLeft,
    secondLeft: secondLeft
  };
}

// Обновляем значения времени в localStorage для текущего id
function updateLocalStorage(id, minuteLeft, secondLeft) {
  localStorage.setItem('minuteLeft-' + id, minuteLeft);
  localStorage.setItem('secondLeft-' + id, secondLeft);
}

function countdown(id) {
  var time = checkLocalStorage(id);
  var minuteLeft = time.minuteLeft;
  var secondLeft = time.secondLeft;

  document.getElementById('countdown').textContent = minuteLeft;
  document.getElementById('seconds').textContent = secondLeft;
  
  if (minuteLeft < 0) {
    clearTimeout(timerId);
    localStorage.removeItem('minuteLeft-' + id);
    localStorage.removeItem('secondLeft-' + id);
    var urlParams = new URLSearchParams(window.location.search);
    var count = urlParams.get('count');
    window.location.href = "/testing/check?respondentId=" + id + "&count=" + count;
  } else {
    if (secondLeft <= 0) {
      minuteLeft--;
      secondLeft = 60;
    }
    secondLeft--;
  }
  
  updateLocalStorage(id, minuteLeft, secondLeft);
}

// Запускаем таймер каждую секунду
var urlParams = new URLSearchParams(window.location.search);
var id = urlParams.get('respondentId');
timerId = setInterval(function() {
  countdown(id);
}, 1000);