import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['mainImage'];

    change(event) {
        const newSrc = event.currentTarget.getAttribute('src');
        this.mainImageTarget.setAttribute('src', newSrc);
    }
}
