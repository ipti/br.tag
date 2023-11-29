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

const DateComponent = function () {

  const days = []
  const daysOfWeek = ["Domingo", "Segunda-freia", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado"]

  function addDays(dayStart) {
    days.length = 0;
    const [day, month, year] = dayStart.split('/')
    const dateFormat = [year, month, day].join('-');
    const date = new Date(dateFormat)

    for (let i = 0; i < 7; i++) {
      date.setDate(date.getDate() + 1)
      if (date.getDay() != 0 && date.getDay() != 6) {
        let [day, month, year] = [date.getDate(), date.getMonth() + 1, date.getFullYear()]
        day = day < 10 ? '0' + day : day
        days.push({
          date: [day, month, year].join('/'),
          order: date.getDay()
        });
      }


    }

    render(days)
  }
  function getLastDay(){
    return days[days.length-1].date
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
    }, `<ul class="t-tabs__list">`);
    container.html(template)
  }

  return {
    actions: {
      addDays: addDays,
      getLastDay: getLastDay
    }
  }
}

const data = DateComponent();

$(document).on('input', '.js-date', function () {
  data.actions.addDays($(this).val())
})

/* PlateComponent */
const PlateComponent = function () {
  let idPlateAccordion = 0;
  let foods = [] 
  getFoodList()


  function addPlate(idMealAccordion) {
    render(idMealAccordion)
    initializeSelect2()
  }
  function initializePlateAccordion(accordionActive) {
    const container = $(`.js-plate-accordion-header[data-id-accordion='${accordionActive}']`).parent()
   
    if(container.data('ui-accordion')) {
      $(container).accordion("destroy");
    }
    container.accordion({
      active: Number(accordionActive),
      collapsible: true,
      icons: false,
    });
    $(".js-plate-accordion-header").off("keydown");
  }
  function render(idMealAccordion) {

    const container = $(`.ui-accordion-content[data-id-accordion='${idMealAccordion}'] .js-plate-accordion`) 
    let template = `
      <div class="ui-accordion-header js-plate-accordion-header" data-id-accordion='${idPlateAccordion}' data-meal-id='${idMealAccordion}'>
        <input type="text" class="t-accordion-input-header js-plate-name" autofocus="true" placeholder="Digite o nome do prato" />
        <label>
        <span class="fa fa-pencil"  id="js-stopPropagation"></span>
        </label>
        <div class="row js-ingredients-names"></div>
      </div>
      <div class="ui-accordion-content js-plate-accordion-content" data-id-accordion='${idPlateAccordion}' data-meal-id='${idMealAccordion}'>
        <div class="row">
          <div class="t-field-select column clearfix">
            <select class="t-field-select__input js-inicializate-select2 js-taco-foods">
                <option value="">Busque pelo Alimento (TACO)</option>
            </select>
          </div>
        </div>
        <table class="tag-table-secondary centralize js-meal-component-table">
          <tr>
            <th>Nome</th>
            <th>unidade</th>
            <th>Medida</th>
            <th>Quantidade</th>
            <th>PT</th>
            <th>LIP</th>
            <th>CHO</th>
            <th>KCAL</th>
            <th></th>
          </tr>
          <tr class='js-total hide'>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
          </tr>
        </table>
        <div class="row">
            <a class="t-button-icon-danger js-remove-plate">Remover Prato</a>
        </div>
      </div>
    `
    container.attr("data-id-accordion", idMealAccordion);
    container.append(template)
    removePlate(idPlateAccordion)
    addListTacoFood(idPlateAccordion)
    addRowToTable(idPlateAccordion)
    initializePlateAccordion(idPlateAccordion)
    idPlateAccordion++;
  }
  function removePlate(idPlateAccordion) {
  const button =  $(`.js-plate-accordion-content[data-id-accordion="${idPlateAccordion}"] .js-remove-plate`)

  button.on(
      "click", function (event) {
        $(`.js-plate-accordion-header[data-id-accordion="${idPlateAccordion}"], 
        .js-plate-accordion-content[data-id-accordion="${idPlateAccordion}"]`).remove()
      }
    )
  }
  function addListTacoFood(idPlateAccordion) {
    const select = $(`.js-plate-accordion-content[data-id-accordion="${idPlateAccordion}"] .js-taco-foods`)

    $.map(foods, function (name, id) {
      select.append($('<option>', {
          value: id,
          text: name
      }));
   });
   

}
  function getFoodList (){
    return new Promise(function(resolve, reject) {
      $.ajax({
          url: "?r=foods/foodMenu/getTacoFoods",
          type: "GET",
      }).done(function(response) {
          foods = JSON.parse(response);
          resolve(response);
      }).fail(function(error) {
          reject(error);
      });
      
  });
  }
  function addRowToTable(idPlateAccordion) {
    const select = $(`.js-plate-accordion-content[data-id-accordion="${idPlateAccordion}"] .js-taco-foods`)
    select.on('change', function (event) {
        getFood(select.val(), idPlateAccordion)
        $(select).val('');
        initializeSelect2();
    })
  }
  function getFood (idFood, idPlateAccordion){
    const table =  $(`.js-plate-accordion-content[data-id-accordion="${idPlateAccordion}"] .js-meal-component-table`)
    const totalLine = table.find('.js-total')
    $.ajax({
      url: "?r=foods/foodMenu/getFood",
      data: {
          idFood: idFood
      },
      type: "GET",
    }).success(function (response) {
        response = JSON.parse(DOMPurify.sanitize(response))
        let line = createMealComponent(response);
        const accordionActive = table.closest(".js-plate-accordion-content").attr('data-id-accordion')
        removeFood(line, accordionActive)
        totalLine.remove()
        addFoodMeasurement(line)
        addUnitMask(line)
        changeAmount(line)
        table.append(line)
        calculateNutritionalValue(table) 
        initializePlateAccordion(accordionActive)
        initializeSelect2()
    })
  }
  function changeAmount(line) {
    const input = line.find('.js-unit input')
    const select = line.find('.js-measure select')
    const td = line.find('.js-amount')
    input.on('input', function (event) {
      let newAmount = calculateAmount(
        select.find('option:selected').attr('data-value'), 
        select.find('option:selected').attr('data-measure'), input.val())
      td.text(newAmount)
    })
    select.on('change', function (event) {
      let newAmount = calculateAmount(
        select.find('option:selected').attr('data-value')
      , select.find('option:selected').attr('data-measure'), input.val())
      td.text(newAmount)
    })
  }
  function calculateAmount(value, measure, amount) {
      amount = amount == "" ? 0 : amount
      return (Number(amount) * Number(value)).toFixed(2) + measure
  }
  function calculateNutritionalValue(table) {
    let total_pt = total_lip = total_cho = total_kcal = 0;
    table.find('.js-pt').each((_,pt)=>{
      total_pt += Number(pt.innerHTML) ? Number(pt.innerHTML) : 0
    })
    table.find('.js-lip').each((_,lip)=>{
      total_lip += Number(lip.innerHTML) ? Number(lip.innerHTML) : 0
    })
    table.find('.js-cho').each((_,cho)=>{
      total_cho += Number(cho.innerHTML) ? Number(cho.innerHTML) : 0
    })
    table.find('.js-kcal').each((_,kcal)=>{
      total_kcal += Number(kcal.innerHTML) ? Number(kcal.innerHTML) : 0
    })

    const lineTotal = $(`<tr class='js-total'></tr>`)
        .append(`<td>Total</td>`)
        .append(`<td></td>`)
        .append(`<td></td>`)
        .append(`<td></td>`)
        .append(`<td>${total_pt.toFixed(2)}</td>`)
        .append(`<td>${total_lip.toFixed(2)}</td>`)
        .append(`<td>${total_cho.toFixed(2)}</td>`)
        .append(`<td>${total_kcal.toFixed(2)}</td>`)
        .append(`<td></td>`)
    table.append(lineTotal)
  }
  function createMealComponent({id, name, pt, lip, cho, kcal}) {
    const line =  $(`<tr class='js-food-ingredient' data-idTaco='${id}'></tr>`)
        .append(`<td class='js-food-name'>${name}</td>`)
        .append(`<td class='js-unit'><input class='t-field-text__input' type='text' style='width:50px !important'></td>`)
        .append(`<td class='js-measure'>
                <select class="js-inicializate-select2 t-field-select__input js-food-measurement" style='width:100px'>
                </select>
            </td>`)
        .append(`<td class='js-amount'></td>`)
        .append(`<td class='js-pt'>${pt}</td>`)
        .append(`<td class='js-lip'>${lip}</td>`)
        .append(`<td class='js-cho'>${cho}</td>`)
        .append(`<td class='js-kcal'>${kcal}</td>`)
        .append(`<td class='js-remove-taco-food' data-id-accordion='${idPlateAccordion}'><span class='t-icon-close t-button-icon'><span></td>`)

        return line;
  }
  function addFoodMeasurement(line) {
    return new Promise(function(resolve, reject) {
      $.ajax({
          url: "?r=foods/foodMenu/getFoodMeasurement",
          type: "GET",
      }).done(function(response) {
        cratedMeasurementOptions(JSON.parse(response), line)
      }).fail(function(error) {
          reject(error);
      });
    });
  }
  function cratedMeasurementOptions(measurements, line){
    const select = line.find('.js-food-measurement')
    measurements.forEach(obj => {
      const option = document.createElement("option");
      option.text = obj.unit;
      option.value = obj.id;
    
      // Adiciona os outros atributos como data-atributes
      option.setAttribute("data-measure", obj.measure);
      option.setAttribute("data-value", obj.value);
    
      // Adiciona a option ao select
      select.append(option);
    });
    initializeSelect2()
  }
  function removeFood(line, accordionActive) {
    
    line.find('.js-remove-taco-food').on(
      "click", function (event) {
         const table = line.closest('.js-meal-component-table')
        const totalLine = line.nextAll().last() 

        totalLine.remove()
        $(this).parent().remove()
        calculateNutritionalValue(table)
        initializePlateAccordion(accordionActive)
      }
    )
  }
  function addUnitMask(line) {
    const input = line.find('.js-unit input')
    console.log(input)
    $(input).mask('999.99', {reverse: true}); 
  }
  return {
    actions: {
      addPlate: addPlate,
    }
  }
}

