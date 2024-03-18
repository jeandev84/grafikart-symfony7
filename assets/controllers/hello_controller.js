import { Controller } from '@hotwired/stimulus';

/*
 * Un controller ici permet de rajouter un comportement particulier sur un element HTML
 *
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
*/
export default class extends Controller {
    connect() {
        /* console.log('Hello') */
        this.element.textContent = 'Hello Stimulus! Edit me in assets/controllers/hello_controller.js';
    }
}
