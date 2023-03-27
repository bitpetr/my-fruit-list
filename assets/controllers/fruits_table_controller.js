import { Controller } from '@hotwired/stimulus'
import $ from 'jquery'
import 'bootstrap'
import 'datatables.net'
import 'datatables.net-bs5'
import '../../vendor/omines/datatables-bundle/src/Resources/public/js/datatables.js'

export default class extends Controller {
  connect () {
    const config = JSON.parse(this.element.dataset.config)
    const options = {}
    this.tableName = config.name

    if (this.tableName === 'fruits-fav') {
      options.footerCallback = function (row, data, start, end, display) {
        console.log(row, data)
        const api = this.api()
        let totalCarbohydrates = 0
        let totalProtein = 0
        let totalFat = 0
        let totalCalories = 0
        let totalSugar = 0

        for (let i = start; i < end; i++) {
          const rowData = api.row(i).data()
          totalCarbohydrates += parseFloat(rowData['carbohydrates'])
          totalProtein += parseFloat(rowData['protein'])
          totalFat += parseFloat(rowData['fat'])
          totalCalories += parseFloat(rowData['calories'])
          totalSugar += parseFloat(rowData['sugar'])
        }

        document.querySelector('#totalCarbohydrates').innerHTML = totalCarbohydrates.toFixed(2)
        document.querySelector('#totalProtein').innerHTML = totalProtein.toFixed(2)
        document.querySelector('#totalFat').innerHTML = totalFat.toFixed(2)
        document.querySelector('#totalCalories').innerHTML = totalCalories.toFixed(2)
        document.querySelector('#totalSugar').innerHTML = totalSugar.toFixed(2)
      }
    }

    $(this.element).initDataTables(config, options).then((dt) => this.dt = dt)
  }

  update (e) {
    if (this.tableName === 'fruits-fav') {
      dt.draw()
    }
  }
}