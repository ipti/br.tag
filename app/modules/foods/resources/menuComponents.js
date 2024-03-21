let meals = [];
let idMeals = 0;
let idplates = 0;
let idIgredientes = 0;

function parseDOM(htmlString) {
  const wrapper = $('<div></div>');
  wrapper.append(htmlString);
  return wrapper;
}
function initializeMealAccordion(id = false) {
  $('.js-meals-component').accordion("destroy");
  $(".js-meals-component").accordion({
    heightStyle: "content",
    active: id,
    collapsible: true,
    icons: false,
  });
}
function initializeSelect2() {
  $("select.js-initialize-select2").select2("destroy");
  $('select.js-initialize-select2').select2();
}
/* DateComponent */

$('.js-date').mask("99/99/9999");


$(".js-date").datepicker({
  language: "pt-BR",
  format: "dd/mm/yyyy",
  autoclose: true,
  todayHighlight: true,
  allowInputToggle: true,
  disableTouchKeyboard: true,
  keyboardNavigation: false,
  orientation: "bottom left",
  clearBtn: true,
  maxViewMode: 2,
  showClearButton: false
});

const DateComponent = function () {

  const daysOfWeek = ["Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado"]

  function getLastDay() {
    return days[days.length - 1].date
  }
  function render() {
    const container = $(".js-days-of-week-component");
    const template = daysOfWeek.reduce((html, day, index) => {
      const isActive = index === 1 ? "active" : ""; // Adiciona a classe "active" à segunda-feira
      const isWeekend = index === 0 || index === 6; // Verifica se é sábado ou domingo

      if (!isWeekend) {
        return html +
          `<li class="t-tabs__item js-day-tab js-change-pagination ${isActive}" data-day-of-week=${index} >
            <div class="text-primary">${day}</div>
          </li>`;
      } else {
        return html; // Não adiciona <li> para sábado e domingo
      }
    }, `<ul class="t-tabs__list column">`);
    container.html(template);
  }

  return {
    actions: {
      render: render,
      getLastDay: getLastDay
    }
  }
}

const data = DateComponent();

data.actions.render()

