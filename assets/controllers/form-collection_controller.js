import { Controller } from '@hotwired/stimulus';


export default class extends Controller {
    connect() {
       this.index = this.element.childElementCount;
       const btn = document.createElement('button');
       btn.setAttribute('class', 'btn btn-secondary');
       btn.innerText = 'Ajouter un element';
       btn.setAttribute('type', 'button');
       btn.addEventListener('click', this.addElement);
       this.element.childNodes.forEach(this.addDeleteButton)
       this.element.append(btn);
    }


    /**
     * @param {MouseEvent} e
    */
    addElement = (e) => {
        e.preventDefault()
        const element = document.createRange().createContextualFragment(
            this.element.dataset['prototype'].replaceAll('__name__', this.index)
        ).firstElementChild;
        this.addDeleteButton(element)
        this.index++;
        e.currentTarget.insertAdjacentElement('beforebegin', element);
    }




    /**
     * @param {HTMLElement} item
    */
    addDeleteButton = (item) => {
        const btn = document.createElement('button');
        btn.setAttribute('class', 'btn btn-danger');
        btn.innerText = 'Supprimer';
        btn.setAttribute('type', 'button');
        item.append(btn);
        btn.addEventListener('click', e => {
            e.preventDefault();
            item.remove();
        });
    }
}
