const Book = require('../Models/Book')
const Magazine = require('../Models/Magazine')

class MaterialFactory {

    static createMaterial(type, data) {

        switch (type) {
            case 'book':
                return new Book(
                    data.title,
                    data.author,
                    data.isbn,
                    data.pages,
                    data.category
                )

            case 'magazine':
                return new Magazine(
                    data.title,
                    data.editor,
                    data.editionNumber
                )

            default:
                throw new Error('Invalid material type')
        }
    }
}

module.exports = MaterialFactory