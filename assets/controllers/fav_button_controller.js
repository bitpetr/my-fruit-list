import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
  connect () {
  }

  async toggle () {
    const elem = this.element
    const fruitId = elem.parentElement.parentElement.id
    const isFavorite = elem.innerHTML === '★'

    try {
      const response = await fetch(`/api/fruits/${fruitId}`, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ isFavorite: !isFavorite }),
      })

      if (response.ok) {
        const fruit = await response.json()
        elem.innerHTML = fruit.isFavorite ? '★' : '☆'
        elem.title = fruit.isFavorite ? 'Unfavorite' : 'Favorite'
        this.dispatch('favorite', { detail: fruit })
      } else {
        const data = await response.json()
        alert('Error: ' + (data.error ?? 'Unknown'))
      }
    } catch (error) {
      console.error('Error updating favorite status:', error)
    }
  }
}