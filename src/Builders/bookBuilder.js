const Book = require('../Models/Book')

class BookBuilder {

    constructor() {
        this.title = null
        this.author = null
        this.isbn = null
        this.pages = null
        this.category = null
    }

    setTitle(title) {
        this.title = title
        return this
    }

    setAuthor(author) {
        this.author = author
        return this
    }

    setIsbn(isbn) {
        this.isbn = isbn
        return this
    }

    setPages(pages) {
        this.pages = pages
        return this
    }

    setCategory(category) {
        this.category = category
        return this
    }

    build() {
        return new Book(
            this.title,
            this.author,
            this.isbn,
            this.pages,
            this.category
        )
    }
}

module.exports = BookBuilder