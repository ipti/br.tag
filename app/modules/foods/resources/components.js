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
})

$(document).on("click", '.js-change-paginatiobn', function () {
  $('.js-change-paginatiobn.active').each(function () {
    $(this).removeClass('active');
  });
  $(this).addClass("active");
})

const DateComponent = function () {

  const days = ['', '', '', '', '', '', '']

  function addDays(dayStart) {
    const [day, month, year] = dayStart.split('/')
    const dateFormat = [year, month, day].join('-');
    const date = new Date(dateFormat)
  
      for (let i = 0; i < 7; i++) {
        date.setDate(date.getDate()+1)
        console.log(date.toDateString())
        let [day, month, year] =[date.getDate(), date.getMonth()+1, date.getFullYear()]
        day = day < 10 ? '0'+day : day
        days[date.getDay()] = [day, month, year].join('/');

      }

    render(days)
  }

  function render(days) {
    const container = $(".js-days-of-week-component");
    let template = `

          <ul class="t-tabs__list">
            <li class="t-tabs__item active js-change-paginatiobn">
              <div class="text-primary">Segunda-feira</div>
              <div class="text-secondary">${days[1]}</div>
            </li>
            <li class="t-tabs__item js-change-paginatiobn">
              <div class="text-primary">Terça-feira</div>
              <div class="text-secondary">${days[2]}</div>
            </li>
            <li class="t-tabs__item js-change-paginatiobn">
              <div class="text-primary">Quarta-feira</div>
              <div class="text-secondary">${days[3]}</div>
            </li>
            <li class="t-tabs__item js-change-paginatiobn">
              <div class="text-primary">Quinta-feira</div>
              <div class="text-secondary">${days[4]}</div>
            </li>
            <li class="t-tabs__item js-change-paginatiobn">
              <div class="text-primary">Sexta-feira</div>
              <div class="text-secondary">${days[5]}</div>
            </li>
            <li class="t-tabs__item js-change-paginatiobn">
              <div class="text-primary">Selecionar <br> outra data</div>
            </li>
				</ul>
          `;
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
  render()
  

  function addDays() {
   

    render()
  }

  function render() {
    const container = $(".js-meals-component");
    let template = `

    <div class="ui-accordion-header">
      titulo
    </div>
    <div class="ui-accordion-content">
      conteudo
    </div>      

          `;
    container.html(template)
    $(".js-meals-component" ).accordion({
      active: false,
      collapsible: true,
      icons: false,
    });    
  }

  return {
    actions: {
      addDays: addDays
    }
  }
}

const meals = MealsComponent();