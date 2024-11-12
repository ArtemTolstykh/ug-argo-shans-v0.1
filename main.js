/*
document.getElementById("dataForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Отключение стандартного поведения формы

    const formData = new FormData(this);

    fetch("add_data.php", {
      method: "POST",
      body: formData
    })
    .then(response => response.text()) // Можно заменить на response.json() для JSON
    .then(data => {
      document.getElementById("response").innerText = data; // Отображение ответа от сервера
    })
    .catch(error => {
      console.error("Ошибка:", error);
    });
  });
*/

$(document).ready(function() {
  $('#dataForm').submit(function(event) {
    event.preventDefault();
    
    $.ajax({
      type: "POST",
      url: "add_data.php",
      data: $('#dataForm').serialize(),
      success: function(response) {
        var data = JSON.parse(response);
        if (data.redirect) {
          window.location.href = data.redirect;
        } else {
          console.log("Уникаьлный пользовалеть. Запрос успешно выполнен!");
        }
      }
    });
  });
});

document.getElementById('dataForm').addEventListener('submit', (event) => {
    
    const slide1 = document.getElementById('slide1');
    const slide2 = document.getElementById('slide2');

    // Делаем slide2 видимым
    slide2.style.display = 'flex';
    slide2.style.transform = 'translateY(0)';

    // Анимация: slide1 уезжает вниз, slide2 появляется
    slide1.style.transform = 'translateY(100%)';

});



// Обработка для кнопки confirmButton на втором слайде
/*document.getElementById('confirmButton').addEventListener('click', () => {
    const slide1 = document.getElementById('slide1');
    const slide2 = document.getElementById('slide2');

    // Переключение: второй слайд уходит, первый выезжает
    slide2.style.transform = 'translateY(100%)';
    slide1.style.transform = 'translateY(0)';

    // Обновляем состояния
    isSlide1Visible = true;
    isSlide2Visible = false;
});
*/

//проверка ввода телефона
document.getElementById('phone').addEventListener('input', function (e) {
    const input = e.target;
    const value = input.value;

    // Если пользователь начинает ввод не с +7, то автоматически добавляем +7
    if (!value.startsWith('+7')) {
        input.value = '+7' + ' ' + value.replace(/[^\d]/g, ''); // Убираем любые символы кроме цифр
    }

    // Ограничиваем ввод длиной 16 символов (12 цифр после +7)
    if (input.value.length > 20) {
        input.value = input.value.slice(0, 20);
    }
});

// надписи и цвета на секторах
const prizes = [
  { text: "", color: "hsl(197 30% 43%)", count: 0 },
  { text: "", color: "hsl(173 58% 39%)", count: 0 },
  { text: "", color: "hsl(43 74% 66%)", count: 0 },
  { text: "", color: "hsl(27 87% 67%)", count: 0 },
  { text: "", color: "hsl(12 76% 61%)", count: 0 },
];

/*  Фирменные цвета
  { text: "", color: "hsl(354 96% 38%)", count: 0 },
  { text: "", color: "hsl(171 69% 22%)", count: 0 },
  { text: "", color: "hsl(208 78% 38%)", count: 0 },
  { text: "", color: "hsl(32 100% 47%)", count: 0 },
  { text: "", color: "hsl(328 100% 44%)", count: 0 },
  */

let totalSpins = 0; //Кол во спинов 


// создаём переменные для доступа к элементам статистики
const statsList = document.getElementById("stats-list");

// создаём функцию для обновления статистики
const updateStats = () => {
  const statsList = document.getElementById("stats-list");
  statsList.innerHTML = ""; // очищаем список

  // обновляем статистику для каждого приза
  prizes.forEach(prize => {
    const probability = ((prize.count / totalSpins) * 100).toFixed(2);
    statsList.insertAdjacentHTML(
      "beforeend",
      `<li>${prize.text}: ${prize.count} раз(а), Вероятность: ${probability}%</li>`
    );
  });
};

// функция выбора призового сектора
const statsPrize = () => {

  const selected = Math.floor(rotation / prizeSlice);

  prizes[selected].count += 1; // увеличиваем количество выпадений для выбранного приза

  totalSpins++; // увеличиваем общее количество спинов

  prizeNodes[selected].classList.add(selectedClass);

  updateStats(); // обновляем статистику
};


// создаём переменные для быстрого доступа ко всем объектам на странице — блоку в целом, колесу, кнопке и язычку

const wheel = document.querySelector(".deal-wheel");
const spinner = wheel.querySelector(".spinner");
const trigger = wheel.querySelector(".btn-spin");
const ticker = wheel.querySelector(".ticker");

// на сколько секторов нарезаем круг
const prizeSlice = 360 / prizes.length;
// на какое расстояние смещаем сектора друг относительно друга
const prizeOffset = Math.floor(180 / prizes.length);
// прописываем CSS-классы, которые будем добавлять и убирать из стилей
const spinClass = "is-spinning";
const selectedClass = "selected";
// получаем все значения параметров стилей у секторов
const spinnerStyles = window.getComputedStyle(spinner);

// переменная для анимации
let tickerAnim;
// угол вращения
let rotation = 0;
// текущий сектор
let currentSlice = 0;
// переменная для текстовых подписей
let prizeNodes;

// расставляем текст по секторам
const createPrizeNodes = () => {
  // обрабатываем каждую подпись
  prizes.forEach(({ text, color, reaction }, i) => {
    // каждой из них назначаем свой угол поворота
    const rotation = ((prizeSlice * i) * -1) - prizeOffset;
    // добавляем код с размещением текста на страницу в конец блока spinner
    spinner.insertAdjacentHTML(
      "beforeend",
      // текст при этом уже оформлен нужными стилями
      `<li class="prize" data-reaction=${reaction} style="--rotate: ${rotation}deg">
        <span class="text">${text}</span>
      </li>`
    );
  });
};

