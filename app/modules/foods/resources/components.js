/* DateComponent */

$('.js-date').mask("99/99/9999");
$(".js-date").datepicker({
  locate: "pt-BR",
  format: "dd/mm/yyyy",
  autoclose: true,
  todayHighlight: true,
  allowInputToggle: true,
  disableTouchKeyboard: true,
  keyboardNavigation: false,
  orientation: "bottom left",
  clearBtn: true,
  maxViewMode: 2,
  startDate: "01/01/" + $(".school-year").val(),
  endDate: "31/12/" + $(".school-year").val()
}).on('changeDate', function (ev, indirect) {
  data.actions.addDays($(this).val())
  $(".js-add-meal").removeClass('hide')
})

$(document).on("click", '.js-change-pagination', function () {
  $('.js-change-pagination.active').each(function () {
    $(this).removeClass('active');
  });
  $(this).addClass("active");
})

const DateComponent = function () {

  const days = []
  const daysOfWeek = ["Domingo", "Segunda-freia", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado"]

  function addDays(dayStart) {
    days.length = 0;
    const [day, month, year] = dayStart.split('/')
    const dateFormat = [year, month, day].join('-');
    const date = new Date(dateFormat)
  
      for (let i = 0; i < 7; i++) {
        date.setDate(date.getDate()+1)
        if(date.getDay() != 0 && date.getDay() != 6){
          let [day, month, year] =[date.getDate(), date.getMonth()+1, date.getFullYear()]
          day = day < 10 ? '0'+day : day
          days.push({
            date: [day, month, year].join('/'),
            order: date.getDay()
          });
        }
        

      }

    render(days)
  }

  function render(days) {
    const container = $(".js-days-of-week-component");
    const template = days.reduce((html, day, index) => {
      const isActive = index === 0 ? "active" : "";
      return html +
        `<li class="t-tabs__item js-day-tab js-change-pagination ${isActive}" data-day-of-week=${day.order} >
          <div class="text-primary">${daysOfWeek[day.order]}</div>
          <div class="text-secondary">${day.date}</div>
        </li>`;
    }, `<ul class="t-tabs__list">`) +
      `<li class="t-tabs__item js-change-pagination">
        <div class="text-primary">Selecionar <br> outra data</div>
      </li>
      </ul>`;
    container.html(template)
  }

  return {
    actions: {
      addDays: addDays
    }
  }
}

const data = DateComponent();
$(document).on('input', '.js-date', function () {
  data.actions.addDays($(this).val())
})

/* MealsComponent */

const MealsComponent = function () {

  function addMeal() {
    if($(".ui-accordion-header").length > 0){
      $('.js-meals-component').accordion("destroy");  
    }

    render($('.js-day-tab.active').attr("data-day-of-week"))
  }

  function render(mealDay) {
    const container = $(".js-meals-component");
    let template = `

    <div class="ui-accordion-header" data-day-of-week="${mealDay}">
      titulo
    </div>
    <div class="ui-accordion-content">
      conteudo
    </div>      

          `;
    container.append(template)
      
    $(".js-meals-component" ).accordion({
      active: false,
      collapsible: true,
      icons: false,
    });    
  }

  return {
    actions: {
      addMeal: addMeal
    }
  }
}

const meals = MealsComponent();

$(document).on("click", ".js-add-meal", function () {  
  meals.actions.addMeal()
});