/* PlateComponent */
const PlateComponent = function (plate) {

  function render() {

    let template = `
      <div class="ui-accordion-header js-plate-accordion-header mobile-row" data-id-accordion="${plate.id}">
        <div class='column flex-direction--row align-items--baseline'>
          <input type="text" class="t-accordion-input-header js-plate-name" autofocus="true" value='${plate.description}' name='Nome do Prato' placeholder="Digite o nome do prato" required='required' />
          <label>
          <span class="fa fa-pencil" id="js-stopPropagation"></span>
          </label>
        </div>
        <div class="column justify-content--space-between  border-left">
            <span class="js-ingredients-names"></span>
            <span class="t-icon-down_arrow arrow" ></span>
        </div>
      </div>
      <div class="ui-accordion-content accordion-overflow js-plate-accordion-content"  data-id-accordion="${plate.id}">
        <div class="row">
          <div class="t-field-select column clearfix">
            <select class="t-field-select__input js-initialize-select2 js-taco-foods">
                <option value="">Busque pelo Alimento (TACO)</option>
            </select>
          </div>
        </div>
        <table class="tag-table-secondary centralize js-meal-component-table">
          <tr>
            <th>Nome</th>
            <th>Unidade</th>
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
          <th ></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
        </tr>
        </table>
        <div class="row t-margin-medium--bottom">
            <a class="t-button-icon-danger js-remove-plate" data-id-plate="${plate.id}">Remover Prato</a>
        </div>
      </div>
    `
    const wrapper = parseDOM(template);

    wrapper.find(".js-plate-name").on("change", (e) => { plate.description = e.target.value });
    getFoodList(wrapper.find(".js-taco-foods"))
    const table = wrapper.find('table.js-meal-component-table')
    plate.foodIngredients.map((e) => {
      getFood(e, table)
    })
    wrapper.find(".js-remove-plate").on("click", (e) => {
      const plateIdToRemove = $(e.target).attr("data-id-plate");
      const day = $('.js-day-tab.active').attr("data-day-of-week")

      let accordionActive = 0
      for (let i = 0; i < meals.length; i++) {
        const meal = meals[i];
        const plateIndex = meal.plates.findIndex(plate => plate.id == plateIdToRemove);
        if (plateIndex !== -1) {
          accordionActive = i
          meal.plates.splice(plateIndex, 1);
        }

      }
      $(".js-meals-component").html('')
      meals.forEach((e) => {
        MealsComponent(e, day).actions.render();
      });
      initializeMealAccordion(accordionActive)
    })
    const selectFoods = wrapper.find('.js-taco-foods')
    addRowToTable(selectFoods, table)
    return wrapper.children()

  }

  function getFoodList(select) {

    $.ajax({
      url: "?r=foods/foodmenu/getTacoFoods",
      type: "GET",
    }).success(function (response) {
      let foods = JSON.parse(response);
      $.map(foods, function (name, id) {
        select.append($('<option>', {
          value: id,
          text: name
        }));
      });
    })


  }
  function addRowToTable(selectFoods, table) {
    selectFoods.on('change', (e) => {
      table.find('tbody').html('')
      table.find('tbody').append(`<tr>
            <th>Nome</th>
            <th>Unidade</th>
            <th>Medida</th>
            <th>Quantidade</th>
            <th>PT</th>
            <th>LIP</th>
            <th>CHO</th>
            <th>KCAL</th>
            <th></th>
        </tr>`)
      plate.foodIngredients.push({
        id: idIgredientes,
        foodIdFk: selectFoods.val(),
        foodMeasureUnitId: "",
        amount: "",
      })
      idIgredientes++
      plate.foodIngredients.map((e) => {
        getFood(e, table)
      })

      $(selectFoods).val('');
      initializeSelect2();
    })
  }
  function getFood(food, table) {
    let tacoFood = null
    $.ajax({
      url: "?r=foods/foodmenu/getFood",
      data: {
        idFood: food.foodIdFk
      },
      type: "GET",
    }).success(function (response) {
      response = JSON.parse(DOMPurify.sanitize(response))
      tacoFood = response
      let line = createMealComponent(response, food);
      const wrapper = parseDOM(line);
      wrapper.find(".js-unit input").on("input", (e) => { food.amount = e.target.value });
      wrapper.find('.js-remove-taco-food').on('click', (e) => {

        let accordionPlateActive = $(e.target).attr("data-id-plate")
        let ingredientId = $(e.target).attr("data-id-food-ingredients")
        let accordionMeals = 0
        for (let i = 0; i < meals.length; i++) {
          let meal = meals[i];
          let plateIndex = meal.plates.findIndex(plate => plate.id == accordionPlateActive);

          if (plateIndex !== -1) {
            meal.plates[plateIndex].foodIngredients = meal.plates[plateIndex].foodIngredients.filter(foodIngredient => foodIngredient.id != ingredientId);
            accordionMeals = i;
          }
        }

        const day = $('.js-day-tab.active').attr("data-day-of-week")
        $(".js-meals-component").html('')
        meals.forEach((e) => {
          MealsComponent(e, day).actions.render();
        });
        initializeMealAccordion(accordionMeals)

      })
      table.find('.js-total').remove()
      addFoodMeasurement(line, food)
      addUnitMask(line)
      changeAmount(line, food, tacoFood)
      addIngrendientsName(line.find('.js-food-name').text())
      table.append(line)
      calculateNutritionalValue(table)
    })
  }
  function changeAmount(line, food, tacoFood) {
    const input = line.find('.js-unit input')
    const select = line.find('.js-measure select')
    const td = line.find('.js-amount')

    input.on('input', function (event) {
            let newAmount = calculateAmount(
                select.find('option:selected').attr('data-value'),
                tacoFood,
                input.val(),
                select.find('option:selected').attr('data-measure'))
            td.find(".js-amount-value").text(newAmount)

    })
    select.on('change', function (event) {
        food.foodMeasureUnitId = select.val()
        let newAmount = calculateAmount(
            select.find('option:selected').attr('data-value'),
            tacoFood,
            input.val(),
            select.find('option:selected').attr('data-measure'))
            td.find(".js-amount-value").text(newAmount)
  })
  }
  function calculateAmount(value, tacoFood, amount, measure) {
    amount = amount == "" ? 0 : amount

    let result = (Number(amount) * Number(value)).toFixed(2)

    if(measure == 'u') {
        result = parseInt(result);
    }

    if(tacoFood.measurementUnit !="l" && measure == 'ml') {
        return result + 'g'
    }

    return result + measure
  }
  function calculateNutritionalValue(table) {
    let total_pt = total_lip = total_cho = total_kcal = 0;
    table.find('.js-pt').each((_, pt) => {
      total_pt += Number(pt.innerHTML) ? Number(pt.innerHTML) : 0
    })
    table.find('.js-lip').each((_, lip) => {
      total_lip += Number(lip.innerHTML) ? Number(lip.innerHTML) : 0
    })
    table.find('.js-cho').each((_, cho) => {
      total_cho += Number(cho.innerHTML) ? Number(cho.innerHTML) : 0
    })
    table.find('.js-kcal').each((_, kcal) => {
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
  function createMealComponent({ id, name, pt, lip, cho, kcal }, food) {
    const line = $(`<tr class='js-food-ingredient' data-idTaco='${id}'></tr>`)
      .append(`<td class='js-food-name'>${name}</td>`)
      .append(`<td class='js-unit'><input class='t-field-text__input' type='text' style='width:50px !important' required='required' name='Unidade' value='${food.amount}'></td>`)
      .append(`<td class='js-measure'>
                <select class="js-initialize-select2 t-field-select__input js-food-measurement" style='width:100px' required='required'>
                </select>
            </td>`)
      .append(`<td class='js-amount'>
                <span class="js-amount-value"></span>
             </td>`)
      .append(`<td class='js-pt'>${pt}</td>`)
      .append(`<td class='js-lip'>${lip}</td>`)
      .append(`<td class='js-cho'>${cho}</td>`)
      .append(`<td class='js-kcal'>${kcal}</td>`)
      .append(`<td class='js-remove-taco-food'><span class='t-icon-close t-button-icon' data-id-plate='${plate.id}' data-id-food-ingredients="${food.id}"><span></td>`)

    return line;
  }
  function addFoodMeasurement(line, food) {
    $.ajax({
      url: "?r=foods/foodmenu/getFoodMeasurement",
      type: "GET",
    }).success(function (response) {
      const measurements = JSON.parse(response)
      const select = line.find('.js-food-measurement')
      measurements.forEach(obj => {
        const option = document.createElement("option");
        option.text = obj.unit;
        option.value = obj.id;

        option.setAttribute("data-measure", obj.measure);
        option.setAttribute("data-value", obj.value);


        select.append(DOMPurify.sanitize((option)));
      });
      select.val(food.foodMeasureUnitId).trigger('change')
      initializeSelect2()
    })
  }
  function addUnitMask(line) {
    const input = line.find('.js-unit input')
    input.on('input', (e) => {
      const inputValue = e.target.value;

      if (/[^0-9.]/.test(inputValue)) {
        const sanitizedValue = inputValue.replace(/[^0-9.,]/g, '');

        $(e.target).val(sanitizedValue);
      }
    })
  }
  function addIngrendientsName(name) {

    let oldIngrendientsName = $(`.js-plate-accordion-header[data-id-accordion="${plate.id}"]  .js-ingredients-names`)
    let ingredientsList = oldIngrendientsName.text().trim().split(', ')
    let firstNameNewIngredient = name.split(', ')[0]

    if (ingredientsList.indexOf(firstNameNewIngredient) === -1) {
      ingredientsList[0] == "" ? ingredientsList[0] = firstNameNewIngredient : ingredientsList.push(firstNameNewIngredient)
    }

    let newIngredientsName = ingredientsList.join(", ");
    oldIngrendientsName.html(newIngredientsName)

  }
  function removeIngrendientsName(idAccordion, name) {

    let allSelectedIngredients = []

    let tdElements = $(`.js-plate-accordion-content[data-id-accordion="${idAccordion}"] .js-meal-component-table tr.js-food-ingredient td.js-food-name`)
    tdElements.each(function () {
      allSelectedIngredients.push($(this).text());
    });
    let firstNameIngredient = name.split(', ')[0];
    let count = allSelectedIngredients.reduce(function (acc, element) {
      return acc + (element.split(', ')[0] === firstNameIngredient);
    }, 0);

    if (count == 1) {
      let oldIngrendientsName = $(`.js-plate-accordion-header[data-id-accordion="${idAccordion}"]  .js-ingredients-names`)
      let ingredientsList = oldIngrendientsName.text().trim().split(', ')
      let newIngredientsName = ingredientsList.filter((ingredient) => ingredient != firstNameIngredient)
      oldIngrendientsName.html(newIngredientsName)
    }
  }
  return {
    actions: {
      render: render,
    }
  }
}

/* MealsComponent */

const MealsComponent = function (meal, day) {

  function render() {
    const container = $(".js-meals-component");
    let template = `

    <div class="ui-accordion-header js-meals-accordion-header mobile-row ${meal.mealDay != day ? 'hide' : ''}" data-day-of-week="${meal.mealDay}">
      <div class="column justify-content--start js-meal-name">
        ${meal.mealType == "Selecione a refeição" ? "Turno da refeição" : meal.mealType}
      </div>
      <div class="column justify-content--space-between border-left">
        <span></span>
        <span class="t-icon-trash js-remove-meal" data-id-accordion="${meal.id}" ></span>
      </div>
    </div>
    <div class="ui-accordion-content js-meals-accordion-content  ${meal.mealDay != day ? 'hide' : ''}" data-day-of-week="${meal.mealDay}">
      <div class="row">
        <div class="t-field-text column clearleft--on-mobile">
          <label class="t-field-text__label--required">Hora da Refeição</label>
          <input type='text' class='t-field-text__input js-mealTime' required='required' value="${meal.mealTime}" name='Hora da Refeição' />
        </div>
        <div class="t-field-select column clearleft--on-mobile">
            <label class='t-field-select__label--required'>Refeição</label>
            <select required='required' name='Refeição'
                    class="js-initialize-select2 select-search-on t-field-select__input js-meal-type">
              <option value="">Selecione a refeição</option>
          </select>
        </div>
      </div>
      <div class="row">
					<div class="t-field-select column clearleft--on-mobile">
            <label class="t-field-select__label--required">Turno</label>
            <select class="js-initialize-select2 select-search-on t-field-select__input js-shift" name='Turno' required='required'>
                <option value="">Selecione o turno</option>
                <option value="M">Manhã</option>
                <option value="T">Tarde</option>
                <option value="N">Noite</option>
            </select>
					</div>
					<div class="column "></div>
			</div>
      <div class"row">
        <div class="column clearleft--on-mobile t-buttons-container">
          <a class="t-button-secondary js-add-plate">
            <span class="t-icon-start"></span>
            Adicionar Prato
          </a>
        </div>
      </div>
      <div class="row">
        <div class="column t-accordeon--header">
          <div class="mobile-row">
            <div class="column">
              Prato
            </div>
            <div class="column">
              Ingredientes
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="js-plate-accordion column">
        </div>
      </div>
    </div>
          `;

    const wrapper = parseDOM(template);

    wrapper.find(".js-mealTime").on("change", (e) => { meal.mealTime = e.target.value });

    const title = wrapper.find('.js-meal-name')
    wrapper.find("select.js-meal-type").on("change", (e) => {

      meal.mealType = $(e.target).find(":selected").text();
      meal.mealTypeId = e.target.value

      if (meal.mealTypeId != "") {
        title.text(meal.mealType);
      }

    });
    wrapper.find("select.js-shift").on("change", (e) => { meal.shift = e.target.value })
    wrapper.find("select.js-shift").val(meal.shift).trigger("change")

    //adiciona prato à refeição
    const platesContainer = wrapper.find('.js-plate-accordion')
    wrapper.find('.js-add-plate').on("click", (e) => {
      const day = $('.js-day-tab.active').attr("data-day-of-week")
      $(".js-meals-component").html('')
      meal.plates.push({
        description: "",
        id: idplates,
        foodIngredients: []
      })
      idplates++

      meals.forEach((e) => {
        MealsComponent(e, day).actions.render();
      });
      initializeMealAccordion(meals.indexOf(meal))

      $(".js-plate-accordion-header").off("keydown");

    })

    getMealTypeList(wrapper.find(".js-meal-type"))

    // adiciona máscara no input de hora
    wrapper.find(".js-mealTime").mask("99:99")
    container.append(wrapper.children())
    const renderPlates = meal.plates.reduce((acc, plate) => acc.concat(PlateComponent(plate).actions.render()), []);
    platesContainer.html(renderPlates)
    platesContainer.accordion({
      heightStyle: "content",
      active: false,
      collapsible: true,
      icons: false,
    });
    $(".js-plate-accordion-header").off("keydown");
    initializeSelect2()
  }

  function getMealTypeList(select) {
    $.ajax({
      url: "?r=foods/foodmenu/getMealType",
      type: "GET",
    }).success(function (response) {
      select.append(DOMPurify.sanitize(JSON.parse(response)));
      select.val(meal.mealTypeId).trigger('change')
    })
  }
  return {
    actions: {
      render: render
    }
  }
}


$(document).on("click", ".js-add-meal", function () {
  const day = $('.js-day-tab.active').attr("data-day-of-week")
  $(".js-meals-component").html('')
  meals.push({
    id: idMeals,
    mealDay: day,
    mealTime: '',
    mealTypeId: '',
    mealType: 'Turno da refeição',
    shift: '',
    plates: []
  })
  idMeals++
  meals.forEach((e) => {
    MealsComponent(e, day).actions.render();
  });
  initializeMealAccordion(meals.length)
});

$(document).on("click", ".js-remove-meal", function () {
  const day = $('.js-day-tab.active').attr("data-day-of-week")
  let mealIdRemoved = $(this).attr("data-id-accordion")
  meals = meals.filter((e) => e.id != mealIdRemoved)
  $(".js-meals-component").html('')
  meals.forEach((e) => {
    MealsComponent(e, day).actions.render();
  });
  initializeMealAccordion()
});

$(document).on("click", '.js-change-pagination', function () {

  let clicked = $(this)
  $('.js-change-pagination.active').removeClass('active');
  clicked.addClass("active");

  let day = clicked.attr("data-day-of-week");

  $(".js-meals-component").html('')
  meals.forEach((e) => {
    MealsComponent(e, day).actions.render();
  });
  initializeMealAccordion()
})