// рисуем разноцветные секторы
const createConicGradient = () => {
  // устанавливаем нужное значение стиля у элемента spinner
  spinner.setAttribute(
    "style",
    `background: conic-gradient(
      from -90deg,
      ${prizes
        // получаем цвет текущего сектора
        .map(({ color }, i) => `${color} 0 ${(100 / prizes.length) * (prizes.length - i)}%`)
        .reverse()
      }
    );`
  );
};

// создаём функцию, которая нарисует колесо в сборе
const setupWheel = () => {
  // сначала секторы
  createConicGradient();
  // потом текст
  createPrizeNodes();
  // а потом мы получим список всех призов на странице, чтобы работать с ними как с объектами
  prizeNodes = wheel.querySelectorAll(".prize");
};

// определяем количество оборотов, которое сделает наше колесо
const spinertia = (min, max) => {
  min = Math.ceil(min);
  max = Math.floor(max);
  return Math.floor(Math.random() * (max - min + 1)) + min;
};

// функция запуска вращения с плавной остановкой
const runTickerAnimation = () => {
  // взяли код анимации отсюда: https://css-tricks.com/get-value-of-css-rotation-through-javascript/
  const values = spinnerStyles.transform.split("(")[1].split(")")[0].split(",");
  const a = values[0];
  const b = values[1];  
  let rad = Math.atan2(b, a);
  
  if (rad < 0) rad += (2 * Math.PI);
  
  const angle = Math.round(rad * (180 / Math.PI));
  const slice = Math.floor(angle / prizeSlice);

  // анимация язычка, когда его задевает колесо при вращении
  // если появился новый сектор
  if (currentSlice !== slice) {
    // убираем анимацию язычка
    ticker.style.animation = "none";
    // и через 10 миллисекунд отменяем это, чтобы он вернулся в первоначальное положение
    setTimeout(() => ticker.style.animation = null, 10);
    // после того, как язычок прошёл сектор - делаем его текущим 
    currentSlice = slice;
  }
  // запускаем анимацию
  tickerAnim = requestAnimationFrame(runTickerAnimation);
};

let prizeCount = 0;

// функция выбора призового сектора
const selectPrize = () => {
  const selected = Math.floor(rotation / prizeSlice);
  const selectedPrize = prizeNodes[selected].textContent.trim();
  prizeNodes[selected].classList.add(selectedClass);

  // Выводим название выпавшего приза в консоль
  console.log(`Выпал приз: ${selectedPrize}`);
//**************************************************
  


  if (prizeCount >= 1) {
    increaseSpins();
  }

};
prizeCount++;

// отслеживаем нажатие на кнопку
trigger.addEventListener("click", () => {
  // делаем её недоступной для нажатия
  trigger.disabled = true;
  // задаём начальное вращение колеса
  rotation = Math.floor(Math.random() * 360 + spinertia(2000, 5000));
  // убираем прошлый приз
  prizeNodes.forEach((prize) => prize.classList.remove(selectedClass));
  // добавляем колесу класс is-spinning, с помощью которого реализуем нужную отрисовку
  wheel.classList.add(spinClass);
  // через CSS говорим секторам, как им повернуться
  spinner.style.setProperty("--rotate", rotation);
  // возвращаем язычок в горизонтальную позицию
  ticker.style.animation = "none";
  // запускаем анимацию вращение
  runTickerAnimation();
  // переменная для отслеживания общего числа спинов
  //totalSpinsNew += 1;

});

// отслеживаем, когда закончилась анимация вращения колеса
spinner.addEventListener("transitionend", () => {
  // останавливаем отрисовку вращения
  cancelAnimationFrame(tickerAnim);
  // получаем текущее значение поворота колеса
  rotation %= 360;
  // выбираем приз
  selectPrize();
  // убираем класс, который отвечает за вращение
  wheel.classList.remove(spinClass);
  // отправляем в CSS новое положение поворота колеса
  spinner.style.setProperty("--rotate", rotation);
  // делаем кнопку снова активной
  trigger.disabled = false;
});

// подготавливаем всё к первому запуску
setupWheel();


// Отслеживаем изменение totalSpins
function increaseSpins() {
  setTimeout(() => {
    window.location.href = 'prize-output.php';
  }, 300);
}


/*
// Получаем элементы модального окна и кнопки закрытия
let modal = document.getElementById("myModal");
let confirmButton = document.getElementById("confirmButton");
let closeButton = document.getElementsByClassName("close")[0];

// Функция для открытия модального окна
function openModal() {
  modal.style.display = "flex";
}

// Функция для закрытия модального окна
function closeModal() {
  window.location.reload();

  //modal.style.display = "none";  
}




// Закрытие модального окна при клике на "Подтвердить" или на кнопку закрытия
confirmButton.onclick = closeModal;
closeButton.onclick = closeModal;

// Закрытие модального окна при клике вне его
//window.onclick = function(event) {
//  if (event.target == modal) {
//    closeModal();
//  }
//}

// Пример использования: увеличиваем totalSpins и проверяем модальное окно

//console.log('Кол-во спинов: ', totalSpins);
 

let fullName = document.getElementById('fullname');
let phone = document.getElementById('phone');
let hectares = document.getElementById('hectares');
let farmName = document.getElementById('farmname');


fullName.value = '';
  phone.value = '';
  hectares.value = '';
  farmName.value = '';



$sql_output = "
        SELECT 
            w.fullname AS person_name,    -- ФИО человека
            p.name AS prize_name          -- Название приза
        FROM 
            itog i
        JOIN 
            winners w ON i.winners_id = w.id
        JOIN 
            prizes p ON i.prizes_id = p.id;
        ";
*/