import {Controller} from "@hotwired/stimulus"
import Masonry from "masonry-layout"
import imagesLoaded from "imagesloaded"

export default class extends Controller {
    connect() {
        imagesLoaded(this.element, () => {
            this.masonry = new Masonry(this.element, {
                itemSelector: ".masonry-item",
                percentPosition: true,
                gutter: 16, // mezera mezi panely
            })
        })
    }

    layout() {
        this.masonry.layout()
    }
}