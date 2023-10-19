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

const DataComponent = function () {

  const days = {
    monday: '',
    tuesday: '',
    wednesday: '',
    thursday: '',
    friday: ''
  };

  function addDays(dayStart) {
    const [day, month, year] = dayStart.split('/')
    const dateFormat = [year, month, day].join('-');
    const date = new Date(dateFormat)

    const daysOfWeek = new Map();
    daysOfWeek.set(date.getDay(), dayStart)
    date.setDate(date.getDate() + 1)
      console.log(date)
    /* while(daysOfWeek.size <= 7){
      let nextDay = date.getDate() + 1
     
        date.setDate(date.getDate() + 1)
        console.log()
  
      
    } */
    

    /* Array.from(daysOfWeek.entries()); */
    days.monday = dayStart
    days.tuesday = dayStart
    days.wednesday = dayStart
    days.thursday = dayStart
    days.friday = dayStart
    render(days)
  }

  function render(days) {
    const container = $(".js-days-of-week-component");
    let template = `

          <ul class="t-tabs__list">
            <li class="t-tabs__item active js-change-paginatiobn">
              <div class="text-primary">Segunda-feira</div>
              <div class="text-secondary">${days.monday}</div>
            </li>
            <li class="t-tabs__item js-change-paginatiobn">
              <div class="text-primary">Terça-feira</div>
              <div class="text-secondary">${days.tuesday}</div>
            </li>
            <li class="t-tabs__item js-change-paginatiobn">
              <div class="text-primary">Quarta-feira</div>
              <div class="text-secondary">${days.wednesday}</div>
            </li>
            <li class="t-tabs__item js-change-paginatiobn">
              <div class="text-primary">Quinta-feira</div>
              <div class="text-secondary">${days.thursday}</div>
            </li>
            <li class="t-tabs__item js-change-paginatiobn">
              <div class="text-primary">Sexta-feira</div>
              <div class="text-secondary">${days.friday}</div>
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

const data = DataComponent();
$(document).on('input', '.js-date', function () {
  data.actions.addDays($(this).val())
})