import { Controller } from '@hotwired/stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller 
{
    static targets = ["menu", "burger"]

    toggle() {
        this.menuTarget.classList.toggle('hidden');
        if(this.menuTarget.classList.contains('hidden')) {
            this.burgerTarget.innerText = '☰';
        } else {
            this.burgerTarget.innerText = 'x';
        }
    }
}