/* MealsComponent */

const MealsComponent = function () {

  const plates = PlateComponent();
  let idMealAccordion = 0;
  let mealTypes =  []
  
  getMealTypeList()

  function addMeal() {
    const dayOfWeek = $('.js-day-tab.active').attr("data-day-of-week");
    render(dayOfWeek)

    initializeMealsAccordion()
    initializeSelect2()
  }
  function initializeMealsAccordion() {
    $('.js-meals-component').accordion("destroy");
    $( ".js-meals-component" ).accordion({
      active: idMealAccordion-1,
      collapsible: true,
      icons: false,
    });
  }

  function changeVisibleMeals(tabActive) {
    $('.js-meals-accordion-header, .js-meals-accordion-content').each(function () {
      $(this).attr("data-day-of-week") != tabActive.attr('data-day-of-week') ? $(this).addClass('hide') : $(this).removeClass('hide')
    })
  }

  function render(mealDay) {
    const container = $(".js-meals-component");

    let template = `

    <div class="ui-accordion-header js-meals-accordion-header row" data-day-of-week="${mealDay}" data-id-accordion="${idMealAccordion}">
      <div class="column justify-content--start js-meal-type">
        turno da refeição
      </div>
      <div class="column justify-content--space-between">
        <span></span>
        <span class="t-icon-trash js-remove-meal" data-id-accordion="${idMealAccordion}"></span>
      </div>
    </div>
    <div class="ui-accordion-content js-meals-accordion-content" data-day-of-week="${mealDay}" data-id-accordion="${idMealAccordion}">
      <div class="row">
        <div class="t-field-text column">
          <label class="t-field-text__label--required">Hora da refeição *</label>
          <input type='text' class='t-field-text__input js-mealTime' />
        </div>
        <div class="t-field-select column">
            <label class='t-field-select__label--required'>Refeição *</label>
            <select name="meal" class="js-inicializate-select2 select-search-on t-field-select__input js-food-meal-type js-change-meal-name">
              <option value="">Selecione a refeição</option>
          </select>
        </div>
      </div>
      <div class="row">
					<div class="t-field-select column">
            <label class="t-field-select__label--required">Turno *</label>
            <select class="js-inicializate-select2 select-search-on t-field-select__input js-shift">
                <option value="">Selecione o turno</option>
                <option value="M">Manhã</option>
                <option value="T">Tarde</option>
                <option value="N">Noite</option>
            </select>          
					</div>
					<div class="column"></div>
			</div>
      <div class"row">
        <div class="column t-buttons-container">
          <a class="t-button-secondary js-add-plate">
            <span class="t-icon-start"></span>
            Adicionar Prato
          </a>
        </div>
      </div>
      <div class="js-plate-accordion"></div>
    </div>     
          `;

    container.append(template)

    changeMealsName(idMealAccordion)
    addPlateToMeal(idMealAccordion)
    addMealTypeList(idMealAccordion)
    addHourMask(idMealAccordion)
    idMealAccordion++;

  }
  function addMealTypeList(idMealAccordion) {
    const select = $(`.js-meals-accordion-content[data-id-accordion="${idMealAccordion}"] .js-food-meal-type`)
    select.append(mealTypes)
  }
  function getMealTypeList (){
    return new Promise(function(resolve, reject) {
      $.ajax({
          url: "?r=foods/foodMenu/getMealType",
          type: "GET",
      }).done(function(response) {
        mealTypes = DOMPurify.sanitize(JSON.parse(response));
        resolve(response);
      }).fail(function(error) {
          reject(error);
      });
      
  });
  }
  function addHourMask(idMealAccordion) {
    const input =  $(`.js-meals-accordion-content[data-id-accordion="${idMealAccordion}"] .js-mealTime`)
    input.mask("99:99");
  }
  function removeMeal(idAccordion) {
    $(`.ui-accordion-header[data-id-accordion="${idAccordion}"], .ui-accordion-content[data-id-accordion="${idAccordion}"]`).remove()
  }

  function changeMealsName(idAccordion) {
    const select = $(`.ui-accordion-content[data-id-accordion='${idAccordion}'] .js-change-meal-name`)
    const title = $(`.ui-accordion-header[data-id-accordion='${idAccordion}'] .js-meal-type`)
      select.on("change",  function (event) {
        title.text($(this).select2('data').text);
      })
  }
  function addPlateToMeal(idAccordion) {
    const button = $(`.ui-accordion-content[data-id-accordion='${idAccordion}'] .js-add-plate`)
    
    
    button.on("click", function (event) {
      plates.actions.addPlate(idAccordion)
      initializeMealsAccordion()
    })
  }
  return {
    actions: {
      addMeal: addMeal,
      changeVisibleMeals: changeVisibleMeals,
      removeMeal: removeMeal,
    }
  }
}

const meals = MealsComponent();


$(document).on("click", ".js-add-meal", function () {
  meals.actions.addMeal()
});

$(document).on("click", ".js-remove-meal", function () {
  meals.actions.removeMeal($(this).attr("data-id-accordion"))
});

$(document).on("click", '.js-change-pagination', function () {
  let tabActive = $(this)
  $('.js-change-pagination.active').each(function () {
    $(this).removeClass('active');
  });
  tabActive.addClass("active");

  meals.actions.changeVisibleMeals(tabActive)
})


